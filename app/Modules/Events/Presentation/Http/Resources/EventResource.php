<?php

namespace App\Modules\Events\Presentation\Http\Resources;

use App\Modules\Events\Domain\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Event */
class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'location' => $this->location,
            'categories' => EventCategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
