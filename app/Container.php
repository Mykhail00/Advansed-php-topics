<?php

namespace App;

use App\Exceptions\Container\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if (! $this->entries[$id]) {
         throw new NotFoundException('Class "'. $id . '" has no binding');
        }

        $entry = $this->entries[$id];

        //return callback and pass container instance as an argument,
        //this way callback function has access to the container instance and
        //can get its own dependencies
        return $entry($this);
    }

    public function has(string $id): bool
    {
        return (isset($this->entries[$id]));
    }

    // $concrete is a resolver function
    public function set(string $id, callable $concrete): void
    {
        $this->entries[$id] = $concrete;
    }
}