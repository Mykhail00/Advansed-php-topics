<?php

declare(strict_types = 1);

namespace App\Services;

class InvoiceService
{
    public function __construct(
        protected SalesTaxService $salesTaxService,
        protected PaymentGatewayInterface $paymentGateway,
        protected EmailService $emailService
    ) {
    }

    public function process(array $customer, float $amount): bool
    {
        // 1. calculate sales tax
        $tax = $this->salesTaxService->calculate($amount, $customer);

        // 2. process invoice
        if (! $this->paymentGateway->charge($customer, $amount, $tax)) {
            return false;
        }

        // 3. send receipt
        $this->emailService->send($customer, 'receipt');

        echo 'Invoice processed successfully</br>';

        return true;
    }
}