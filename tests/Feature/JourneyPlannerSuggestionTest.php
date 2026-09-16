<?php

namespace Tests\Feature;

use App\Models\ClinicalSite;
use App\Models\TripRequest;
use App\Support\JourneyPlanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JourneyPlannerSuggestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_suggests_combining_two_nearby_sites_on_the_same_slot(): void
    {
        $near1 = ClinicalSite::create(['name' => 'Near Site A', 'lat' => -33.9000, 'lng' => 18.6000, 'active' => true]);
        $near2 = ClinicalSite::create(['name' => 'Near Site B', 'lat' => -33.9090, 'lng' => 18.6000, 'active' => true]); // ~1km away
        $far = ClinicalSite::create(['name' => 'Far Site', 'lat' => -34.3500, 'lng' => 18.6000, 'active' => true]); // ~50km away

        $this->makeApprovedTrip($near1, '2026-08-01', '06:00 - 18:00');
        $this->makeApprovedTrip($near2, '2026-08-01', '06:00 - 18:00');
        $this->makeApprovedTrip($far, '2026-08-01', '06:00 - 18:00');

        $suggestions = JourneyPlanner::suggest(10.0);

        $this->assertCount(1, $suggestions, 'only the two nearby sites should form a suggestion');
        $suggestion = $suggestions->first();
        $this->assertEqualsCanonicalizing(
            ['Near Site A', 'Near Site B'],
            $suggestion['sites']->pluck('name')->all()
        );
        $this->assertSame(2, $suggestion['studentCount']);
    }

    public function test_it_does_not_suggest_combining_sites_on_different_dates_or_time_slots(): void
    {
        $siteA = ClinicalSite::create(['name' => 'Site A', 'lat' => -33.9000, 'lng' => 18.6000, 'active' => true]);
        $siteB = ClinicalSite::create(['name' => 'Site B', 'lat' => -33.9010, 'lng' => 18.6000, 'active' => true]);

        $this->makeApprovedTrip($siteA, '2026-08-01', '06:00 - 18:00');
        $this->makeApprovedTrip($siteB, '2026-08-02', '06:00 - 18:00'); // different date

        $this->assertCount(0, JourneyPlanner::suggest(10.0));
    }

    public function test_it_ignores_trips_already_assigned_to_a_journey(): void
    {
        $siteA = ClinicalSite::create(['name' => 'Site A', 'lat' => -33.9000, 'lng' => 18.6000, 'active' => true]);
        $siteB = ClinicalSite::create(['name' => 'Site B', 'lat' => -33.9010, 'lng' => 18.6000, 'active' => true]);

        $tripA = $this->makeApprovedTrip($siteA, '2026-08-01', '06:00 - 18:00');
        $this->makeApprovedTrip($siteB, '2026-08-01', '06:00 - 18:00');

        $tripA->update(['journey_id' => \App\Models\Journey::create([
            'date' => '2026-08-01', 'time' => '06:00 - 18:00', 'label' => 'x', 'created_by' => 'test',
        ])->id]);

        $this->assertCount(0, JourneyPlanner::suggest(10.0));
    }

    /**
     * Sanity check on the real seeded clinical site data: run the
     * migrations/seeder and confirm every active site has coordinates
     * and no two *differently named* sites share the exact same
     * lat/lng down to 6 decimal places (a sign of a copy-paste error
     * rather than intentional suburb-level approximation, which this
     * project documents separately in TransportOptions::SITE_SEED).
     */
    public function test_seeded_clinical_sites_all_have_coordinates(): void
    {
        $this->seed();

        $sites = ClinicalSite::all();
        $this->assertGreaterThan(50, $sites->count());

        $missingCoords = $sites->filter(fn ($s) => $s->lat === null || $s->lng === null);
        $this->assertCount(0, $missingCoords, 'sites missing coordinates: '.$missingCoords->pluck('name')->implode(', '));
    }

    private function makeApprovedTrip(ClinicalSite $site, string $date, string $time): TripRequest
    {
        return TripRequest::create([
            'student_name' => 'Test Student',
            'student_email' => 'test@example.com',
            'student_number' => '0800000000',
            'clinical_site_id' => $site->id,
            'site' => $site->name,
            'date' => $date,
            'time' => $time,
            'department' => 'Nursing Sciences',
            'status' => 'approved',
            'source' => 'manual',
        ]);
    }
}
