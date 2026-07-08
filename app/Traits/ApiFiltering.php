<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ApiFiltering
{
    /**
     * Apply request-based filters to the Eloquent query builder.
     *
     * @param  Builder  $query
     * @param  array  $allowedFilters  Map of key => operator or column
     * @param  Request|null  $request
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $allowedFilters, ?Request $request = null): Builder
    {
        $request = $request ?: request();
        $filters = $request->input('filter', $request->all());

        if (!is_array($filters)) {
            return $query;
        }

        foreach ($allowedFilters as $filterKey => $definition) {
            if (!isset($filters[$filterKey])) {
                continue;
            }

            $value = $filters[$filterKey];

            if ($value === '' || $value === null) {
                continue;
            }

            if ($definition instanceof \Closure) {
                $definition($query, $value);
                continue;
            }

            $column = $filterKey;
            $operator = '=';

            if (is_array($definition)) {
                $column = $definition['column'] ?? $filterKey;
                $operator = $definition['operator'] ?? '=';
            } elseif (is_string($definition)) {
                if (in_array(strtolower($definition), ['=', 'like', '>', '>=', '<', '<=', 'in', 'between'])) {
                    $operator = $definition;
                } else {
                    $column = $definition;
                }
            }

            switch (strtolower($operator)) {
                case 'like':
                    $query->where($column, 'like', '%' . $value . '%');
                    break;
                case 'in':
                    $values = is_array($value) ? $value : explode(',', $value);
                    $query->whereIn($column, $values);
                    break;
                case 'between':
                    if (is_array($value) && count($value) === 2) {
                        $query->whereBetween($column, $value);
                    } elseif (is_string($value) && str_contains($value, ',')) {
                        $query->whereBetween($column, explode(',', $value));
                    }
                    break;
                default:
                    $query->where($column, $operator, $value);
                    break;
            }
        }

        return $query;
    }
}
