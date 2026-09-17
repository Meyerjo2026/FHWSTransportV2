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
     *
     * Runs one query per call — fine for a single request (e.g. right
     * after submitting one), but callers resolving this for a *list*
     * of requests (e.g. the "My Requests" table) should fetch
     * all assignments once via all() and call resolve() per row
     * instead, to avoid an N+1 query per row.
     */
    public static function staffFor(?string $year, ?string $department, ?string $qualification)
    {
        return self::resolve(self::query()->whereNotNull('staff_id')->with('staff')->get(), $year, $department, $qualification);
    }

    /**
     * In-memory equivalent of staffFor(), given a pre-fetched
     * collection of assignments (with 'staff' eager-loaded).
     */
    public static function resolve($assignments, ?string $year, ?string $department, ?string $qualification)
    {
        return $assignments
            ->filter(fn ($a) => ($a->type === 'year' && $a->value === $year)
                || ($a->type === 'department' && $a->value === $department)
                || ($a->type === 'qualification' && $a->value === $qualification))
            ->pluck('staff')
            ->filter()
            ->unique('id')
            ->values();
    }
}
