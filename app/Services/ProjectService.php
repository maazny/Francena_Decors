<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProjectService
{
    public static function clearCache(?Project $project = null, ?ProjectCategory $category = null): void
    {
        Cache::forget('featured_projects');
        Cache::forget('homepage_projects');
        Cache::forget('project_categories');

        if ($project) {
            Cache::forget('project_'.$project->id);
        }

        if ($category) {
            Cache::forget('project_category_'.$category->id);
        }
    }

    public static function makeSlug(string $title, ?string $slug = null): string
    {
        return filled($slug) ? Str::slug($slug) : Str::slug($title);
    }

    public static function buildQuery(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = Project::query()->with(['category', 'coverImage', 'bannerImage'])->published()->ordered();

        if (! empty($filters['category'])) {
            $query->where('project_category_id', $filters['category']);
        }

        if (! empty($filters['location'])) {
            $query->where('location', 'like', '%'.$filters['location'].'%');
        }

        if (! empty($filters['year'])) {
            $query->whereYear('start_date', $filters['year']);
        }

        if (! empty($filters['budget'])) {
            $query->where('budget', '<=', $filters['budget']);
        }

        if (! empty($filters['completion_status'])) {
            $query->where('completion_percentage', $filters['completion_status']);
        }

        if (! empty($filters['featured'])) {
            $query->where('featured', true);
        }

        return $query;
    }
}
