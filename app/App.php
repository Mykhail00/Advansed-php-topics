<?php

declare(strict_types = 1);

namespace App;

use App\Exceptions\RouteNotFoundException;
use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\PaddlePayment;
use App\Services\StripePayment;
use App\Services\PaymentGatewayInterface;
use App\Services\SalesTaxService;
use Symfony\Component\Mailer\MailerInterface;

class App
{
    private static DB $db;

    public function __construct(
        protected Container $container,
        protected Router $router,
        protected array $request,
        protected Config $config)
    {
        static::$db = new DB($config->db ?? []);

        $this->container->set(
            PaymentGatewayInterface::class, PaddlePayment::class);
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouteNotFoundException) {
            http_response_code(404);

            echo View::make('error/404');
        }
    }
}