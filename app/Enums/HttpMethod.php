<?php

namespace App\Enums;

enum HttpMethod: string
{
    case Post = 'post';
    case Get = 'get';
    case Put = 'put';
    case Head = 'head';
}