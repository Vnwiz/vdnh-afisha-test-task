<?php

namespace App\Modules\Events\Presentation\Http\Controllers;

use App\Modules\Events\Domain\DTOs\CreateEventCategoryDTO;
use App\Modules\Events\Domain\DTOs\UpdateEventCategoryDTO;
use App\Modules\Events\Domain\Services\EventCategoryServiceInterface;
use App\Modules\Events\Presentation\Http\Requests\CreateEventCategoryRequest;
use App\Modules\Events\Presentation\Http\Requests\UpdateEventCategoryRequest;
use App\Modules\Events\Presentation\Http\Resources\EventCategoryResource;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * @group EventCategory
 * Категории мероприятий
 */
class EventCategoryController extends Controller
{
    public function __construct(
        private readonly EventCategoryServiceInterface $eventCategoryService,
    ) {
    }

    /**
     * Список категорий
     *
     * @operationId eventCategory.index
     *
     * @apiResourceCollection App\Modules\Events\Presentation\Http\Resources\EventCategoryResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\EventCategory
     */
    public function index(): JsonResponse
    {
        $categories = $this->eventCategoryService->getAll();

        return ApiResponse::success(EventCategoryResource::collection($categories));
    }

    /**
     * Получение категории по ID
     *
     * @operationId eventCategory.show
     *
     * @apiResource App\Modules\Events\Presentation\Http\Resources\EventCategoryResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\EventCategory
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->eventCategoryService->getById($id);

        return ApiResponse::success(EventCategoryResource::make($category));
    }

    /**
     * Создание категории
     *
     * @operationId eventCategory.create
     *
     * @apiResource App\Modules\Events\Presentation\Http\Resources\EventCategoryResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\EventCategory
     */
    public function create(CreateEventCategoryRequest $request): JsonResponse
    {
        $dto = CreateEventCategoryDTO::fromRequestValidated($request);
        $category = $this->eventCategoryService->create($dto);

        return ApiResponse::created(EventCategoryResource::make($category));
    }

    /**
     * Обновление категории
     *
     * @operationId eventCategory.update
     *
     * @apiResource App\Modules\Events\Presentation\Http\Resources\EventCategoryResource
     *
     * @apiResourceModel App\Modules\Events\Domain\Models\EventCategory
     */
    public function update(int $id, UpdateEventCategoryRequest $request): JsonResponse
    {
        $dto = UpdateEventCategoryDTO::fromRequestValidated($request);
        $category = $this->eventCategoryService->update($id, $dto);

        return ApiResponse::success(EventCategoryResource::make($category));
    }

    /**
     * Удаление категории
     *
     * @operationId eventCategory.delete
     */
    public function delete(int $id): JsonResponse
    {
        $this->eventCategoryService->delete($id);

        return ApiResponse::success();
    }
}
