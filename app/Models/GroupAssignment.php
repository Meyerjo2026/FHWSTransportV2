<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupAssignment extends Model
{
    /**
     * type is one of 'year', 'department', 'qualification' — value holds
     * the corresponding option (e.g. 'Year 2', 'Emergency Medical
     * Sciences', 'Diploma in Emergency Care'). One staff member can be
     * responsible for a given value per type.
     */
    protected $fillable = [
        'type',
        'value',
        'staff_id',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Every staff member who would see a request with this year/
     * department/qualification on their approve page — i.e. anyone
     * assigned to any one of the three (matches the OR logic in
     * StaffController::approve()). Used to tell a student who their
     * request has effectively been routed to.
     */
    public static function staffFor(?string $year, ?string $department, ?string $qualification)
    {
        return self::query()
            ->whereNotNull('staff_id')
            ->where(function ($q) use ($year, $department, $qualification) {
                $q->where(fn ($q2) => $q2->where('type', 'year')->where('value', $year))
                    ->orWhere(fn ($q2) => $q2->where('type', 'department')->where('value', $department))
                    ->orWhere(fn ($q2) => $q2->where('type', 'qualification')->where('value', $qualification));
            })
            ->with('staff')
            ->get()
            ->pluck('staff')
            ->filter()
            ->unique('id')
            ->values();
    }
}
