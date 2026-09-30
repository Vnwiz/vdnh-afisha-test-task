<?php

namespace App\Modules\Events\Domain\Services;

use App\Modules\Events\Domain\DTOs\CreateEventDTO;
use App\Modules\Events\Domain\DTOs\IndexEventDTO;
use App\Modules\Events\Domain\DTOs\UpdateEventDTO;
use App\Modules\Events\Domain\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface EventServiceInterface
{
    /**
     * @return LengthAwarePaginator<int, Event>
     */
    public function index(IndexEventDTO $dto): LengthAwarePaginator;

    /**
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Event;

    public function create(CreateEventDTO $dto): Event;

    /**
     * @throws ModelNotFoundException
     */
    public function update(int $id, UpdateEventDTO $dto): Event;

    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool;
}
