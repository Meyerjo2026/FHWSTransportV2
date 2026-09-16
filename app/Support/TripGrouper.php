<?php

namespace App\Support;

use Illuminate\Support\Collection;

class TripGrouper
{
    /**
     * Group trip requests by date|site|time, matching the roster/pivot
     * format used for scheduling and invoicing.
     *
     * @return Collection<int, array{date:string,site:string,time:string,items:Collection,allFinal:bool}>
     */
    public static function group(Collection $requests): Collection
    {
        return $requests
            ->groupBy(fn ($r) => $r->date.'|'.$r->site.'|'.$r->time)
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'date' => $first->date,
                    'site' => $first->site,
                    'time' => $first->time,
                    'items' => $items,
                    'allFinal' => $items->every(fn ($r) => $r->status === 'finalised'),
                ];
            })
            ->sortBy('date')
            ->values();
    }
}
