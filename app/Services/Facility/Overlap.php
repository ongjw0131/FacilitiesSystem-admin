<?php

namespace App\Services\Facility;

use Carbon\Carbon;

class Overlap
{
    /**
     * Determine if two time ranges overlap.
     */
    public static function rangesOverlap(Carbon|string $startA, Carbon|string $endA, Carbon|string $startB, Carbon|string $endB): bool
    {
        $startA = self::toCarbon($startA);
        $endA = self::toCarbon($endA);
        $startB = self::toCarbon($startB);
        $endB = self::toCarbon($endB);

        return $startA < $endB && $endA > $startB;
    }

    /**
     * Ensure a single range is valid.
     */
    public static function startsBeforeEnds(Carbon|string $start, Carbon|string $end): bool
    {
        $start = self::toCarbon($start);
        $end = self::toCarbon($end);

        return $start->lt($end);
    }

    private static function toCarbon(Carbon|string $value): Carbon
    {
        return $value instanceof Carbon
            ? $value->copy()->timezone(config('app.timezone'))
            : Carbon::parse($value)->timezone(config('app.timezone'));
    }
}
