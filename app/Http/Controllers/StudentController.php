<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSite;
use App\Models\GroupAssignment;
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
            'yearsByQualification' => array_combine(
                TransportOptions::allQualifications(),
                array_map(fn ($q) => TransportOptions::yearsFor($q), TransportOptions::allQualifications())
            ),
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
            'year' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        if (! TransportOptions::isValidPair($data['department'], $data['qualification'])) {
            return back()->withErrors(['qualification' => 'Please select a qualification that belongs to the chosen department.'])->withInput();
        }

        if (! TransportOptions::isValidYear($data['qualification'], $data['year'])) {
            return back()->withErrors(['year' => 'Please select a year that applies to the chosen qualification.'])->withInput();
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
            'year' => $data['year'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'source' => 'manual',
        ]);

        $responsibleStaff = GroupAssignment::staffFor($data['year'], $data['department'], $data['qualification']);
        $approverNote = $responsibleStaff->isNotEmpty()
            ? 'Awaiting approval from '.$responsibleStaff->pluck('name')->implode(', ').'.'
            : 'Awaiting approval.';

        return redirect('/student')->with('success', "Request submitted: {$site->name} on {$data['date']} ({$data['time']}). {$approverNote}");
    }

    public function mine()
    {
        $assignments = GroupAssignment::whereNotNull('staff_id')->with('staff')->get();

        $list = TripRequest::where('student_id', Auth::id())
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($r) use ($assignments) {
                $r->responsibleStaff = GroupAssignment::resolve($assignments, $r->year, $r->department, $r->qualification);

                return $r;
            });

        return view('student.mine', [
            'user' => Auth::user(),
            'list' => $list,
        ]);
    }
}
