<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use Auditable;
    protected $fillable = [
        'mvp_development_id',
        'name',
        'target_value',
        'actual_value',
         'section_id', 'section_type',
         'user_id',
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
    public function mvpDevelopment()
    {
        return $this->belongsTo(MVPDevelopment::class);
    }
    public function section()
    {
        return $this->morphTo();
    }
}
