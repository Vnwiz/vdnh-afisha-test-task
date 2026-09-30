<?php

namespace App\Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Illuminate\Pagination\LengthAwarePaginator
 */
class PaginationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
        ];
    }
}
