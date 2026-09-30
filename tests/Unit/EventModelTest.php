<?php

namespace Tests\Unit;

use App\Modules\Events\Domain\Models\Event;
use PHPUnit\Framework\TestCase;

class EventModelTest extends TestCase
{
    public function test_fillable_contains_schedule_fields(): void
    {
        $event = new Event();

        $this->assertSame(
            ['title', 'slug', 'description', 'starts_at', 'ends_at', 'location'],
            $event->getFillable()
        );
    }
}
