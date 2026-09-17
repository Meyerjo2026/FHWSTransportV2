<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSite;
use App\Models\Quote;
use App\Models\TripRequest;
use App\Models\User;
use App\Models\YearGroupAssignment;
use App\Support\TransportOptions;
use App\Support\TripGrouper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function yearGroups()
    {
        $assignments = collect(TransportOptions::YEAR_OPTIONS)->mapWithKeys(function ($year) {
            return [$year => YearGroupAssignment::with('staff')->firstWhere('year', $year)];
        });

        return view('admin.year-groups', [
            'user' => Auth::user(),
            'years' => TransportOptions::YEAR_OPTIONS,
            'assignments' => $assignments,
            'staffMembers' => User::where('role', 'staff')->orderBy('name')->get(),
        ]);
    }

    public function updateYearGroup(Request $request, string $year)
    {
        abort_unless(in_array($year, TransportOptions::YEAR_OPTIONS, true), 404);

        $data = $request->validate([
            'staff_id' => ['nullable', 'exists:users,id'],
        ]);

        YearGroupAssignment::updateOrCreate(['year' => $year], ['staff_id' => $data['staff_id'] ?: null]);

        return back()->with('success', "Updated staff assignment for {$year}.");
    }

    public function sites(Request $request)
    {
        $type = $request->query('type');

        $sites = ClinicalSite::orderBy('name')
            ->when($type, fn ($q) => $q->where('type', $type))
            ->get();

        // Sites sharing the exact same coordinates are usually a sign the
        // location was approximated at suburb level (see
        // TransportOptions::SITE_SEED) rather than geocoded individually —
        // flagged here so an admin knows which pins most need a manual
        // Google Maps check.
        $duplicateCoordKeys = $sites
            ->filter(fn ($s) => $s->lat !== null)
            ->groupBy(fn ($s) => round($s->lat, 5).','.round($s->lng, 5))
            ->filter(fn ($group) => $group->count() > 1)
            ->keys();

        // Types actually in use, plus the starter list — lets the filter
        // and datalist include custom types an admin has typed in, not
        // just the ones TransportOptions ships with.
        $typeOptions = ClinicalSite::whereNotNull('type')
            ->distinct()
            ->pluck('type')
            ->merge(TransportOptions::TYPE_OPTIONS)
            ->unique()
            ->sort()
            ->values();

        return view('admin.sites', [
            'user' => Auth::user(),
            'sites' => $sites,
            'duplicateCoordKeys' => $duplicateCoordKeys,
            'typeOptions' => $typeOptions,
            'selectedType' => $type,
        ]);
    }

    public function storeSite(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:clinical_sites,name'],
            'address' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
        ]);

        ClinicalSite::create($data);

        return back()->with('success', "Added clinical site: {$data['name']}.");
    }

    public function toggleSite(ClinicalSite $site)
    {
        $site->update(['active' => ! $site->active]);

        return back();
    }

    public function updateSite(Request $request, ClinicalSite $site)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:clinical_sites,name,'.$site->id],
            'address' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $site->update($data);

        return back()->with('success', "Updated clinical site: {$data['name']}.");
    }

    public function bulkUploadSites(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $rows = array_map('str_getcsv', file($request->file('file')->getRealPath()));
        $header = array_map(fn ($h) => strtolower(trim($h)), array_shift($rows) ?? []);

        $created = 0;
        $updated = 0;
        foreach ($rows as $row) {
            if (! $row || count($row) < count($header)) {
                continue;
            }
            $assoc = array_combine($header, array_map('trim', $row));
            if (empty($assoc['name'])) {
                continue;
            }

            $site = ClinicalSite::updateOrCreate(
                ['name' => $assoc['name']],
                [
                    'address' => ($assoc['address'] ?? '') ?: null,
                    'type' => ($assoc['type'] ?? '') ?: null,
                    'lat' => is_numeric($assoc['lat'] ?? null) ? (float) $assoc['lat'] : null,
                    'lng' => is_numeric($assoc['lng'] ?? null) ? (float) $assoc['lng'] : null,
                ]
            );

            $site->wasRecentlyCreated ? $created++ : $updated++;
        }

        return back()->with('success', "Uploaded clinical sites: {$created} added, {$updated} updated.");
    }

    public function consolidate()
    {
        $approved = TripRequest::whereIn('status', ['approved', 'finalised'])->get();
        $groups = TripGrouper::group($approved);

        return view('admin.consolidate', [
            'user' => Auth::user(),
            'groups' => $groups,
        ]);
    }

    public function review()
    {
        $pending = TripRequest::where('status', 'pending')->orderBy('date')->get();
        $approved = TripRequest::where('status', 'approved')->orderBy('date')->get();

        return view('admin.review', [
            'user' => Auth::user(),
            'pending' => $pending,
            'approved' => $approved,
        ]);
    }

    public function finaliseForm()
    {
        $approved = TripRequest::whereIn('status', ['approved', 'finalised'])->get();
        $groups = TripGrouper::group($approved)->filter(fn ($g) => ! $g['allFinal'])->values();

        return view('admin.finalise', [
            'user' => Auth::user(),
            'groups' => $groups,
        ]);
    }

    public function finalise(Request $request)
    {
        $ids = collect($request->input('ids', []))
            ->flatMap(fn ($csv) => explode(',', $csv))
            ->filter()
            ->unique();

        TripRequest::whereIn('id', $ids)->update(['status' => 'finalised']);

        return redirect('/admin/finalise');
    }

    public function quotesIndex()
    {
        $finalisedUnquoted = TripRequest::where('status', 'finalised')->whereNull('quote_id')->get();
        $groups = TripGrouper::group($finalisedUnquoted);
        $quotes = Quote::orderByDesc('created_at')->get();

        return view('admin.quotes', [
            'user' => Auth::user(),
            'groups' => $groups,
            'quotes' => $quotes,
            'defaultRate' => TransportOptions::DEFAULT_RATE,
        ]);
    }

    public function generateQuote(Request $request)
    {
        $data = $request->validate([
            'period' => ['required', 'string'],
            'rate' => ['required', 'numeric'],
        ]);

        $finalised = TripRequest::where('status', 'finalised')->whereNull('quote_id')->get();
        if ($finalised->isEmpty()) {
            return back()->with('error', 'No finalised trips awaiting a quote.');
        }

        $groups = TripGrouper::group($finalised);
        $total = $groups->sum(fn ($g) => $data['rate'] * $g['items']->count());

        $quote = Quote::create([
            'ref' => 'RFQ'.(1000 + Quote::count() + 1),
            'period' => $data['period'],
            'rate' => $data['rate'],
            'total' => $total,
            'created_by' => Auth::user()->name,
        ]);

        TripRequest::whereIn('id', $finalised->pluck('id'))->update(['quote_id' => $quote->id]);

        return redirect("/admin/quotes/{$quote->id}");
    }

    public function quoteShow(Quote $quote)
    {
        $items = TripRequest::where('quote_id', $quote->id)->get();
        $groups = TripGrouper::group($items);

        return view('admin.quote-show', [
            'user' => Auth::user(),
            'quote' => $quote,
            'groups' => $groups,
        ]);
    }
}
