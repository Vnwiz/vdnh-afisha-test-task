<?php

namespace App\Modules\Events\Presentation\Http\Controllers;

use App\Core\Resources\PaginationResource;
use App\Modules\Events\Domain\DTOs\CreateEventDTO;
use App\Modules\Events\Domain\DTOs\IndexEventDTO;
use App\Modules\Events\Domain\DTOs\UpdateEventDTO;
use App\Modules\Events\Domain\Services\EventServiceInterface;
use App\Modules\Events\Presentation\Http\Requests\CreateEventRequest;
use App\Modules\Events\Presentation\Http\Requests\IndexEventRequest;
use App\Modules\Events\Presentation\Http\Requests\UpdateEventRequest;
use App\Modules\Events\Presentation\Http\Resources\EventResource;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * @group Event
 * Афиша мероприятий
 */
class EventController extends Controller
{
    public function __construct(
        private readonly EventServiceInterface $eventService,
    ) {
    }

    /**
     * Список мероприятий
     *
     * @operationId event.index
     *
     * @apiResourceCollection App\Modules\Events\Presentation\Http\Resources\EventResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\Event
     *
     * @apiResourceAdditional pagination="{'total': 100, 'per_page': 10, 'current_page': 1, 'last_page': 10}"
     */
    public function index(IndexEventRequest $request): JsonResponse
    {
        $dto = IndexEventDTO::fromRequestValidated($request);
        $paginator = $this->eventService->index($dto);

        return ApiResponse::success([
            'items' => EventResource::collection($paginator->items()),
            'pagination' => PaginationResource::make($paginator),
        ]);
    }

    /**
     * Получение мероприятия по ID
     *
     * @operationId event.show
     *
     * @apiResource App\Modules\Events\Presentation\Http\Resources\EventResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\Event
     */
    public function show(int $id): JsonResponse
    {
        $event = $this->eventService->getById($id);

        return ApiResponse::success(EventResource::make($event));
    }

    /**
     * Создание мероприятия
     *
     * @operationId event.create
     *
     * @apiResource App\Modules\Events\Presentation\Http\Resources\EventResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\Event
     */
    public function create(CreateEventRequest $request): JsonResponse
    {
        $dto = CreateEventDTO::fromRequestValidated($request);
        $event = $this->eventService->create($dto);

        return ApiResponse::created(EventResource::make($event));
    }

    /**
     * Обновление мероприятия
     *
     * @operationId event.update
     *
     * @apiResource App\Modules\Events\Presentation\Http\Resources\EventResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\Event
     */
    public function update(int $id, UpdateEventRequest $request): JsonResponse
    {
        $dto = UpdateEventDTO::fromRequestValidated($request);
        $event = $this->eventService->update($id, $dto);

        return ApiResponse::success(EventResource::make($event));
    }

    /**
     * Удаление мероприятия
     *
     * @operationId event.delete
     */
    public function delete(int $id): JsonResponse
    {
        $this->eventService->delete($id);

        return ApiResponse::success();
    }
}
