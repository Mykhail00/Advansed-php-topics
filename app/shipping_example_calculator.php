<?php

declare(strict_types=1);

use App\Services\Shipping\BillableWeightCalculatorService;
use App\Services\Shipping\DimDivisor;
use App\Services\Shipping\PackageDimensions;
use App\Services\Shipping\Weight;

require __DIR__ . '/../vendor/autoload.php';

$package = [
    'weight' => 8,
    'dimensions' => [
        'width' => 9,
        'height' => 7,
        'length' => 15,
    ]
];

$packageDimensions = new PackageDimensions(
    $package['dimensions']['width'],
    $package['dimensions']['height'],
    $package['dimensions']['length'],
);

$billableWeight = (new BillableWeightCalculatorService())->calculate(
    $packageDimensions,
    new Weight($package['weight']),
    DimDivisor::FEDEX
);

$widerPackageDimensions = $packageDimensions->increaseWidth(10);

$widerPackageBillableWeight = (new BillableWeightCalculatorService())->calculate(
    $widerPackageDimensions,
    new Weight($package['weight']),
    DimDivisor::FEDEX
);

echo $billableWeight . ' lb' . PHP_EOL;
echo $widerPackageBillableWeight . ' lb' . PHP_EOL;