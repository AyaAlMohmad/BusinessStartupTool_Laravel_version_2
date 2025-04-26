<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class BusinessSetup extends Model
{
    use Auditable;
    protected $fillable = ['business_type','business_id' ,'requirements', 'timeline', 'setup_costs','user_id'];

    protected $casts = [
        'requirements' => 'array',
    ];

    public function licenses()
    {
        return $this->hasMany(LicensePermit::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}

