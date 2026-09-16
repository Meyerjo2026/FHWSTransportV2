<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripRequest;
use App\Support\TransportOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->input('department');
        $date = $request->input('date');
        $shift = $request->input('shift');

        $requests = TripRequest::query()
            ->whereNotNull('clinical_site_id')
            ->whereIn('status', ['approved', 'finalised'])
            ->with('clinicalSite')
            ->when($department, fn ($q) => $q->where('department', $department))
            ->when($date, fn ($q) => $q->where('date', $date))
            ->get()
            ->filter(fn ($r) => $r->clinicalSite && $r->clinicalSite->lat !== null)
            ->when($shift, fn ($c) => $c->filter(fn ($r) => TransportOptions::shiftFor($r->time) === $shift));

        $markers = $requests
            ->groupBy('clinical_site_id')
            ->map(function ($group) {
                $site = $group->first()->clinicalSite;

                return [
                    'name' => $site->name,
                    'address' => $site->address,
                    'lat' => $site->lat,
                    'lng' => $site->lng,
                    'count' => $group->count(),
                    'departments' => $group->pluck('department')->filter()->unique()->values(),
                ];
            })
            ->values();

        return view('admin.map', [
            'user' => Auth::user(),
            'markers' => $markers,
            'departments' => TransportOptions::departments(),
            'filters' => [
                'department' => $department,
                'date' => $date,
                'shift' => $shift,
            ],
            'pickup' => [
                'name' => TransportOptions::PICKUP_POINT,
                'lat' => TransportOptions::PICKUP_LAT,
                'lng' => TransportOptions::PICKUP_LNG,
            ],
            'totalPlacements' => $requests->count(),
            'totalSites' => $markers->count(),
        ]);
    }
}
