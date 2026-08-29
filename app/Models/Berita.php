<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'image',
        'published_at',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Berita $berita): void {
            if (! $berita->slug || $berita->isDirty('title')) {
                $base = Str::slug($berita->title);
                $slug = $base;
                $number = 2;

                while (
                    static::query()
                        ->where('slug', $slug)
                        ->when(
                            $berita->exists,
                            fn (Builder $query) => $query
                                ->where('id', '!=', $berita->id)
                        )
                        ->exists()
                ) {
                    $slug = $base.'-'.$number;
                    $number++;
                }

                $berita->slug = $slug;
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
