<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearGroupAssignment extends Model
{
    protected $fillable = [
        'year',
        'staff_id',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
