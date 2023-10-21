<?php

namespace App;

use App\Exceptions\Container\ContainerException;
use App\Exceptions\Container\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        //Check first if there is explicit binding
        if ($this->has($id)) {
            $entry = $this->entries[$id];

            return $entry($this);
        }

        // Otherwise return method that tries to make autowiring
        return $this->resolve($id);
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

    public function resolve(string $id)
    {
        // 1. Inspect the class that we are trying to get from the container
        $reflectionClass = new \ReflectionClass($id);

        if (! $reflectionClass->isInstantiable()) {
            throw new ContainerException('Class "' . $id . '" is not instantiable');
        }

        // 2. Inspect the constructor of the class
        $constructor = $reflectionClass->getConstructor(); // returns ReflectionMethod or null

        //if there is no constructor, means there is no dependencies we can new up the class
        if (! $constructor) {
            return new $id;
        }
        // 3. Inspect the constructor parameters(dependencies)
        $parameters = $constructor->getParameters(); // return array of ReflectionParameter objects

        if (! $parameters) { // if constructor is empty there are no dependencies
            return new $id;
        }

        // 4. If the constructor parameter is a class than try to resolve that class using the container (RECURSIVELY)
        $dependencies = array_map(
            //function resolves dependencies
            function(\ReflectionParameter $param) use ($id){
                $name = $param->getName();
                $type = $param->getType();

                if (! $type){
                    throw new ContainerException('Failed to resolve class "' . $id . '" because param "' . $name . '" missing type hint');
                }

                if ($type instanceof \ReflectionUnionType){
                    throw new ContainerException('Failed to resolve class "' . $id . '" because of union type param "'. $name . '"');
                }

                if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()){
                    return $this->get($type->getName());
                }

                throw new ContainerException('Failed to resolve class "' . $id . '" because of invalid param "'. $name . '"');

        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}