<?php

namespace App\Modules\Events\Application\Repositories;

use App\Modules\Events\Domain\DTOs\IndexEventDTO;
use App\Modules\Events\Domain\Models\Event;
use App\Modules\Events\Domain\Repositories\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventRepository implements EventRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function paginate(IndexEventDTO $dto): LengthAwarePaginator
    {
        return Event::query()
            ->with('categories')
            ->when(
                $dto->categoryIds,
                fn ($query) => $query->whereHas(
                    'categories',
                    fn ($query) => $query->whereIn('event_categories.id', $dto->categoryIds)
                )
            )
            ->when(
                $dto->dateFrom,
                fn ($query) => $query->whereRaw('COALESCE(ends_at, starts_at) >= ?', [$dto->dateFrom])
            )
            ->when(
                $dto->dateTo,
                fn ($query) => $query->where('starts_at', '<=', $dto->dateTo)
            )
            ->orderBy('starts_at')
            ->paginate($dto->perPage, page: $dto->page);
    }

    public function findById(int $id): ?Event
    {
        return Event::query()
            ->with('categories')
            ->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function update(Event $event, array $data): bool
    {
        return $event->update($data);
    }

    public function delete(Event $event): bool
    {
        return (bool) $event->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function syncCategories(Event $event, array $categoryIds): void
    {
        $event->categories()->sync($categoryIds);
    }
}
