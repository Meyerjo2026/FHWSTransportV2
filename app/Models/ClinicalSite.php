<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalSite extends Model
{
    protected $fillable = [
        'name',
        'address',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function tripRequests()
    {
        return $this->hasMany(TripRequest::class);
    }
}
