<?php

namespace App\Modules\Events\Application\Repositories;

use App\Modules\Events\Domain\Models\EventCategory;
use App\Modules\Events\Domain\Repositories\EventCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventCategoryRepository implements EventCategoryRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(): Collection
    {
        return EventCategory::query()
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?EventCategory
    {
        return EventCategory::query()->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): EventCategory
    {
        return EventCategory::create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function update(EventCategory $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(EventCategory $category): bool
    {
        return (bool) $category->delete();
    }
}
