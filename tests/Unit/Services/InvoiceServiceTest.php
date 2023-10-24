<?php

namespace Tests\Unit\Services;

use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\StripePayment;
use App\Services\SalesTaxService;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    /** @test */
    public function it_processes_invoice(): void
    {
        // Create mock
        // Create Test Doubles
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock = $this->createMock(StripePayment::class);
        $emailServiceMock = $this->createMock(EmailService::class);

        //Stub charge method
        $gatewayServiceMock->method('charge')->willReturn(true);


        // given invoice service
        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $customer = ['name' => 'Misha'];
        $amount = 150;

        // when process is called
        $result = $invoiceService->process($customer, $amount);

        // then assert invoice is processed successfully
        $this->assertTrue($result);
    }

    /** @test */
    public function it_sends_receipt_email_when_invoice_is_processed(): void
    {
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock = $this->createMock(StripePayment::class);
        $emailServiceMock = $this->createMock(EmailService::class);

        $gatewayServiceMock->method('charge')->willReturn(true);

        // Using Mock object to set up expectations
        $emailServiceMock
            ->expects($this->once())
            ->method('send')
            ->with(['name' => 'Misha'], 'receipt');

        // Inject our mocked dependencies
        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $customer = ['name' => 'Misha'];
        $amount = 150;

        $result = $invoiceService->process($customer, $amount);

        $this->assertTrue($result);
    }
}