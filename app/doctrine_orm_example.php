<?php

declare(strict_types=1);

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Enums\InvoiceStatus;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Create entity manager
$params = [
    'dbname' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
];

$entityManager = new EntityManager(
    DriverManager::getConnection($params),
    ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/Entity']));

$queryBuilder = $entityManager->createQueryBuilder();

// WHERE amount > :amount AND (status = :status OR created_at >= :date)

$query = $queryBuilder
    ->select('i', 'it')
    ->from(Invoice::class, 'i')
    ->join('i.items', 'it')
    ->where(
        $queryBuilder->expr()->andX(
            $queryBuilder->expr()->gt('i.amount', ':amount'),
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq('i.status', ':status'),
                $queryBuilder->expr()->gte('i.createdAt',  ':date')
            )
        )
    )
    ->setParameter(':amount', 40)
    ->setParameter(':status', InvoiceStatus::Paid->value)
    ->setParameter(':date', '2023-11-03 00:00:00')
    ->getQuery();

$invoices = $query->getResult();

//$invoice = $query->getArrayResult();
//var_dump($invoice);

/** @var Invoice $invoice */
foreach ($invoices as $invoice) {
    echo $invoice->getCreatedAt()->format('d/m/Y g:ia')
        . ', ' . $invoice->getAmount()
        . ', ' . $invoice->getStatus()->toString() . PHP_EOL;
}