<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSite;
use App\Models\TripRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function approve()
    {
        $pending = TripRequest::where('status', 'pending')->orderBy('date')->get();
        $recent = TripRequest::where('status', '!=', 'pending')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return view('staff.approve', [
            'user' => Auth::user(),
            'pending' => $pending,
            'recent' => $recent,
        ]);
    }

    public function setStatus(Request $request, TripRequest $tripRequest)
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $tripRequest->update(['status' => $data['status']]);

        return back();
    }

    public function bulkForm()
    {
        return view('staff.bulk', ['user' => Auth::user()]);
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $rows = array_map('str_getcsv', file($request->file('file')->getRealPath()));
        $header = array_map(fn ($h) => strtolower(trim($h)), array_shift($rows) ?? []);

        $sitesByName = ClinicalSite::pluck('id', 'name');

        $count = 0;
        foreach ($rows as $row) {
            if (! $row || count($row) < count($header)) {
                continue;
            }
            $assoc = array_combine($header, array_map('trim', $row));
            if (empty($assoc['site']) || empty($assoc['date']) || empty($assoc['time'])) {
                continue;
            }

            TripRequest::create([
                'student_id' => null,
                'student_name' => $assoc['name'] ?? 'Unknown',
                'student_email' => $assoc['email'] ?? '',
                'student_number' => $assoc['number'] ?? '',
                'clinical_site_id' => $sitesByName[$assoc['site']] ?? null,
                'site' => $assoc['site'],
                'date' => $assoc['date'],
                'time' => $assoc['time'],
                'department' => $assoc['department'] ?? null,
                'qualification' => $assoc['qualification'] ?? null,
                'notes' => $assoc['notes'] ?? null,
                'status' => 'approved',
                'source' => 'bulk',
                'uploaded_by' => Auth::user()->name,
            ]);
            $count++;
        }

        return back()->with('success', "Uploaded and approved {$count} trip(s).");
    }
}
