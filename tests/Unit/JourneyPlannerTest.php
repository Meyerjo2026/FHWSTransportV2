<?php

namespace Tests\Unit;

use App\Support\JourneyPlanner;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class JourneyPlannerTest extends TestCase
{
    /**
     * 1 degree of latitude is ~111.19km everywhere on Earth — a
     * standard geodesy reference value, independent of any site data
     * in this app. If haversineKm() drifts from this, the formula
     * itself is broken.
     */
    public function test_haversine_matches_known_one_degree_latitude_distance(): void
    {
        $km = JourneyPlanner::haversineKm(0.0, 0.0, 1.0, 0.0);

        $this->assertEqualsWithDelta(111.19, $km, 0.1);
    }

    public function test_haversine_distance_between_identical_points_is_zero(): void
    {
        $this->assertSame(0.0, JourneyPlanner::haversineKm(-33.9, 18.6, -33.9, 18.6));
    }

    /**
     * Cape Town CBD to Bellville CBD is a well-known ~16km trip —
     * cross-checked against Google Maps' straight-line ("as the crow
     * flies") distance, not the app's own data, as an independent
     * sanity check on the formula.
     */
    public function test_haversine_matches_known_cape_town_landmark_distance(): void
    {
        $capeTownCbd = [-33.9249, 18.4241];
        $bellville = [-33.9000, 18.6292];

        $km = JourneyPlanner::haversineKm(...$capeTownCbd, ...$bellville);

        $this->assertEqualsWithDelta(19.2, $km, 1.5);
    }

    public function test_cluster_groups_nearby_sites_and_separates_far_ones(): void
    {
        // A and B are ~2km apart, B and C are ~2km apart (so A-B-C chain
        // together even though A-C direct is ~4km); D is ~50km away from
        // all of them and must stay in its own cluster.
        $sites = collect([
            (object) ['id' => 1, 'lat' => -33.9000, 'lng' => 18.6000], // A
            (object) ['id' => 2, 'lat' => -33.9180, 'lng' => 18.6000], // B (~2km south of A)
            (object) ['id' => 3, 'lat' => -33.9360, 'lng' => 18.6000], // C (~2km south of B)
            (object) ['id' => 4, 'lat' => -34.3500, 'lng' => 18.6000], // D (~50km south of C)
        ]);

        $clusters = JourneyPlanner::cluster($sites, 10.0);

        $this->assertCount(2, $clusters);
        $sizes = collect($clusters)->map(fn ($c) => count($c))->sort()->values()->all();
        $this->assertSame([1, 3], $sizes);

        $bigCluster = collect($clusters)->first(fn ($c) => count($c) === 3);
        $this->assertEqualsCanonicalizing([1, 2, 3], $bigCluster);

        $smallCluster = collect($clusters)->first(fn ($c) => count($c) === 1);
        $this->assertSame([4], $smallCluster);
    }

    public function test_cluster_keeps_every_site_separate_when_threshold_is_tiny(): void
    {
        $sites = collect([
            (object) ['id' => 1, 'lat' => -33.9000, 'lng' => 18.6000],
            (object) ['id' => 2, 'lat' => -33.9010, 'lng' => 18.6000], // ~110m away
        ]);

        $clusters = JourneyPlanner::cluster($sites, 0.01); // 10m threshold

        $this->assertCount(2, $clusters);
    }

    public function test_cluster_groups_everything_when_threshold_is_huge(): void
    {
        $sites = collect([
            (object) ['id' => 1, 'lat' => -33.9000, 'lng' => 18.6000],
            (object) ['id' => 2, 'lat' => -34.3500, 'lng' => 18.6000],
        ]);

        $clusters = JourneyPlanner::cluster($sites, 1000.0);

        $this->assertCount(1, $clusters);
        $this->assertEqualsCanonicalizing([1, 2], $clusters[0]);
    }

    public function test_max_pairwise_distance_of_a_single_site_is_zero(): void
    {
        $sites = collect([(object) ['id' => 1, 'lat' => -33.9, 'lng' => 18.6]]);

        $this->assertSame(0.0, JourneyPlanner::maxPairwiseDistance($sites));
    }
}
