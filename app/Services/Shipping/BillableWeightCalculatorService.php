<?php

declare(strict_types=1);

namespace App\Services\Shipping;

class BillableWeightCalculatorService
{
    public function calculate(
        PackageDimensions $dimensions,
        Weight $weight,
        DimDivisor $dimDivisor,
    ): int {
        $dimWeight = (int) round(
            $dimensions->width * $dimensions->height * $dimensions->length / $dimDivisor->value);
        return max($weight->value, $dimWeight);
    }
}