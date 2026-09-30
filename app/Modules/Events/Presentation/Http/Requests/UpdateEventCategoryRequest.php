<?php

namespace App\Modules\Events\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = (int) $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('event_categories', 'slug')->ignore($categoryId)],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Название категории.',
                'example' => 'Концерты',
            ],
            'slug' => [
                'description' => 'Уникальный slug.',
                'example' => 'kontserty',
            ],
        ];
    }
}
