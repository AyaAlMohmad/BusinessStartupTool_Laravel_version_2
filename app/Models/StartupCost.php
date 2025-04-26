<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class StartupCost extends Model
{
    use Auditable;
    protected $fillable = ['financial_planning_id',   'business_id','user_id', 'item', 'category', 'amount', 'timing', 'notes'];

    public function financialPlanning()
    {
        return $this->belongsTo(FinancialPlanning::class);
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
