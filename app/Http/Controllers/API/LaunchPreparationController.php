<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\LaunchPreparation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class LaunchPreparationController extends Controller
{
    public function index(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $latestPreparation = LaunchPreparation::where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->with(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])
            ->latest()
            ->first();

        return response()->json($latestPreparation, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'launch_checklists' => 'nullable|array',
            'launch_checklists.*.category' => 'nullable|string',
            'launch_checklists.*.task' => 'nullable|string',
            'launch_checklists.*.due_date' => 'nullable|date',
            'launch_checklists.*.status' => 'nullable|string',
            'launch_checklists.*.assignee' => 'nullable|string',
            'launch_checklists.*.notes' => 'nullable|string',
            'marketing_activities' => 'nullable|array',
            'marketing_activities.*.activity' => 'nullable|string',
            'marketing_activities.*.timeline' => 'nullable|string',
            'marketing_activities.*.budget' => 'nullable|numeric',
            'marketing_activities.*.status' => 'nullable|string',
            'marketing_activities.*.metrics' => 'nullable|array',
            'risk_assessments' => 'nullable|array',
            'risk_assessments.*.description' => 'nullable|string',
            'risk_assessments.*.impact' => 'nullable|string',
            'risk_assessments.*.probability' => 'nullable|string',
            'risk_assessments.*.mitigation_strategies' => 'nullable|array',
            'risk_assessments.*.contingency_plan' => 'nullable|string',
            'launch_milestones' => 'nullable|array',
            'launch_milestones.*.description' => 'nullable|string',
            'launch_milestones.*.due_date' => 'nullable|date',
            'launch_milestones.*.status' => 'nullable|string',
            'launch_milestones.*.dependencies' => 'nullable|array',
        ]);

    $businessId = $this->getValidatedBusinessId($request);
    $validatedData['user_id'] = Auth::id();
    $validatedData['business_id'] = $businessId;

   
    $launchPreparation = LaunchPreparation::create([
        'user_id' => $validatedData['user_id'],
        'business_id' => $validatedData['business_id']
    ]);

   
    if (isset($validatedData['launch_checklists'])) {
        foreach ($validatedData['launch_checklists'] as $checklist) {
            $checklist['user_id'] = $validatedData['user_id'];
            $checklist['business_id'] = $validatedData['business_id'];
            $launchPreparation->launchChecklists()->create($checklist);
        }
    }

    if (isset($validatedData['marketing_activities'])) {
        foreach ($validatedData['marketing_activities'] as $activity) {
            $activity['user_id'] = $validatedData['user_id'];
            $activity['business_id'] = $validatedData['business_id'];
            $launchPreparation->marketingActivities()->create($activity);
        }
    }

  
    if (isset($validatedData['risk_assessments'])) {
        foreach ($validatedData['risk_assessments'] as $risk) {
            $risk['user_id'] = $validatedData['user_id'];
            $risk['business_id'] = $validatedData['business_id'];
            $launchPreparation->riskAssessments()->create($risk);
        }
    }

 
    if (isset($validatedData['launch_milestones'])) {
        foreach ($validatedData['launch_milestones'] as $milestone) {
            $milestone['user_id'] = $validatedData['user_id'];
            $milestone['business_id'] = $validatedData['business_id'];
            $launchPreparation->launchMilestones()->create($milestone);
        }
    }

        return response()->json([
            'message' => 'Launch preparation created successfully',
            'data' => $launchPreparation->load(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])
        ], 201);
    }

    public function show($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        $launchPreparation = LaunchPreparation::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->with(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])
            ->firstOrFail();

        return response()->json($launchPreparation, 200);
    }

    public function update(Request $request, $id)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $launchPreparation = LaunchPreparation::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    
        $validatedData = $request->validate([
            'launch_checklists' => 'nullable|array',
            'launch_checklists.*.category' => 'sometimes|nullable|string',
            'launch_checklists.*.task' => 'sometimes|nullable|string',
            'launch_checklists.*.due_date' => 'sometimes|nullable|date',
            'launch_checklists.*.status' => 'sometimes|nullable|string',
            'launch_checklists.*.assignee' => 'sometimes|nullable|string',
            'launch_checklists.*.notes' => 'nullable|string',
            'marketing_activities' => 'nullable|array',
            'marketing_activities.*.activity' => 'sometimes|nullable|string',
            'marketing_activities.*.timeline' => 'sometimes|nullable|string',
            'marketing_activities.*.budget' => 'sometimes|nullable|numeric',
            'marketing_activities.*.status' => 'sometimes|nullable|string',
            'marketing_activities.*.metrics' => 'nullable|array',
            'risk_assessments' => 'nullable|array',
            'risk_assessments.*.description' => 'sometimes|nullable|string',
            'risk_assessments.*.impact' => 'sometimes|nullable|string',
            'risk_assessments.*.probability' => 'sometimes|nullable|string',
            'risk_assessments.*.mitigation_strategies' => 'nullable|array',
            'risk_assessments.*.contingency_plan' => 'nullable|string',
            'launch_milestones' => 'nullable|array',
            'launch_milestones.*.description' => 'sometimes|nullable|string',
            'launch_milestones.*.due_date' => 'sometimes|nullable|date',
            'launch_milestones.*.status' => 'sometimes|nullable|string',
            'launch_milestones.*.dependencies' => 'nullable|array',
        ]);
    
      
        if (isset($validatedData['launch_checklists'])) {
            $launchPreparation->launchChecklists()->delete();
            foreach ($validatedData['launch_checklists'] as $checklist) {
                $checklist['user_id'] = Auth::id();
                $checklist['business_id'] = $businessId;
                $launchPreparation->launchChecklists()->create($checklist);
            }
        }
    
      
        if (isset($validatedData['marketing_activities'])) {
            $launchPreparation->marketingActivities()->delete();
            foreach ($validatedData['marketing_activities'] as $activity) {
                $activity['user_id'] = Auth::id();
                $activity['business_id'] = $businessId;
                $launchPreparation->marketingActivities()->create($activity);
            }
        }
    
   
        if (isset($validatedData['risk_assessments'])) {
            $launchPreparation->riskAssessments()->delete();
            foreach ($validatedData['risk_assessments'] as $risk) {
                $risk['user_id'] = Auth::id();
                $risk['business_id'] = $businessId;
                $launchPreparation->riskAssessments()->create($risk);
            }
        }
    
    
        if (isset($validatedData['launch_milestones'])) {
            $launchPreparation->launchMilestones()->delete();
            foreach ($validatedData['launch_milestones'] as $milestone) {
                $milestone['user_id'] = Auth::id();
                $milestone['business_id'] = $businessId;
                $launchPreparation->launchMilestones()->create($milestone);
            }
        }
    
        return response()->json([
            'message' => 'Launch preparation updated successfully',
            'data' => $launchPreparation->load(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])
        ], 200);
    }

    public function destroy($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        $launchPreparation = LaunchPreparation::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $launchPreparation->delete();
        return response()->json(['message' => 'Launch preparation deleted successfully'], 204);
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
