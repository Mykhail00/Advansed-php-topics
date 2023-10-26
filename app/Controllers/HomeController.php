<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\App;
use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Put;
use App\Attributes\Route;
use App\Container;
use App\Enums\HttpMethod;
use App\Services\InvoiceService;
use App\View;

class HomeController
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    #[Get('/')]
    #[Get('/home', HttpMethod::Head)]
    public function index(): View
    {
        $this->invoiceService->process([], 25);
        return View::make('index');
    }

    #[Post('/store')]
    public function store()
    {

    }

    #[Put('/update')]
    public function update()
    {
    }
}
