<?php

namespace App\Modules\Events\Domain\DTOs;

use App\Core\DTOs\AbstractDTO;

class UpdateEventCategoryDTO extends AbstractDTO
{
    public ?string $name = null;

    public ?string $slug = null;
}
