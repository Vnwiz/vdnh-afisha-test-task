<?php

namespace App\Modules\Events\Domain\Repositories;

use App\Modules\Events\Domain\DTOs\IndexEventDTO;
use App\Modules\Events\Domain\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, Event>
     */
    public function paginate(IndexEventDTO $dto): LengthAwarePaginator;

    public function findById(int $id): ?Event;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Event;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Event $event, array $data): bool;

    public function delete(Event $event): bool;

    /**
     * @param  list<int>  $categoryIds
     */
    public function syncCategories(Event $event, array $categoryIds): void;
}
