<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use Auditable;
    protected $fillable = [
        'audience_description',
        'problem_statement',
        'solution_overview','user_id',
        'business_id'
    ];

    public function marketingChannels()
    {
        return $this->hasMany(MarketingChannel::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function contentStrategies()
    {
        return $this->hasMany(ContentStrategy::class);
    }

    public function brandIdentity()
    {
        return $this->hasOne(BrandIdentity::class);
    }
}
