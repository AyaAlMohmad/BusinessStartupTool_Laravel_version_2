<?php
namespace App\Http\Controllers;

use App\Models\StartupCost;
use App\Models\FundingDistribution;
use App\Models\FundingSource;
use App\Models\FundingStatus;
use App\Models\MarketingActivity;
use App\Models\Metric;
use App\Models\MVPDevelopment;
use App\Models\SalesStrategy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $startupCosts = StartupCost::all();
        $fundingSources = FundingSource::all();
        $salesStrategies = SalesStrategy::all();
        $marketingActivities = MarketingActivity::all();
        $metrics = Metric::all();
        $mvpDevelopments = MVPDevelopment::all();

        return view('dashboard', compact(
            'startupCosts',
            'fundingSources',
            'salesStrategies',
            'marketingActivities',
            'metrics',
            'mvpDevelopments'
        ));
    }
}