<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Container;
use App\Exceptions\RouteNotFoundException;
use App\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;
    protected function setUp(): void
    {
        parent::setUp();
        $container = new Container();
        $this->router = new Router($container);
    }
    /**
     * @test
     */
    public function it_registers_a_route(): void
    {
        $this->router->register('get', '/users', ['Users', 'index']);

        $expected = [
            'get' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertSame($expected, $this->router->routes());
    }

    /** @test */
    public function it_registers_a_get_route(): void
    {

        $this->router->get('/users', ['Users', 'index']);

        $expected = [
            'get' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertSame($expected, $this->router->routes());
    }

    /** @test */
    public function it_registers_a_post_route(): void
    {

        $this->router->post('/users', ['Users', 'index']);

        $expected = [
            'post' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertSame($expected, $this->router->routes());
    }

    /** @test */
    public function there_is_no_routes_when_router_is_created(): void
    {
        $container = new Container();
        $router = new Router($container);

        $this->assertEmpty($router->routes());
    }

    /**
     * @test
     * @dataProvider Tests\DataProviders\RouterDataProvider::routeNotFoundCases()
     */
    public function it_throws_route_not_found_exception(
        string $requestUri,
        string $requestMethod
    ): void
    {
        $user = new class() {
            public function delete(): bool
            {
                return true;
            }
        };

        $this->router->post('/users', [$user::class, 'store']);
        $this->router->get('/users', ['Users', 'index']);

        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    /** @test */
    public function it_resolves_a_route_from_callable(): void
    {
        $this->router->get('/user', fn() => [1, 2, 3]);
        $this->assertSame(
            [1, 2, 3],
            $this->router->resolve('/user', 'get')
        );
    }

    /** @test */
    public function it_resolves_route(): void
    {
        $user = new class() {
          public function index() {
              return [1, 2, 3];
          }
        };

        $this->router->get('/user', [$user::class, 'index']);

        $this->assertSame(
            [1, 2, 3],
            $this->router->resolve('/user', 'get')
        );
    }
}