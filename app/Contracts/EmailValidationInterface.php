<?php

namespace App\Contracts;

interface EmailValidationInterface
{
    public function verify(string $email): array;
}