<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSite;
use App\Models\TripRequest;
use App\Support\TransportOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function create()
    {
        return view('student.request', [
            'user' => Auth::user(),
            'sites' => ClinicalSite::where('active', true)->orderBy('name')->get(),
            'timeSlots' => TransportOptions::TIME_SLOTS,
            'departments' => TransportOptions::departments(),
            'qualificationsByDepartment' => TransportOptions::QUALIFICATIONS_BY_DEPARTMENT,
            'pickupPoint' => TransportOptions::PICKUP_POINT,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinical_site_id' => ['required', 'exists:clinical_sites,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'string'],
            'department' => ['required', 'string', 'in:'.implode(',', TransportOptions::departments())],
            'qualification' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        if (! TransportOptions::isValidPair($data['department'], $data['qualification'])) {
            return back()->withErrors(['qualification' => 'Please select a qualification that belongs to the chosen department.'])->withInput();
        }

        $user = Auth::user();
        $site = ClinicalSite::findOrFail($data['clinical_site_id']);

        TripRequest::create([
            'student_id' => $user->id,
            'student_name' => $user->name,
            'student_email' => $user->email,
            'student_number' => $user->number,
            'clinical_site_id' => $site->id,
            'site' => $site->name,
            'date' => $data['date'],
            'time' => $data['time'],
            'department' => $data['department'],
            'qualification' => $data['qualification'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'source' => 'manual',
        ]);

        return redirect('/student')->with('success', "Request submitted: {$site->name} on {$data['date']} ({$data['time']}). Awaiting approval.");
    }

    public function mine()
    {
        $list = TripRequest::where('student_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('student.mine', [
            'user' => Auth::user(),
            'list' => $list,
        ]);
    }
}
