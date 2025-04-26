<?php

namespace App\Http\Controllers;

use App\Models\BusinessIdea;
use App\Models\BusinessSetup;
use App\Models\FinancialPlanning;
use App\Models\FundingSource;
use App\Models\LaunchPreparation;
use App\Models\Marketing;
use App\Models\MarketResearch;
use App\Models\MVPDevelopment;
use App\Models\SalesStrategy;
use App\Models\StartupCost;
use App\Models\User;
use Illuminate\Http\Request;

class ProgressAnalyticsController extends Controller
{
    public function index()
    {
    
        // $sectionCompletion = [
        //     'business_idea' => BusinessIdea::count() > 0 ? 100 : 0,
        //     'market_research' => MarketResearch::count() > 0 ? 100 : 0,
        //     'marketing' => Marketing::count() > 0 ? 100 : 0,
        //     'mvp_development' => MVPDevelopment::count() > 0 ? 100 : 0,
        //     'sales' => SalesStrategy::count() > 0 ? 100 : 0,
        //     'business_setup' => BusinessSetup::count() > 0 ? 100 : 0,
        //     'financial_planning' => FinancialPlanning::count() > 0 ? 100 : 0,
        //     'launch_preparation' => LaunchPreparation::count() > 0 ? 100 : 0,
        // ];
        $totalRecords = 100; 

        $sectionCompletion = [
            'business_idea' => round((BusinessIdea::count() / $totalRecords) * 100),
            'market_research' => round((MarketResearch::count() / $totalRecords) * 100),
            'marketing' => round((Marketing::count() / $totalRecords) * 100),
            'mvp_development' => round((MVPDevelopment::count() / $totalRecords) * 100),
            'sales' => round((SalesStrategy::count() / $totalRecords) * 100),
            'business_setup' => round((BusinessSetup::count() / $totalRecords) * 100),
            'financial_planning' => round((FinancialPlanning::count() / $totalRecords) * 100),
            'launch_preparation' => round((LaunchPreparation::count() / $totalRecords) * 100),
        ];
        $userActivity = [
            'last_24_hours' => User::whereDate('last_login', '=', now()->toDateString())->count(),
            'last_7_days' => User::whereDate('last_login', '>=', now()->subDays(7)->toDateString())
                                ->whereDate('last_login', '<=', now()->toDateString())
                                ->count(),
            'last_30_days' => User::whereDate('last_login', '>=', now()->subDays(30)->toDateString())
                                ->whereDate('last_login', '<=', now()->toDateString())
                                ->count(),
        ];

     
        $mostActiveSections = [
            'business_idea' => BusinessIdea::count(),
            'market_research' => MarketResearch::count(),
            'marketing' => Marketing::count(),
            'mvp_development' => MVPDevelopment::count(),
        ];

        return view('ProgressAnalytics', compact('sectionCompletion', 'userActivity', 'mostActiveSections'));
    }
}
