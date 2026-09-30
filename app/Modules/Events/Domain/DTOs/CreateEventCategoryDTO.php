<?php

namespace App\Modules\Events\Domain\DTOs;

use App\Core\DTOs\AbstractDTO;

class CreateEventCategoryDTO extends AbstractDTO
{
    public string $name;

    public ?string $slug = null;
}
