<?php

namespace App\Services;

use App\Models\JobCategory;
use Illuminate\Support\Str;

class JobCategoryService
{
    public function create(array $data): JobCategory
    {
        $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['name']);
        return JobCategory::create($data);
    }

    public function update(JobCategory $category, array $data): JobCategory
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
        }
        $category->update($data);
        return $category;
    }

    public function toggleStatus(JobCategory $category): JobCategory
    {
        $category->update([
            'status' => ! $category->status,
        ]);
        return $category;
    }

    public function restore(int $id): bool
    {
        $category = JobCategory::onlyTrashed()->findOrFail($id);
        return $category->restore();
    }

    public function bulkDelete(array $ids): int
    {
        return JobCategory::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        return JobCategory::whereIn('id', $ids)->update(['status' => $status]);
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
