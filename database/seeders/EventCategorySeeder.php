<?php

namespace Database\Seeders;

use App\Modules\Events\Domain\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Концерты',
            'Выставки',
            'Экскурсии',
            'Детям',
            'Спорт',
            'Фестивали',
        ];

        foreach ($categories as $name) {
            EventCategory::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
