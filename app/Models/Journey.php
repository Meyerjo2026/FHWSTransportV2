<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    protected $fillable = [
        'date',
        'time',
        'label',
        'threshold_km',
        'created_by',
    ];

    public function tripRequests()
    {
        return $this->hasMany(TripRequest::class);
    }
}
