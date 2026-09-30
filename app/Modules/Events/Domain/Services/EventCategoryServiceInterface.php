<?php

namespace App\Modules\Events\Domain\Services;

use App\Modules\Events\Domain\DTOs\CreateEventCategoryDTO;
use App\Modules\Events\Domain\DTOs\UpdateEventCategoryDTO;
use App\Modules\Events\Domain\Models\EventCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface EventCategoryServiceInterface
{
    /**
     * @return Collection<int, EventCategory>
     */
    public function getAll(): Collection;

    /**
     * @throws ModelNotFoundException
     */
    public function getById(int $id): EventCategory;

    public function create(CreateEventCategoryDTO $dto): EventCategory;

    /**
     * @throws ModelNotFoundException
     */
    public function update(int $id, UpdateEventCategoryDTO $dto): EventCategory;

    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool;
}
