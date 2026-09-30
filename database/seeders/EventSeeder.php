<?php

namespace Database\Seeders;

use App\Modules\Events\Domain\Models\Event;
use App\Modules\Events\Domain\Models\EventCategory;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = EventCategory::query()->pluck('id');

        if ($categoryIds->isEmpty()) {
            return;
        }

        Event::factory()
            ->count(12)
            ->create()
            ->each(function (Event $event) use ($categoryIds): void {
                $event->categories()->sync(
                    $categoryIds->random(fake()->numberBetween(1, 3))->all()
                );
            });
    }
}
