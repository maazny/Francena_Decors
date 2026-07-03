<?php

namespace App\Services;

use App\Models\JobCategory;
use Illuminate\Support\Str;

class JobCategoryService
{
    public function create(array $data): JobCategory
    {
        $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['name']);
        $cat = JobCategory::create($data);
        JobOpeningService::clearCache();
        return $cat;
    }

    public function update(JobCategory $category, array $data): JobCategory
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
        }
        $category->update($data);
        JobOpeningService::clearCache();
        return $category;
    }

    public function toggleStatus(JobCategory $category): JobCategory
    {
        $category->update([
            'status' => ! $category->status,
        ]);
        JobOpeningService::clearCache();
        return $category;
    }

    public function restore(int $id): bool
    {
        $category = JobCategory::onlyTrashed()->findOrFail($id);
        $res = $category->restore();
        JobOpeningService::clearCache();
        return $res;
    }

    public function bulkDelete(array $ids): int
    {
        $count = JobCategory::whereIn('id', $ids)->delete();
        JobOpeningService::clearCache();
        return $count;
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        $count = JobCategory::whereIn('id', $ids)->update(['status' => $status]);
        JobOpeningService::clearCache();
        return $count;
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (JobCategory::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . ++$i;
        }
        return $slug;
    }
}
