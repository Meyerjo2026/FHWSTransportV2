<?php

namespace App\Support;

use Illuminate\Support\Collection;

class TripGrouper
{
    /**
     * Group trip requests by date|site|time, matching the roster/pivot
     * format used for scheduling and invoicing. Groups larger than
     * $maxPerTrip are split into multiple trips (one vehicle can only
     * carry so many students), each tagged with a tripPart/tripParts
     * pair so the UI can show "Trip 1 of 2" etc.
     *
     * @return Collection<int, array{date:string,site:string,time:string,items:Collection,allFinal:bool,tripPart:int,tripParts:int}>
     */
    public static function group(Collection $requests, ?int $maxPerTrip = null): Collection
    {
        $maxPerTrip ??= TransportOptions::MAX_STUDENTS_PER_TRIP;

        return $requests
            ->groupBy(fn ($r) => $r->date.'|'.$r->site.'|'.$r->time)
            ->flatMap(function (Collection $items) use ($maxPerTrip) {
                $first = $items->first();
                $chunks = $items->values()->chunk($maxPerTrip)->values();
                $total = $chunks->count();

                return $chunks->map(function (Collection $chunkItems, int $i) use ($first, $total) {
                    return [
                        'date' => $first->date,
                        'site' => $first->site,
                        'time' => $first->time,
                        'items' => $chunkItems->values(),
                        'allFinal' => $chunkItems->every(fn ($r) => $r->status === 'finalised'),
                        'tripPart' => $i + 1,
                        'tripParts' => $total,
                    ];
                });
            })
            ->sortBy('date')
            ->values();
    }
}
