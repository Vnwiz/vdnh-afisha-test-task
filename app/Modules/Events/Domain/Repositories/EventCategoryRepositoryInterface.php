<?php

namespace App\Modules\Events\Domain\Repositories;

use App\Modules\Events\Domain\Models\EventCategory;
use Illuminate\Database\Eloquent\Collection;

interface EventCategoryRepositoryInterface
{
    /**
     * @return Collection<int, EventCategory>
     */
    public function getAll(): Collection;

    public function findById(int $id): ?EventCategory;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): EventCategory;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(EventCategory $category, array $data): bool;

    public function delete(EventCategory $category): bool;
}
