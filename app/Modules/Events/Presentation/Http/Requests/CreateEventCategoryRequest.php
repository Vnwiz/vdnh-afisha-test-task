<?php

namespace App\Modules\Events\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:event_categories,slug'],
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
                'description' => 'Уникальный slug. Если не передан — сгенерируется из name.',
                'example' => 'kontserty',
            ],
        ];
    }
}
