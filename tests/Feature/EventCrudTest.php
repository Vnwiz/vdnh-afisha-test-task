<?php

namespace Tests\Feature;

use App\Modules\Events\Domain\Models\Event;
use App\Modules\Events\Domain\Models\EventCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_and_list_events(): void
    {
        $category = EventCategory::factory()->create([
            'name' => 'Концерты',
        ]);

        $createResponse = $this->postJson('/api/v1/events', [
            'title' => 'Вечер на ВДНХ',
            'description' => 'Описание',
            'starts_at' => '2026-10-15 19:00:00',
            'ends_at' => '2026-10-15 21:00:00',
            'location' => 'Павильон №1',
            'category_ids' => [$category->id],
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Вечер на ВДНХ')
            ->assertJsonPath('data.categories.0.id', $category->id);

        $indexResponse = $this->getJson('/api/v1/events');

        $indexResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.pagination.total', 1);
    }

    public function test_can_update_and_delete_event(): void
    {
        $event = Event::factory()->create([
            'title' => 'Старое название',
        ]);

        $this->putJson('/api/v1/events/'.$event->id, [
            'title' => 'Новое название',
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Новое название');

        $this->deleteJson('/api/v1/events/'.$event->id)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson('/api/v1/events/'.$event->id)
            ->assertNotFound();
    }

    public function test_can_manage_event_categories(): void
    {
        $this->postJson('/api/v1/event-categories', [
            'name' => 'Выставки',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Выставки');

        $this->getJson('/api/v1/event-categories')
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
