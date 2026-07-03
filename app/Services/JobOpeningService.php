<?php

namespace App\Services;

use App\Models\JobOpening;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class JobOpeningService
{
    public function create(array $data): JobOpening
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['title']);
            $opening = JobOpening::create($data);
            $this->syncRelations($opening, $data);
            return $opening;
        });
    }

    public function update(JobOpening $opening, array $data): JobOpening
    {
        return DB::transaction(function () use ($opening, $data) {
            if (isset($data['title']) && (!isset($data['slug']) || empty($data['slug']))) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], $opening->id);
            }
            $opening->update($data);
            $this->syncRelations($opening, $data);
            return $opening;
        });
    }

    public function duplicate(JobOpening $opening): JobOpening
    {
        return DB::transaction(function () use ($opening) {
            $newOpening = $opening->replicate();
            $newOpening->title = $opening->title . ' (Copy)';
            $newOpening->slug = $this->generateUniqueSlug($newOpening->title);
            $newOpening->reference_no = $opening->reference_no ? $opening->reference_no . '-COPY' : null;
            $newOpening->status = false; // default to draft/inactive
            $newOpening->push();

            // Replicate skills, benefits, qualifications
            foreach ($opening->skills as $skill) {
                $newOpening->skills()->create([
                    'skill_name' => $skill->skill_name,
                    'display_order' => $skill->display_order,
                ]);
            }
            foreach ($opening->benefits as $benefit) {
                $newOpening->benefits()->create([
                    'benefit_name' => $benefit->benefit_name,
                    'display_order' => $benefit->display_order,
                ]);
            }
            foreach ($opening->qualifications as $qualification) {
                $newOpening->qualifications()->create([
                    'qualification_name' => $qualification->qualification_name,
                    'display_order' => $qualification->display_order,
                ]);
            }

            return $newOpening;
        });
    }

    public function toggleStatus(JobOpening $opening): JobOpening
    {
        $opening->update([
            'status' => ! $opening->status,
        ]);
        return $opening;
    }

    public function toggleFeatured(JobOpening $opening): JobOpening
    {
        $opening->update([
            'featured' => ! $opening->featured,
        ]);
        return $opening;
    }

    public function toggleHomepageFeatured(JobOpening $opening): JobOpening
    {
        $opening->update([
            'homepage_featured' => ! $opening->homepage_featured,
        ]);
        return $opening;
    }

    public function restore(int $id): bool
    {
        $opening = JobOpening::onlyTrashed()->findOrFail($id);
        return $opening->restore();
    }

    public function bulkDelete(array $ids): int
    {
        return JobOpening::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, bool $status): int
    {
        return JobOpening::whereIn('id', $ids)->update(['status' => $status]);
    }

    protected function syncRelations(JobOpening $opening, array $data): void
    {
        if (isset($data['skills'])) {
            $opening->skills()->delete();
            foreach ($data['skills'] as $index => $skill) {
                if (!empty($skill)) {
                    $opening->skills()->create([
                        'skill_name' => $skill,
                        'display_order' => $index,
                    ]);
                }
            }
        }

        if (isset($data['benefits'])) {
            $opening->benefits()->delete();
            foreach ($data['benefits'] as $index => $benefit) {
                if (!empty($benefit)) {
                    $opening->benefits()->create([
                        'benefit_name' => $benefit,
                        'display_order' => $index,
                    ]);
                }
            }
        }

        if (isset($data['qualifications'])) {
            $opening->qualifications()->delete();
            foreach ($data['qualifications'] as $index => $qualification) {
                if (!empty($qualification)) {
                    $opening->qualifications()->create([
                        'qualification_name' => $qualification,
                        'display_order' => $index,
                    ]);
                }
            }
        }
    }

    public function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (JobOpening::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . ++$i;
        }
        return $slug;
    }
}
