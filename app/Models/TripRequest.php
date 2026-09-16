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
        'clinical_site_id',
        'site',
        'date',
        'time',
        'notes',
        'department',
        'qualification',
        'status',
        'source',
        'uploaded_by',
        'quote_id',
        'journey_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function clinicalSite()
    {
        return $this->belongsTo(ClinicalSite::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }
}
