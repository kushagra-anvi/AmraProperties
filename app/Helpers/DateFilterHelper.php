<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DateFilterHelper
{
    /**
     * Apply date range filters to a database query.
     */
    public static function apply(
        Builder $query,
        ?string $filter,
        string $column = 'created_at',
        ?string $startDate = null,
        ?string $endDate = null
    ): Builder {
        if (empty($filter)) {
            return $query;
        }

        switch ($filter) {
            case 'today':
                return $query->whereBetween($column, [
                    Carbon::today()->startOfDay(),
                    Carbon::today()->endOfDay()
                ]);

            case 'yesterday':
                return $query->whereBetween($column, [
                    Carbon::yesterday()->startOfDay(),
                    Carbon::yesterday()->endOfDay()
                ]);

            case 'last_7_days':
                return $query->whereBetween($column, [
                    Carbon::today()->subDays(6)->startOfDay(),
                    Carbon::today()->endOfDay()
                ]);

            case 'last_30_days':
                return $query->whereBetween($column, [
                    Carbon::today()->subDays(29)->startOfDay(),
                    Carbon::today()->endOfDay()
                ]);

            case 'custom':
                if ($startDate && $endDate) {
                    return $query->whereBetween($column, [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                } elseif ($startDate) {
                    return $query->where($column, '>=', Carbon::parse($startDate)->startOfDay());
                } elseif ($endDate) {
                    return $query->where($column, '<=', Carbon::parse($endDate)->endOfDay());
                }
                break;
        }

        return $query;
    }

    /**
     * Get start and end date labels or carbon instances for displaying filters in views.
     */
    public static function getActiveRangeLabel(?string $filter, ?string $startDate = null, ?string $endDate = null): string
    {
        switch ($filter) {
            case 'today':
                return 'Today (' . Carbon::today()->format('M d, Y') . ')';
            case 'yesterday':
                return 'Yesterday (' . Carbon::yesterday()->format('M d, Y') . ')';
            case 'last_7_days':
                return 'Last 7 Days (' . Carbon::today()->subDays(6)->format('M d') . ' - ' . Carbon::today()->format('M d, Y') . ')';
            case 'last_30_days':
                return 'Last 30 Days (' . Carbon::today()->subDays(29)->format('M d') . ' - ' . Carbon::today()->format('M d, Y') . ')';
            case 'custom':
                if ($startDate && $endDate) {
                    return Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y');
                } elseif ($startDate) {
                    return 'From ' . Carbon::parse($startDate)->format('M d, Y');
                } elseif ($endDate) {
                    return 'Until ' . Carbon::parse($endDate)->format('M d, Y');
                }
                return 'Custom Range';
            default:
                return 'All Time';
        }
    }
}
