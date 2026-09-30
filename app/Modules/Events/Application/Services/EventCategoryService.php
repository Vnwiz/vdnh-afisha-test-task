<?php

namespace App\Modules\Events\Application\Services;

use App\Modules\Events\Domain\DTOs\CreateEventCategoryDTO;
use App\Modules\Events\Domain\DTOs\UpdateEventCategoryDTO;
use App\Modules\Events\Domain\Models\EventCategory;
use App\Modules\Events\Domain\Repositories\EventCategoryRepositoryInterface;
use App\Modules\Events\Domain\Services\EventCategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class EventCategoryService implements EventCategoryServiceInterface
{
    public function __construct(
        private EventCategoryRepositoryInterface $eventCategoryRepository,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): Collection
    {
        return $this->eventCategoryRepository->getAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $id): EventCategory
    {
        $category = $this->eventCategoryRepository->findById($id);

        if (! $category) {
            throw new ModelNotFoundException("Event category with ID {$id} not found.");
        }

        return $category;
    }

    public function create(CreateEventCategoryDTO $dto): EventCategory
    {
        return $this->eventCategoryRepository->create($dto->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, UpdateEventCategoryDTO $dto): EventCategory
    {
        $category = $this->eventCategoryRepository->findById($id);

        if (! $category) {
            throw new ModelNotFoundException("Event category with ID {$id} not found.");
        }

        $data = $dto->toArrayWithoutNulls();
        $this->eventCategoryRepository->update($category, $data);

        return $category->fresh();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $category = $this->eventCategoryRepository->findById($id);

        if (! $category) {
            throw new ModelNotFoundException("Event category with ID {$id} not found.");
        }

        return $this->eventCategoryRepository->delete($category);
    }
}
