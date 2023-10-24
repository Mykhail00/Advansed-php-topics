<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Ticket;
use Generator;

class GeneratorExampleController
{
    public function __construct(private Ticket $ticketModel)
    {
    }

    public function index()
    {
        foreach ($this->ticketModel->all() as $ticket) {
            echo $ticket['id'] . ' :' . substr($ticket['content'], 0, 15) . '<br/>';
        }
    }
}