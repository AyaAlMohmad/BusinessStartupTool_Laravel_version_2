<?php
namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class LaunchPreparation extends Model
{
    use Auditable;
    protected $table = 'launch_preparations';
protected $fillable=['user_id','business_id'];
    public function launchChecklists()
    {
        return $this->hasMany(LaunchChecklist::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function marketingActivities()
    {
        return $this->hasMany(MarketingActivity::class);
    }

    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }

    public function launchMilestones()
    {
        return $this->hasMany(LaunchMilestone::class);
    }
}
