<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assumption extends Model
{
    use Auditable;
    protected $fillable = [
        'mvp_development_id',
        'description',
        'test_method',
        'success_criteria',
        'user_id',
        'business_id'
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
        return $this->hasMany(Metric::class, 'section_id')->where('section_type', 'assumptions');
    }
}
