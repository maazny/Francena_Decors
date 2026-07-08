<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ApiSorting
{
    /**
     * Apply request-based sorting to the Eloquent query builder.
     *
     * @param  Builder  $query
     * @param  array  $allowedSorts  List of columns allowed for sorting
     * @param  string|null  $defaultSort  Default sort string (e.g. '-created_at')
     * @param  Request|null  $request
     * @return Builder
     */
    protected function applySorting(Builder $query, array $allowedSorts, ?string $defaultSort = null, ?Request $request = null): Builder
    {
        $request = $request ?: request();
        $sortString = $request->input('sort', $defaultSort);

        if (!$sortString) {
            return $query;
        }

        $sortFields = explode(',', $sortString);

        foreach ($sortFields as $sortField) {
            $sortField = trim($sortField);
            if (empty($sortField)) {
                continue;
            }

            $direction = 'asc';
            if (str_starts_with($sortField, '-')) {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $direction);
            }
        }

        return $query;
    }
}
