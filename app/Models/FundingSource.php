<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class FundingSource extends Model
{
    use Auditable;
    protected $fillable = ['financial_planning_id','business_id', 'source', 'type', 'amount', 'status','user_id', 'terms'];

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
