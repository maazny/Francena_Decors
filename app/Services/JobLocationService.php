<?php

namespace App\Services;

use App\Models\JobLocation;
use Illuminate\Support\Str;

class JobLocationService
{
    public function create(array $data): JobLocation
    {
        $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['name']);
        return JobLocation::create($data);
    }

    public function update(JobLocation $location, array $data): JobLocation
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $location->id);
        }
        $location->update($data);
        return $location;
    }

    public function toggleStatus(JobLocation $location): JobLocation
    {
        $location->update([
            'status' => ! $location->status,
        ]);
        return $location;
    }

    public function restore(int $id): bool
    {
        $location = JobLocation::onlyTrashed()->findOrFail($id);
        return $location->restore();
    }

    public function bulkDelete(array $ids): int
    {
        return JobLocation::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        return JobLocation::whereIn('id', $ids)->update(['status' => $status]);
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (JobLocation::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . ++$i;
        }
        return $slug;
    }
}
