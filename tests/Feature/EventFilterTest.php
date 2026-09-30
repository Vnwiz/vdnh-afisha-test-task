<?php

namespace Tests\Feature;

use App\Modules\Events\Domain\Models\Event;
use App\Modules\Events\Domain\Models\EventCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_filters_events_by_multiple_categories(): void
    {
        $concerts = EventCategory::factory()->create(['name' => 'Концерты']);
        $exhibitions = EventCategory::factory()->create(['name' => 'Выставки']);
        $sports = EventCategory::factory()->create(['name' => 'Спорт']);

        $concertEvent = Event::factory()->create([
            'title' => 'Концерт',
            'starts_at' => '2026-10-10 18:00:00',
            'ends_at' => '2026-10-10 20:00:00',
        ]);
        $concertEvent->categories()->sync([$concerts->id]);

        $exhibitionEvent = Event::factory()->create([
            'title' => 'Выставка',
            'starts_at' => '2026-10-11 12:00:00',
            'ends_at' => '2026-10-11 18:00:00',
        ]);
        $exhibitionEvent->categories()->sync([$exhibitions->id]);

        $sportEvent = Event::factory()->create([
            'title' => 'Матч',
            'starts_at' => '2026-10-12 15:00:00',
            'ends_at' => '2026-10-12 17:00:00',
        ]);
        $sportEvent->categories()->sync([$sports->id]);

        $response = $this->getJson('/api/v1/events?'.http_build_query([
            'category_ids' => [$concerts->id, $exhibitions->id],
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.pagination.total', 2);

        $titles = collect($response->json('data.items'))->pluck('title')->all();

        $this->assertContains('Концерт', $titles);
        $this->assertContains('Выставка', $titles);
        $this->assertNotContains('Матч', $titles);
    }

    public function test_filters_events_by_date_range(): void
    {
        Event::factory()->create([
            'title' => 'До диапазона',
            'starts_at' => '2026-09-20 12:00:00',
            'ends_at' => '2026-09-20 14:00:00',
        ]);

        Event::factory()->create([
            'title' => 'Внутри диапазона',
            'starts_at' => '2026-10-15 12:00:00',
            'ends_at' => '2026-10-15 14:00:00',
        ]);

        Event::factory()->create([
            'title' => 'Пересекает конец диапазона',
            'starts_at' => '2026-10-30 20:00:00',
            'ends_at' => '2026-11-01 02:00:00',
        ]);

        Event::factory()->create([
            'title' => 'После диапазона',
            'starts_at' => '2026-11-10 12:00:00',
            'ends_at' => '2026-11-10 14:00:00',
        ]);

        $response = $this->getJson('/api/v1/events?'.http_build_query([
            'date_from' => '2026-10-01 00:00:00',
            'date_to' => '2026-10-31 23:59:59',
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('data.pagination.total', 2);

        $titles = collect($response->json('data.items'))->pluck('title')->all();

        $this->assertContains('Внутри диапазона', $titles);
        $this->assertContains('Пересекает конец диапазона', $titles);
        $this->assertNotContains('До диапазона', $titles);
        $this->assertNotContains('После диапазона', $titles);
    }

    public function test_filters_events_by_categories_and_date_range_with_pagination(): void
    {
        $concerts = EventCategory::factory()->create(['name' => 'Концерты']);
        $sports = EventCategory::factory()->create(['name' => 'Спорт']);

        foreach (range(1, 3) as $i) {
            $event = Event::factory()->create([
                'title' => "Концерт {$i}",
                'starts_at' => sprintf('2026-10-%02d 19:00:00', 10 + $i),
                'ends_at' => sprintf('2026-10-%02d 21:00:00', 10 + $i),
            ]);
            $event->categories()->sync([$concerts->id]);
        }

        $sportEvent = Event::factory()->create([
            'title' => 'Спорт вне фильтра категорий',
            'starts_at' => '2026-10-15 12:00:00',
            'ends_at' => '2026-10-15 14:00:00',
        ]);
        $sportEvent->categories()->sync([$sports->id]);

        $outsideRange = Event::factory()->create([
            'title' => 'Концерт вне дат',
            'starts_at' => '2026-12-01 19:00:00',
            'ends_at' => '2026-12-01 21:00:00',
        ]);
        $outsideRange->categories()->sync([$concerts->id]);

        $page1 = $this->getJson('/api/v1/events?'.http_build_query([
            'category_ids' => [$concerts->id],
            'date_from' => '2026-10-01 00:00:00',
            'date_to' => '2026-10-31 23:59:59',
            'page' => 1,
            'per_page' => 2,
        ]));

        $page1
            ->assertOk()
            ->assertJsonPath('data.pagination.total', 3)
            ->assertJsonPath('data.pagination.per_page', 2)
            ->assertJsonPath('data.pagination.current_page', 1)
            ->assertJsonPath('data.pagination.last_page', 2)
            ->assertJsonCount(2, 'data.items');

        $page2 = $this->getJson('/api/v1/events?'.http_build_query([
            'category_ids' => [$concerts->id],
            'date_from' => '2026-10-01 00:00:00',
            'date_to' => '2026-10-31 23:59:59',
            'page' => 2,
            'per_page' => 2,
        ]));

        $page2
            ->assertOk()
            ->assertJsonPath('data.pagination.current_page', 2)
            ->assertJsonCount(1, 'data.items');

        $allTitles = collect($page1->json('data.items'))
            ->merge($page2->json('data.items'))
            ->pluck('title')
            ->all();

        $this->assertEqualsCanonicalizing(
            ['Концерт 1', 'Концерт 2', 'Концерт 3'],
            $allTitles
        );
    }
}
