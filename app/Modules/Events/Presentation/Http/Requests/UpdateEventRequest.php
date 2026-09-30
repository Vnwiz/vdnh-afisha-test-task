<?php

namespace App\Modules\Events\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eventId = (int) $this->route('id');

        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($eventId)],
            'description' => ['nullable', 'string'],
            'starts_at' => ['sometimes', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:event_categories,id'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Название мероприятия.',
                'example' => 'Вечер на ВДНХ',
            ],
            'slug' => [
                'description' => 'Уникальный slug.',
                'example' => 'vecher-na-vdnh',
            ],
            'description' => [
                'description' => 'Описание мероприятия.',
                'example' => 'Концерт под открытым небом',
            ],
            'starts_at' => [
                'description' => 'Дата и время начала.',
                'example' => '2026-10-15 19:00:00',
            ],
            'ends_at' => [
                'description' => 'Дата и время окончания.',
                'example' => '2026-10-15 21:00:00',
            ],
            'location' => [
                'description' => 'Место проведения.',
                'example' => 'Павильон №1',
            ],
            'category_ids' => [
                'description' => 'ID категорий.',
                'example' => [1],
            ],
        ];
    }
}
