<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;

trait ApiPagination
{
    /**
     * Format pagination metadata from a paginator instance.
     *
     * @param  mixed  $paginator
     * @return array
     */
    protected function getPaginationMeta($paginator): array
    {
        if ($paginator instanceof LengthAwarePaginator) {
            return [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more_pages' => $paginator->hasMorePages(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ];
        }

        if ($paginator instanceof CursorPaginator) {
            return [
                'cursor' => [
                    'current' => $paginator->cursor() ? $paginator->cursor()->encode() : null,
                    'next' => $paginator->nextCursor() ? $paginator->nextCursor()->encode() : null,
                    'prev' => $paginator->prevCursor() ? $paginator->prevCursor()->encode() : null,
                ],
                'per_page' => $paginator->perPage(),
                'has_more_pages' => $paginator->hasMorePages(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ];
        }

        if ($paginator instanceof Paginator) {
            return [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'has_more_pages' => $paginator->hasMorePages(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ];
        }

        return [];
    }

    /**
     * Respond with a paginated resource or dataset.
     *
     * @param  mixed  $paginator
     * @param  mixed  $resourceClass  Optional ApiResource class to transform the items
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function paginatedResponse($paginator, $resourceClass = null, string $message = 'Resource list retrieved successfully')
    {
        $items = $paginator->items();

        if ($resourceClass) {
            $items = $resourceClass::collection($items);
        }

        return $this->success(
            $items,
            $message,
            200,
            $this->getPaginationMeta($paginator)
        );
    }
}
