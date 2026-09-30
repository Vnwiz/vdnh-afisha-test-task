<?php

namespace App\Modules\Events\Application\Services;

use App\Modules\Events\Domain\DTOs\CreateEventDTO;
use App\Modules\Events\Domain\DTOs\IndexEventDTO;
use App\Modules\Events\Domain\DTOs\UpdateEventDTO;
use App\Modules\Events\Domain\Models\Event;
use App\Modules\Events\Domain\Repositories\EventRepositoryInterface;
use App\Modules\Events\Domain\Services\EventServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

readonly class EventService implements EventServiceInterface
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function index(IndexEventDTO $dto): LengthAwarePaginator
    {
        return $this->eventRepository->paginate($dto);
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $id): Event
    {
        $event = $this->eventRepository->findById($id);

        if (! $event) {
            throw new ModelNotFoundException("Event with ID {$id} not found.");
        }

        return $event;
    }

    public function create(CreateEventDTO $dto): Event
    {
        return DB::transaction(function () use ($dto): Event {
            $data = $dto->toArray();
            $categoryIds = $data['category_ids'] ?? [];
            unset($data['category_ids']);

            $event = $this->eventRepository->create($data);

            if (! empty($categoryIds)) {
                $this->eventRepository->syncCategories($event, $categoryIds);
            }

            return $event->load('categories');
        });
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, UpdateEventDTO $dto): Event
    {
        $event = $this->eventRepository->findById($id);

        if (! $event) {
            throw new ModelNotFoundException("Event with ID {$id} not found.");
        }

        return DB::transaction(function () use ($event, $dto): Event {
            $raw = $dto->toArray();
            $categoryIds = array_key_exists('category_ids', $raw) ? $raw['category_ids'] : null;

            $data = $dto->toArrayWithoutNulls();
            unset($data['category_ids']);

            if (! empty($data)) {
                $this->eventRepository->update($event, $data);
            }

            if ($categoryIds !== null) {
                $this->eventRepository->syncCategories($event, $categoryIds);
            }

            return $event->fresh(['categories']);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $event = $this->eventRepository->findById($id);

        if (! $event) {
            throw new ModelNotFoundException("Event with ID {$id} not found.");
        }

        return $this->eventRepository->delete($event);
    }
}
