<?php

namespace App\Services;

use App\Models\ContactCategory;
use Illuminate\Support\Str;

class ContactCategoryService
{
    public function create(array $data): ContactCategory
    {
        $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['name']);
        return ContactCategory::create($data);
    }

    public function update(ContactCategory $category, array $data): ContactCategory
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
        }
        $category->update($data);
        return $category;
    }

    public function toggleStatus(ContactCategory $category): ContactCategory
    {
        $category->update([
            'status' => ! $category->status,
        ]);
        return $category;
    }

    public function restore(int $id): bool
    {
        $category = ContactCategory::onlyTrashed()->findOrFail($id);
        return $category->restore();
    }

    public function bulkDelete(array $ids): int
    {
        return ContactCategory::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        return ContactCategory::whereIn('id', $ids)->update(['status' => $status]);
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (ContactCategory::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . ++$i;
        }
        return $slug;
    }
}
