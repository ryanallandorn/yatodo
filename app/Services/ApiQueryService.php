<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiQueryService
{


    /**
     * 
     */
    public function applySearch(
        Builder $query,
        ?string $searchQuery,
        array|string $searchFields,
        string $searchOperator = 'AND',
        bool $searchMatchCase = false,
        bool $searchRegex = false
    ): Builder {
        if (is_string($searchFields)) {
            $searchFields = explode(',', $searchFields);
        }
    
        if ($searchQuery && !empty($searchFields)) {
            $query->where(function ($queryBuilder) use (
                $searchFields, $searchQuery, $searchOperator, $searchMatchCase, $searchRegex
            ) {
                foreach ($searchFields as $index => $field) {
                    $operator = $searchRegex ? ($searchMatchCase ? '~' : '~*') : 'LIKE';
                    $queryCondition = $searchRegex ? $searchQuery : "%{$searchQuery}%";
    
                    if (!$searchMatchCase && !$searchRegex) {
                        // Case-insensitive non-regex search: Use LOWER function
                        $rawField = "LOWER(\"$field\")";
                        $queryCondition = strtolower($queryCondition);
                    } else {
                        // Case-sensitive or regex search: Use field as-is
                        $rawField = "\"$field\"";
                    }
    
                    $queryMethod = $index === 0 || strtoupper($searchOperator) === 'AND'
                        ? 'whereRaw'
                        : 'orWhereRaw';
    
                    $queryBuilder->{$queryMethod}("$rawField $operator ?", [$queryCondition]);
                }
            });
        }
    
        return $query;
    }
    


    /**
     * 
     */
    public function applyFilters(Builder $query, array $filters = []): Builder
    {
        foreach ($filters as $field => $value) {
            is_array($value)
                ? $query->whereBetween($field, $value)
                : $query->where($field, $value);
        }
        return $query;
    }


    /**
     * 
     */
    public function applyOrder(Builder $query, string $orderBy = 'id', string $orderDirection = 'asc'): Builder
    {
        return $query->orderBy($orderBy, $orderDirection);
    }


    /**
     * 
     */
    public function applyPagination(Builder $query, int $limit = 10, int $page = 1): Builder
    {
        $offset = ($page - 1) * $limit;
        return $query->limit($limit)->offset($offset);
    }


    /**
     * 
     */
    public function applyCursorPagination(Builder $query, ?int $lastId = null, int $limit = 10): Builder
    {
        if ($lastId) {
            $query->where('id', '>', $lastId);
        }
        return $query->limit($limit);
    }


    /**
     * 
     */
    public function extractParameters(array $params): array
    {
        return [
            'searchQuery' => $params['searchQuery'] ?? null,
            'searchFields' => $params['searchFields'] ?? [],
            'searchOperator' => $params['searchOperator'] ?? 'AND',
            'filters' => $params['filters'] ?? [],
            'filterExcludeIds' => (array)($params['filterExcludeIds'] ?? []),
            'orderBy' => $params['orderBy'] ?? 'id',
            'orderDirection' => $params['orderDirection'] ?? 'asc',
            'limit' => $params['limit'] ?? 10,
            'lastId' => $params['lastId'] ?? null,
        ];
    }


    /**
     * 
     */
    public function getFilteredResults(Builder $query, array $params = []): array
    {
        $params = $this->extractParameters($params);

        $query = $this->applySearch($query, $params['searchQuery'], $params['searchFields'], $params['searchOperator']);
        $query = $this->applyFilters($query, $params['filters']);

        if (!empty($params['filterExcludeIds'])) {
            $query->whereNotIn('id', $params['filterExcludeIds']);
        }

        $query = $this->applyOrder($query, $params['orderBy'], $params['orderDirection']);

        $query = $this->applyCursorPagination($query, $params['lastId'], $params['limit']);

        $items = $query->get();
        $nextCursor = $items->last()?->id;

        return [
            'items' => $items,
            'nextCursor' => $nextCursor,
            'limit' => $params['limit'],
        ];
    }
}
