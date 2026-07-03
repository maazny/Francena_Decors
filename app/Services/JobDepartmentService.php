<?php

namespace App\Services;

use App\Models\JobDepartment;
use Illuminate\Support\Str;

class JobDepartmentService
{
    public function create(array $data): JobDepartment
    {
        $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['name']);
        return JobDepartment::create($data);
    }

    public function update(JobDepartment $department, array $data): JobDepartment
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $department->id);
        }
        $department->update($data);
        return $department;
    }

    public function toggleStatus(JobDepartment $department): JobDepartment
    {
        $department->update([
            'status' => ! $department->status,
        ]);
        return $department;
    }

    public function restore(int $id): bool
    {
        $department = JobDepartment::onlyTrashed()->findOrFail($id);
        return $department->restore();
    }

    public function bulkDelete(array $ids): int
    {
        return JobDepartment::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        return JobDepartment::whereIn('id', $ids)->update(['status' => $status]);
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
