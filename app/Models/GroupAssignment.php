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
}
