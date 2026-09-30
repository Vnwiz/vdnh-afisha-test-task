<?php

namespace App\Modules\Events\Domain\Models;

use Database\Factories\EventCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class EventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function newFactory(): EventCategoryFactory
    {
        return EventCategoryFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (EventCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category);
            }
        });
    }

    public static function generateUniqueSlug(EventCategory $category): string
    {
        $baseSlug = Str::slug($category->name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $category->id ?? 0)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * @return BelongsToMany<Event, $this>
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_event_category');
    }
}
