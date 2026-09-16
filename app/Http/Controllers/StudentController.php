<?php

namespace App\Http\Controllers;

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
            'sites' => TransportOptions::SITES,
            'timeSlots' => TransportOptions::TIME_SLOTS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'site' => ['required', 'string'],
            'date' => ['required', 'date'],
            'time' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = Auth::user();

        TripRequest::create([
            'student_id' => $user->id,
            'student_name' => $user->name,
            'student_email' => $user->email,
            'student_number' => $user->number,
            'site' => $data['site'],
            'date' => $data['date'],
            'time' => $data['time'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'source' => 'manual',
        ]);

        return redirect('/student')->with('success', "Request submitted: {$data['site']} on {$data['date']} ({$data['time']}). Awaiting approval.");
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
