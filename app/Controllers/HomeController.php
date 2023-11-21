<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\App;
use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Put;
use App\Enums\HttpMethod;
use App\View;

class HomeController
{
    #[Get('/')]
    #[Get('/home', HttpMethod::Head)]
    public function index(): View
    {
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
