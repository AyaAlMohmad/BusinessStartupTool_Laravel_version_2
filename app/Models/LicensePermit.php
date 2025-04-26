<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class LicensePermit extends Model
{
    use Auditable;
    protected $fillable = ['user_id', 'business_id', 'business_setup_id', 'name', 'requirements', 'status', 'deadline'];

    protected $casts = [
        'requirements' => 'array',
        'deadline' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function businessSetup()
    {
        return $this->belongsTo(BusinessSetup::class);
    }
}
