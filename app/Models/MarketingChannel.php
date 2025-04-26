<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class MarketingChannel extends Model
{
    use Auditable;
    protected $fillable = [
        'marketing_id',
        'name',
        'strategy',
        'budget',
        'expected_roi','user_id',
        'business_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
