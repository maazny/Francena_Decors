<?php

namespace App\Services;

use App\Models\ContactCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ContactCategoryService
{
    public static function clearCache(): void
    {
        Cache::forget('contact.active_categories');
    }

    public function create(array $data): ContactCategory
    {
        $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['name']);
        $category = ContactCategory::create($data);
        self::clearCache();
        return $category;
    }

    public function update(ContactCategory $category, array $data): ContactCategory
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
        }
        $category->update($data);
        self::clearCache();
        return $category;
    }

    public function toggleStatus(ContactCategory $category): ContactCategory
    {
        $category->update([
            'status' => ! $category->status,
        ]);
        self::clearCache();
        return $category;
    }

    public function restore(int $id): bool
    {
        $category = ContactCategory::onlyTrashed()->findOrFail($id);
        $res = $category->restore();
        self::clearCache();
        return $res;
    }

    public function bulkDelete(array $ids): int
    {
        $res = ContactCategory::whereIn('id', $ids)->delete();
        self::clearCache();
        return $res;
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        $res = ContactCategory::whereIn('id', $ids)->update(['status' => $status]);
        self::clearCache();
        return $res;
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
