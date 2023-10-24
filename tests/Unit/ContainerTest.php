<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Container;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    /** @test */
    public function it_sets_an_entry_from_a_string(): void
    {
        $this->container->set('id', 'SomeClass');
        $this->assertSame($this->container->has('id'), true);
    }

    /** @test */
    public function it_sets_an_entry_from_a_callable(): void
    {
        $this->container->set('id', fn() => 'some return');
        $this->assertSame($this->container->has('id'), true);
    }

    /** @test */
    public function it_binds_an_entry_from_a_callable(): void
    {

        $someClass = new class {
        };

        $class = new class {
            public function f(){
                return 'some return';
            }
        };

        $this->container->set($someClass::class, fn() => new $class);

        $this->assertEquals(
            $this->container->get($someClass::class)->f(),
            $class->f());

    }


    /** @test */
    public function it_binds_an_entry_from_a_string(): void
    {
        $classInterface = new class {
        };

        $class = new class {
            public function f(){
                return 'some return';
            }
        };

        $this->container->set($classInterface::class, $class::class);

        $this->assertEquals(
            $this->container->get($classInterface::class)->f(),
            $class->f());
    }

    /** @test */
    public function it_resolves_a_simple_class(): void
    {
        $class = new class {
            public function f()
            {
                return 'some return';
            }
        };

        $this->assertEquals( $this->container->resolve($class::class), new $class);
    }
}