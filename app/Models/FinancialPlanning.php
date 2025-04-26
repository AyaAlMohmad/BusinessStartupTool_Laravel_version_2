<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class FinancialPlanning extends Model
{
    use Auditable;
    protected $fillable = [
        'startup_costs',
        'funding_sources',
        'revenue_projections',
        'expense_projections',
        'breakeven_analysis',
        'cash_flow_projections',
        'user_id',
        'business_id'
    ];

    protected $casts = [
        'startup_costs' => 'array',
        'funding_sources' => 'array',
        'revenue_projections' => 'array',
        'expense_projections' => 'array',
        'breakeven_analysis' => 'array',
        'cash_flow_projections' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function startupCosts()
    {
        return $this->hasMany(StartupCost::class);
    }

    public function fundingSources()
    {
        return $this->hasMany(FundingSource::class);
    }

    public function revenueProjections()
    {
        return $this->hasMany(RevenueProjection::class);
    }

    public function expenseProjections()
    {
        return $this->hasMany(ExpenseProjection::class);
    }
}
