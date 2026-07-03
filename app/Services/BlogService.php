<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogService
{
    public function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (BlogPost::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.++$i;
        }

        return $slug;
    }

    public function estimateReadingTime(?string $content): int
    {
        if (empty($content)) {
            return 0;
        }

        // average 200 words per minute
        $words = str_word_count(strip_tags($content));
        return (int) max(1, round($words / 200));
    }

    public static function clearCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('blog.homepage_posts');
        \Illuminate\Support\Facades\Cache::forget('blog.latest_posts');
        \Illuminate\Support\Facades\Cache::forget('blog.featured_posts');
        \Illuminate\Support\Facades\Cache::forget('blog.popular_posts');
        \Illuminate\Support\Facades\Cache::forget('blog.categories');
    }
}
