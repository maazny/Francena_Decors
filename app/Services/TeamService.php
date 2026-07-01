<?php

namespace App\Services;

use App\Models\TeamDepartment;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TeamService
{
    public const HOMEPAGE_CACHE_KEY = 'homepage_team';
    public const FEATURED_CACHE_KEY = 'featured_team_members';
    public const DEPARTMENTS_CACHE_KEY = 'team_departments';

    public static function clearCache(?TeamMember $member = null, ?TeamDepartment $department = null): void
    {
        Cache::forget(self::HOMEPAGE_CACHE_KEY);
        Cache::forget(self::FEATURED_CACHE_KEY);
        Cache::forget(self::DEPARTMENTS_CACHE_KEY);

        if ($member) {
            Cache::forget('team_member_'.$member->id);
        }

        if ($department) {
            Cache::forget('team_department_'.$department->id);
        }
    }

    public static function makeSlug(string $name, ?string $slug = null): string
    {
        return filled($slug) ? Str::slug($slug) : Str::slug($name);
    }

    public static function buildQuery(array $filters = [])
    {
        $query = TeamMember::query()->with(['department', 'profilePhoto', 'coverPhoto'])->withTrashed();

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['department'])) {
            $query->where('department_id', $filters['department']);
        }

        if (! empty($filters['designation'])) {
            $query->where('designation', 'like', '%'.$filters['designation'].'%');
        }

        if (! empty($filters['experience_min'])) {
            $query->where('experience_years', '>=', (int) $filters['experience_min']);
        }

        if (! empty($filters['featured'])) {
            $query->where('featured', true);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (bool) $filters['status']);
        }

        return $query;
    }

    public static function getHomepageTeam(int $limit = 4)
    {
        return Cache::remember(self::HOMEPAGE_CACHE_KEY, 3600, function () use ($limit) {
            return TeamMember::query()->with(['profilePhoto'])->homepageFeatured()->active()->ordered()->limit($limit)->get();
        });
    }

    public static function getFeaturedMembers(int $limit = 6)
    {
        return Cache::remember(self::FEATURED_CACHE_KEY, 3600, function () use ($limit) {
            return TeamMember::query()->with(['profilePhoto'])->featured()->active()->ordered()->limit($limit)->get();
        });
    }

    public static function getDepartments()
    {
        return Cache::remember(self::DEPARTMENTS_CACHE_KEY, 3600, function () {
            return TeamDepartment::query()->active()->ordered()->get();
        });
    }
}
