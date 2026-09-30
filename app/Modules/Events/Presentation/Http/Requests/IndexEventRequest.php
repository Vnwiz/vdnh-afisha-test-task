<?php

namespace App\Modules\Events\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:event_categories,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function queryParameters(): array
    {
        return [
            'category_ids' => [
                'description' => 'ID категорий. Можно передать несколько.',
                'example' => [1],
            ],
            'date_from' => [
                'description' => 'Начало интервала фильтрации.',
                'example' => '2026-09-01 00:00:00',
            ],
            'date_to' => [
                'description' => 'Конец интервала фильтрации.',
                'example' => '2026-09-30 23:59:59',
            ],
            'page' => [
                'description' => 'Номер страницы.',
                'example' => 1,
            ],
            'per_page' => [
                'description' => 'Количество элементов на странице (1-100).',
                'example' => 10,
            ],
        ];
    }
}
