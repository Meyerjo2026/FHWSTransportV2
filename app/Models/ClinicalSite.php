<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalSite extends Model
{
    protected $fillable = [
        'name',
        'address',
        'lat',
        'lng',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'lat' => 'float',
            'lng' => 'float',
        ];
    }

    public function tripRequests()
    {
        return $this->hasMany(TripRequest::class);
    }
}
