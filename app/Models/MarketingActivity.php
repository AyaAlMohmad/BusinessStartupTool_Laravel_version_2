<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingActivity extends Model
{
    use Auditable;
    protected $fillable = [
        'launch_preparation_id',
        'activity',
        'timeline',
        'budget',
        'status',
        'metrics','user_id',
        'business_id'
    ];

    protected $casts = [
        'metrics' => 'array',
    ];

    public function launchPreparation()
    {
        return $this->belongsTo(LaunchPreparation::class);
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
