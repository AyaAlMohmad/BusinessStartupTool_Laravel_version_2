<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use Auditable;
    protected $fillable = [
        'mvp_development_id',
        'must_have_features',
        'should_have_features',
        'nice_to_have_features',
        'user_id',
        'business_id'
    ];

    protected $casts = [
        'must_have_features' => 'array',
        'should_have_features' => 'array',
        'nice_to_have_features' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function mvpDevelopment()
    {
        return $this->belongsTo(MVPDevelopment::class, 'mvp_development_id');
    }
    public function metrics()
    {
        return $this->hasMany(Metric::class, 'section_id')->where('section_type', 'features');
    }
}
