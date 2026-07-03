<?php

namespace App\Services;

use App\Models\JobDepartment;
use Illuminate\Support\Str;

class JobDepartmentService
{
    public function create(array $data): JobDepartment
    {
        $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['name']);
        $dept = JobDepartment::create($data);
        JobOpeningService::clearCache();
        return $dept;
    }

    public function update(JobDepartment $department, array $data): JobDepartment
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $department->id);
        }
        $department->update($data);
        JobOpeningService::clearCache();
        return $department;
    }

    public function toggleStatus(JobDepartment $department): JobDepartment
    {
        $department->update([
            'status' => ! $department->status,
        ]);
        JobOpeningService::clearCache();
        return $department;
    }

    public function restore(int $id): bool
    {
        $department = JobDepartment::onlyTrashed()->findOrFail($id);
        $res = $department->restore();
        JobOpeningService::clearCache();
        return $res;
    }

    public function bulkDelete(array $ids): int
    {
        $count = JobDepartment::whereIn('id', $ids)->delete();
        JobOpeningService::clearCache();
        return $count;
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        $count = JobDepartment::whereIn('id', $ids)->update(['status' => $status]);
        JobOpeningService::clearCache();
        return $count;
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (JobDepartment::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . ++$i;
        }
        return $slug;
    }
}
