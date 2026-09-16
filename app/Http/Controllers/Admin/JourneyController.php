<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\TripRequest;
use App\Support\JourneyPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JourneyController extends Controller
{
    public function index(Request $request)
    {
        $threshold = (float) $request->input('threshold', JourneyPlanner::DEFAULT_THRESHOLD_KM);
        $threshold = max(0.5, min($threshold, 100));

        $suggestions = JourneyPlanner::suggest($threshold);

        $active = Journey::with('tripRequests.clinicalSite')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.journeys', [
            'user' => Auth::user(),
            'suggestions' => $suggestions,
            'active' => $active,
            'threshold' => $threshold,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'trip_request_ids' => ['required', 'string'],
            'date' => ['required', 'string'],
            'time' => ['required', 'string'],
            'label' => ['required', 'string'],
            'threshold' => ['nullable', 'numeric'],
        ]);

        $ids = collect(explode(',', $data['trip_request_ids']))->filter()->unique();
        $requests = TripRequest::whereIn('id', $ids)
            ->where('status', 'approved')
            ->whereNull('journey_id')
            ->get();

        if ($requests->isEmpty()) {
            return back()->with('error', 'Those trips are no longer available to combine (already actioned elsewhere).');
        }

        DB::transaction(function () use ($data, $requests) {
            $journey = Journey::create([
                'date' => $data['date'],
                'time' => $data['time'],
                'label' => $data['label'],
                'threshold_km' => $data['threshold'] ?? null,
                'created_by' => Auth::user()->name,
            ]);

            TripRequest::whereIn('id', $requests->pluck('id'))->update(['journey_id' => $journey->id]);
        });

        return back()->with('success', "Combined {$requests->count()} trips into one journey: {$data['label']}.");
    }

    public function destroy(Journey $journey)
    {
        if ($journey->tripRequests()->where('status', 'finalised')->exists()) {
            return back()->with('error', 'This journey has finalised trips and can no longer be split apart.');
        }

        TripRequest::where('journey_id', $journey->id)->update(['journey_id' => null]);
        $journey->delete();

        return back()->with('success', 'Journey cancelled — trips are separate again.');
    }
}
