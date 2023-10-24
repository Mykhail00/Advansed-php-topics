<?php

declare(strict_types = 1);

namespace App;

abstract class Model
{
    protected DB $db;

    public function __construct()
    {
        $this->db = App::db();
    }

    public function fetchLazy(\PDOStatement $stmt): \Generator
    {
        foreach($stmt as $record) {
            yield $record;
        }
    }
}
