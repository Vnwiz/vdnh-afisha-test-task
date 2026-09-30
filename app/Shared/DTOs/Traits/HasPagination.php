<?php

namespace App\Core\DTOs\Traits;

trait HasPagination
{
    public int $perPage = 10;

    public int $page = 1;
}
