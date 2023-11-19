<?php

declare(strict_types=1);

namespace App\Services\Shipping;

class PackageDimensions
{
    public function __construct(public readonly int $width, public readonly int $height, public readonly int $length)
    {
        match (true) {
            $width <= 0 || $width > 80 => throw new \InvalidArgumentException('Invalid package width'),
            $height <= 0 || $height > 70 => throw new \InvalidArgumentException('Invalid package height'),
            $length <= 0 || $length > 130 => throw new \InvalidArgumentException('Invalid package length'),
            default => true
        };
    }

    public function increaseWidth(int $width): self
    {
        return new self($this->width + $width, $this->height, $this->length);
    }

    public function equalsTo(PackageDimensions $other): bool
    {
        return $this->width === $other->width
            && $this->height === $other->height
            && $this->length === $other->length;
    }
}