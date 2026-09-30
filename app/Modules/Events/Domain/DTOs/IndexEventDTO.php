<?php

namespace App\Modules\Events\Domain\DTOs;

use App\Core\DTOs\AbstractDTO;
use App\Core\DTOs\Traits\HasPagination;
use Carbon\Carbon;

class IndexEventDTO extends AbstractDTO
{
    use HasPagination;

    /** @var list<int>|null */
    public ?array $categoryIds = null;

    public ?Carbon $dateFrom = null;

    public ?Carbon $dateTo = null;

    /**
     * @param  list<int|string>|int|string|null  $ids
     * @return list<int>|null
     */
    public static function setCategoryIds(mixed $ids): ?array
    {
        if ($ids === null) {
            return null;
        }

        return array_map(intval(...), (array) $ids);
    }
}
