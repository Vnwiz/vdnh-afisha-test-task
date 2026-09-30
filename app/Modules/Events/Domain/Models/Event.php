<?php

namespace App\Modules\Events\Domain\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'starts_at',
        'ends_at',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    protected static function newFactory(): EventFactory
    {
        return EventFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event): void {
            if (empty($event->slug)) {
                $event->slug = static::generateUniqueSlug($event);
            }
        });

        static::updating(function (Event $event): void {
            if (empty($event->slug)) {
                $event->slug = static::generateUniqueSlug($event);
            }
        });
    }

    public static function generateUniqueSlug(Event $event): string
    {
        $baseSlug = Str::slug($event->title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $event->id ?? 0)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * @return BelongsToMany<EventCategory, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(EventCategory::class, 'event_event_category');
    }
}
