<?php

namespace App\Support;

use App\Models\TripRequest;
use Illuminate\Support\Collection;

/**
 * Recommends combining separate approved trip requests into a single
 * multi-stop vehicle journey when their clinical sites are close
 * together — e.g. several students going to different clinics in the
 * same area on the same day and shift can share one vehicle instead
 * of dispatching one per site.
 *
 * This is a deterministic geographic-clustering algorithm (haversine
 * distance + connected components), not a call to a hosted AI model —
 * no third-party API key is configured for this app, and a distance
 * threshold is fully explainable/auditable in a way a black-box model
 * call would not be for a transport-cost decision like this.
 */
class JourneyPlanner
{
    public const DEFAULT_THRESHOLD_KM = 10.0;

    /**
     * @return Collection<int, array{
     *   key:string, date:string, time:string,
     *   sites:Collection, items:Collection,
     *   studentCount:int, maxSpanKm:float
     * }>
     */
    public static function suggest(?float $thresholdKm = null): Collection
    {
        $thresholdKm ??= self::DEFAULT_THRESHOLD_KM;

        $requests = TripRequest::with('clinicalSite')
            ->where('status', 'approved')
            ->whereNull('journey_id')
            ->whereNotNull('clinical_site_id')
            ->get()
            ->filter(fn ($r) => $r->clinicalSite && $r->clinicalSite->lat !== null);

        return $requests
            ->groupBy(fn ($r) => $r->date.'|'.$r->time)
            ->flatMap(function (Collection $bucket) use ($thresholdKm) {
                $date = $bucket->first()->date;
                $time = $bucket->first()->time;

                $bySite = $bucket->groupBy('clinical_site_id');
                $sites = $bySite->map(fn ($items) => $items->first()->clinicalSite)->values();

                $clusters = self::cluster($sites, $thresholdKm);

                return collect($clusters)
                    ->filter(fn ($siteIds) => count($siteIds) >= 2)
                    ->map(function ($siteIds) use ($bySite, $date, $time, $thresholdKm) {
                        $items = collect($siteIds)->flatMap(fn ($id) => $bySite->get($id, collect()))->values();
                        $clusterSites = collect($siteIds)->map(fn ($id) => $bySite->get($id)->first()->clinicalSite)->values();

                        return [
                            'key' => md5($date.'|'.$time.'|'.$clusterSites->pluck('id')->sort()->implode(',')),
                            'date' => $date,
                            'time' => $time,
                            'sites' => $clusterSites,
                            'items' => $items,
                            'studentCount' => $items->count(),
                            'maxSpanKm' => self::maxPairwiseDistance($clusterSites),
                        ];
                    })
                    ->values();
            })
            ->sortBy('date')
            ->values();
    }

    /**
     * Connected-components clustering: two sites are linked if they're
     * within $thresholdKm of each other; clusters are the connected
     * groups that results in (so a chain of nearby sites can end up
     * together even if the two ends are further apart than the
     * threshold from each other directly).
     *
     * @return array<int, array<int>> list of clusters, each a list of clinical_site ids
     */
    public static function cluster(Collection $sites, float $thresholdKm): array
    {
        $ids = $sites->pluck('id')->all();
        $byId = $sites->keyBy('id');
        $visited = [];
        $clusters = [];

        foreach ($ids as $start) {
            if (isset($visited[$start])) {
                continue;
            }
            $queue = [$start];
            $visited[$start] = true;
            $component = [];

            while ($queue) {
                $current = array_pop($queue);
                $component[] = $current;

                foreach ($ids as $other) {
                    if (isset($visited[$other]) || $other === $current) {
                        continue;
                    }
                    $km = self::haversineKm(
                        $byId[$current]->lat, $byId[$current]->lng,
                        $byId[$other]->lat, $byId[$other]->lng,
                    );
                    if ($km <= $thresholdKm) {
                        $visited[$other] = true;
                        $queue[] = $other;
                    }
                }
            }

            $clusters[] = $component;
        }

        return $clusters;
    }

    public static function maxPairwiseDistance(Collection $sites): float
    {
        $max = 0.0;
        $list = $sites->values();
        for ($i = 0; $i < $list->count(); $i++) {
            for ($j = $i + 1; $j < $list->count(); $j++) {
                $km = self::haversineKm($list[$i]->lat, $list[$i]->lng, $list[$j]->lat, $list[$j]->lng);
                $max = max($max, $km);
            }
        }

        return round($max, 1);
    }

    public static function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusKm = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }
}
