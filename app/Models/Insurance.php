<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use Auditable;
    protected $fillable = ['business_setup_id','business_id', 'type', 'provider', 'coverage', 'user_id','annual_cost'];

    public function businessSetup()
    {
        return $this->belongsTo(BusinessSetup::class);
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
