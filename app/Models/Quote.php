<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'ref',
        'period',
        'rate',
        'total',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(TripRequest::class, 'quote_id');
    }
}
