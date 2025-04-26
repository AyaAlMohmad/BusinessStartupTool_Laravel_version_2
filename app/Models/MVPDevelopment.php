<?php
namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class MVPDevelopment extends Model
{
    use Auditable;
    protected $table = 'mvp_developments';
protected $fillable=['user_id','business_id'];
    public function features()
    {
        return $this->hasOne(Feature::class,'mvp_development_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function assumptions()
    {
        return $this->hasMany(Assumption::class,'mvp_development_id');
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class,'mvp_development_id');
    }

    public function metrics()
    {
        return $this->hasMany(Metric::class,'mvp_development_id');
    }
}
