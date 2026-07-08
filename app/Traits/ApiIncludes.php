<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ApiIncludes
{
    /**
     * Eager load requested relationships on the query builder.
     */
    protected function applyIncludes(Builder $query, array $allowedIncludes, ?Request $request = null): Builder
    {
        $request = $request ?: request();
        $includesString = $request->input('include', '');

        if (empty($includesString)) {
            return $query;
        }

        $includes = explode(',', $includesString);
        $relationsToLoad = [];

        foreach ($includes as $include) {
            $include = trim($include);
            if (in_array($include, $allowedIncludes)) {
                $relationsToLoad[] = $include;
            }
        }

        if (!empty($relationsToLoad)) {
            $query->with($relationsToLoad);
        }

        return $query;
    }
}
