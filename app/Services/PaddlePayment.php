<?php

declare(strict_types=1);

namespace App\Services;

class PaddlePayment implements PaymentGatewayInterface
{

    public function charge(array $customer, float $amount, float $tax): bool
    {
        return true;
    }
}