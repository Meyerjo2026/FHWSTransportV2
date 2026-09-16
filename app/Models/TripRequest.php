<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripRequest extends Model
{
    protected $fillable = [
        'student_id',
        'student_name',
        'student_email',
        'student_number',
        'site',
        'date',
        'time',
        'notes',
        'status',
        'source',
        'uploaded_by',
        'quote_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
