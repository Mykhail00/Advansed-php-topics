<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\InvoiceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:my-command', description: 'My command')]
class MyCommand extends Command
{
    public function __construct(private readonly InvoiceService $invoiceService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('Paid invoices: ' . count($this->invoiceService->getPaidInvoices()), true);

        return Command::SUCCESS;
    }
}