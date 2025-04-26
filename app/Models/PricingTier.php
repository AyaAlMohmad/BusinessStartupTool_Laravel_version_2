<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    use Auditable;
    protected $fillable = [
        'sales_strategy_id',
        'name',
        'price',
        'features',
        'target_customer','user_id',
        'business_id'
    ];

    protected $casts = [
        'features' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function salesStrategy()
    {
        return $this->belongsTo(SalesStrategy::class);
    }
}
