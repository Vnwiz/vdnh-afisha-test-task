<?php

namespace Database\Factories;

use App\Modules\Events\Domain\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        $startsAt = fake()->dateTimeBetween('now', '+3 months');

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'description' => fake()->optional()->paragraph(),
            'starts_at' => $startsAt,
            'ends_at' => fake()->boolean(70)
                ? (clone $startsAt)->modify('+'.fake()->numberBetween(1, 8).' hours')
                : null,
            'location' => fake()->optional()->randomElement([
                'Павильон №1',
                'Главная аллея',
                'Зелёный театр',
                'Рабочий и колхозница',
            ]),
        ];
    }
}
