<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\FinancialPlanning;
use App\Models\FundingSource;
use App\Models\RevenueProjection;
use App\Models\ExpenseProjection;
use App\Models\StartupCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class FinancialPlanningController extends Controller
{
    // Store Financial Planning Data
    public function store(Request $request)
    {
        $data = $request->validate([
            'startup_costs' => 'nullable|array',
            'funding_sources' => 'nullable|array',
            'revenue_projections' => 'nullable|array',
            'expense_projections' => 'nullable|array',
            'breakeven_analysis' => 'nullable|array',
            'cash_flow_projections' => 'nullable|array',
        ]);

        $businessId = $this->getValidatedBusinessId($request);

        // Add user_id and business_id to main data array
        $data['user_id'] = Auth::id();
        $data['business_id'] = $businessId;

        // Create a new FinancialPlanning record
        $financialPlanning = FinancialPlanning::create($data);

        // Save the related models
        foreach ($data['startup_costs'] as $cost) {
            $cost['user_id'] = $data['user_id'];
            $cost['business_id'] = $businessId;
            $financialPlanning->startupCosts()->create($cost);
        }

        foreach ($data['funding_sources'] as $source) {
            $source['user_id'] = $data['user_id'];
            $source['business_id'] = $businessId;
            $financialPlanning->fundingSources()->create($source);
        }

        foreach ($data['revenue_projections'] as $projection) {
            $projection['user_id'] = $data['user_id'];
            $projection['business_id'] = $businessId;
            $financialPlanning->revenueProjections()->create($projection);
        }

        foreach ($data['expense_projections'] as $projection) {
            $projection['user_id'] = $data['user_id'];
            $projection['business_id'] = $businessId;
            $financialPlanning->expenseProjections()->create($projection);
        }

        return response()->json($financialPlanning, 201);
    }

    public function update(Request $request, $id)
    {
        $businessId = $this->getValidatedBusinessId($request);

        // Check if the record exists and belongs to user/business
        $financialPlanning = FinancialPlanning::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->first();
    
        if (!$financialPlanning) {
            return response()->json([
                'message' => 'Financial planning record not found.',
            ], 404);
        }
    
        // Validate the request data
        $data = $request->validate([
            'startup_costs' => 'nullable|array',
            'funding_sources' => 'nullable|array',
            'revenue_projections' => 'nullable|array',
            'expense_projections' => 'nullable|array',
            'breakeven_analysis' => 'nullable|array',
            'cash_flow_projections' => 'nullable|array',
        ]);
    
        // Update main record
        $financialPlanning->update($data);
    
        // Delete existing related records
        $financialPlanning->startupCosts()->delete();
        $financialPlanning->fundingSources()->delete();
        $financialPlanning->revenueProjections()->delete();
        $financialPlanning->expenseProjections()->delete();
    
        // Create new related records
        if (isset($data['startup_costs'])) {
            foreach ($data['startup_costs'] as $cost) {
                $cost['user_id'] = Auth::id();
                $cost['business_id'] = $businessId;
                $financialPlanning->startupCosts()->create($cost);
            }
        }
    
        if (isset($data['funding_sources'])) {
            foreach ($data['funding_sources'] as $source) {
                $source['user_id'] = Auth::id();
                $source['business_id'] = $businessId;
                $financialPlanning->fundingSources()->create($source);
            }
        }
    
        if (isset($data['revenue_projections'])) {
            foreach ($data['revenue_projections'] as $projection) {
                $projection['user_id'] = Auth::id();
                $projection['business_id'] = $businessId;
                $financialPlanning->revenueProjections()->create($projection);
            }
        }
    
        if (isset($data['expense_projections'])) {
            foreach ($data['expense_projections'] as $projection) {
                $projection['user_id'] = Auth::id();
                $projection['business_id'] = $businessId;
                $financialPlanning->expenseProjections()->create($projection);
            }
        }
    
        return response()->json($financialPlanning->fresh(), 200);
    }

    // Get Financial Planning Data
    public function index(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $latestPlanning = FinancialPlanning::where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->with([
                'startupCosts',
                'fundingSources', 
                'revenueProjections',
                'expenseProjections'
            ])
            ->latest()
            ->first();

        return response()->json($latestPlanning, 200);
    }

    // Get a specific Financial Planning Data by ID
    public function show($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        $financialPlanning = FinancialPlanning::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->with([
                'startupCosts',
                'fundingSources',
                'revenueProjections',
                'expenseProjections'
            ])
            ->firstOrFail();

        return response()->json($financialPlanning, 200);
    }
    private function getValidatedBusinessId(Request $request)
    {
        $businessId = $request->header('business_id');
        
       
        if (!$businessId) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Missing business_id header');
        }
        
      
        $business = Business::where('id', $businessId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$business) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized access to business');
        }

        return $businessId;
    }
}
