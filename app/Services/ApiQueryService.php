<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

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
                        // Check if the field is fully qualified (e.g., "table.field")
                        if (strpos($field, '.') !== false) {
                            $rawField = "LOWER($field)";
                        } else {
                            $rawField = "LOWER(\"$field\")";
                        }
                        $queryCondition = strtolower($queryCondition);
                    } else {
                        // Case-sensitive or regex search: Use field as-is
                        if (strpos($field, '.') !== false) {
                            $rawField = $field;
                        } else {
                            $rawField = "\"$field\"";
                        }
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
    public function applyFilters(Builder $query, array $filters = [], string $targetTable): Builder
    {
        // Early exit if no filters are provided
        if (empty($filters)) {
            return $query;
        }
    
        foreach ($filters as $field => $value) {
            // Validate that the field exists in the table to prevent SQL injection
            if (!Schema::hasColumn($targetTable, $field)) {
                throw new InvalidArgumentException("Invalid filter field: {$field}");
            }
    
            // Ensure the field is prefixed with the target table to avoid ambiguity
            $prefixedField = "{$targetTable}.{$field}";

            //Log::channel('debug')->info('Prefixed Field:', $prefixedField);
            // ray($prefixedField);
    
            // Handle range filters if the value is an array with exactly two numeric values
            if (is_array($value) && count($value) === 2 && is_numeric($value[0]) && is_numeric($value[1])) {
                $query->whereBetween($prefixedField, $value);
                continue;
            }
    
            // Handle simple equality filters for scalar values
            if (is_scalar($value)) {
                $query->where($prefixedField, $value);
                continue;
            }
    
            // Throw an exception for unsupported or invalid filter types
            throw new InvalidArgumentException("Unsupported or invalid filter type for field: {$field}");
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


    /**
     * 
     */
    public function applyExcludeIds(Builder $query, array $excludeIds, string $primaryKey): Builder
    {
        if (!empty($excludeIds)) {
            $query->whereNotIn($primaryKey, $excludeIds);
        }
        return $query;
    }


}
