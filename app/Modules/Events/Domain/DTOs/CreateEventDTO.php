<?php

namespace App\Modules\Events\Domain\DTOs;

use App\Core\DTOs\AbstractDTO;
use Carbon\Carbon;

class CreateEventDTO extends AbstractDTO
{
    public string $title;

    public ?string $slug = null;

    public ?string $description = null;

    public Carbon $startsAt;

    public ?Carbon $endsAt = null;

    public ?string $location = null;

    /** @var list<int>|null */
    public ?array $categoryIds = null;

    public static function setStartsAt(mixed $value): Carbon
    {
        return Carbon::parse($value);
    }

    public static function setEndsAt(mixed $value): ?Carbon
    {
        return $value === null || $value === '' ? null : Carbon::parse($value);
    }

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
