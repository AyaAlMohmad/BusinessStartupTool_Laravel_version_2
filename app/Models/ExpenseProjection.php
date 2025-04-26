<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class ExpenseProjection extends Model
{
    use Auditable;
    protected $fillable = ['financial_planning_id', 'business_id', 'month', 'fixed_expenses', 'variable_expenses', 'user_id', 'assumptions'];

    protected $casts = [
        'assumptions' => 'array',
    ];

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
