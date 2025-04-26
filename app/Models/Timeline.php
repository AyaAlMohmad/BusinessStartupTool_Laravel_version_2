<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use Auditable;
    protected $fillable = [
        'mvp_development_id',
        'name',
        'duration',
        'milestones','user_id',   'business_id'
    ];

    protected $casts = [
        'milestones' => 'array',
    ];

    public function mvpDevelopment()
    {
        return $this->belongsTo(MVPDevelopment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function metrics()
    {
        return $this->hasMany(Metric::class, 'section_id')->where('section_type', 'timelines');
    }
}
