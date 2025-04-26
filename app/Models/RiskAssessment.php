<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessment extends Model
{
    use Auditable;
    protected $fillable = [
        'launch_preparation_id',
        'description',
        'impact',
        'probability',
        'mitigation_strategies',
        'contingency_plan','user_id',   'business_id'
    ];

    protected $casts = [
        'mitigation_strategies' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function launchPreparation()
    {
        return $this->belongsTo(LaunchPreparation::class);
    }
}
