<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSite;
use App\Models\Quote;
use App\Models\TripRequest;
use App\Support\TransportOptions;
use App\Support\TripGrouper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function sites()
    {
        return view('admin.sites', [
            'user' => Auth::user(),
            'sites' => ClinicalSite::orderBy('name')->get(),
        ]);
    }

    public function storeSite(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:clinical_sites,name'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        ClinicalSite::create($data);

        return back()->with('success', "Added clinical site: {$data['name']}.");
    }

    public function toggleSite(ClinicalSite $site)
    {
        $site->update(['active' => ! $site->active]);

        return back();
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
