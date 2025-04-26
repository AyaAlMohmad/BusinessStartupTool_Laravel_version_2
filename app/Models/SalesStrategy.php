<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SalesStrategy extends Model
{
    use Auditable;
    protected $table = 'sales_strategies';
    protected $fillable = ['user_id',   'business_id'];
    public function salesChannels()
    {
        return $this->hasMany(SalesChannel::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function pricingTiers()
    {
        return $this->hasMany(PricingTier::class);
    }

    public function salesProcesses()
    {
        return $this->hasMany(SalesProcess::class);
    }

    public function salesTeams()
    {
        return $this->hasMany(SalesTeam::class);
    }
}
