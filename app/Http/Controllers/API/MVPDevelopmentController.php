<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\MVPDevelopment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class MVPDevelopmentController extends Controller
{
    public function index(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $latestDevelopment = MVPDevelopment::where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->with(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
            ->latest()
            ->first();
    
        return response()->json($latestDevelopment, 200);
    }
    
    public function show($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        $mvpDevelopment = MVPDevelopment::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->with(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
            ->firstOrFail();
    
        return response()->json($mvpDevelopment, 200);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'features.must_have_features' => 'nullable|array',
            'features.should_have_features' => 'nullable|array',
            'features.nice_to_have_features' => 'nullable|array',
            'features.metrics' => 'nullable|array',
            'features.metrics.*.name' => 'nullable|string',
            'features.metrics.*.target_value' => 'nullable|numeric',
            'features.metrics.*.actual_value' => 'nullable|numeric',
            'assumptions' => 'nullable|array',
            'assumptions.*.description' => 'nullable|string',
            'assumptions.*.test_method' => 'nullable|string',
            'assumptions.*.success_criteria' => 'nullable|string',
            'assumptions.*.metrics' => 'nullable|array',
            'assumptions.*.metrics.*.name' => 'nullable|string',
            'assumptions.*.metrics.*.target_value' => 'nullable|numeric',
            'assumptions.*.metrics.*.actual_value' => 'nullable|numeric',
            'timelines' => 'nullable|array',
            'timelines.*.name' => 'nullable|string',
            'timelines.*.duration' => 'nullable|string',
            'timelines.*.milestones' => 'nullable|array',
            'timelines.*.metrics' => 'nullable|array',
            'timelines.*.metrics.*.name' => 'nullable|string',
            'timelines.*.metrics.*.target_value' => 'nullable|numeric',
            'timelines.*.metrics.*.actual_value' => 'nullable|numeric',
        ]);
    
        $businessId = $this->getValidatedBusinessId($request);
        $validatedData['user_id'] = Auth::id();
        $validatedData['business_id'] = $businessId;
    
        $mvpDevelopment = MVPDevelopment::create([
            'user_id' => $validatedData['user_id'],
            'business_id' => $validatedData['business_id']
        ]);
    
         if (isset($validatedData['features'])) {
            $featuresData = $validatedData['features'];
            $featuresData['user_id'] = $validatedData['user_id'];
            $featuresData['business_id'] = $validatedData['business_id'];
            $features = $mvpDevelopment->features()->create($featuresData);
    
            if (isset($featuresData['metrics'])) {
                foreach ($featuresData['metrics'] as $metric) {
                    $metric['section_id'] = $features->id;
                    $metric['section_type'] = 'features';
                    $metric['user_id'] = $validatedData['user_id'];
                    $metric['business_id'] = $validatedData['business_id'];
                    $mvpDevelopment->metrics()->create($metric);
                }
            }
        }
    
        if (isset($validatedData['assumptions'])) {
            foreach ($validatedData['assumptions'] as $assumption) {
                $assumption['user_id'] = $validatedData['user_id'];
                $assumption['business_id'] = $validatedData['business_id'];
                $assumptionRecord = $mvpDevelopment->assumptions()->create($assumption);
    
                if (isset($assumption['metrics'])) {
                    foreach ($assumption['metrics'] as $metric) {
                        $metric['section_id'] = $assumptionRecord->id;
                        $metric['section_type'] = 'assumptions';
                        $metric['user_id'] = $validatedData['user_id'];
                        $metric['business_id'] = $validatedData['business_id'];
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
    
        if (isset($validatedData['timelines'])) {
            foreach ($validatedData['timelines'] as $timeline) {
                $timeline['user_id'] = $validatedData['user_id'];
                $timeline['business_id'] = $validatedData['business_id'];
                $timelineRecord = $mvpDevelopment->timelines()->create($timeline);
    
                if (isset($timeline['metrics'])) {
                    foreach ($timeline['metrics'] as $metric) {
                        $metric['section_id'] = $timelineRecord->id;
                        $metric['section_type'] = 'timelines';
                        $metric['user_id'] = $validatedData['user_id'];
                        $metric['business_id'] = $validatedData['business_id'];
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
    
        return response()->json(['message' => 'MVP Development created successfully', 'data' => $mvpDevelopment->load(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])], 201);
    }
   

    public function update(Request $request, $id)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $mvpDevelopment = MVPDevelopment::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    
        $validatedData = $request->validate([
            'features.must_have_features' => 'nullable|array',
            'features.should_have_features' => 'nullable|array',
            'features.nice_to_have_features' => 'nullable|array',
            'features.metrics' => 'nullable|array',
            'features.metrics.*.name' => 'sometimes|nullable|string',
            'features.metrics.*.target_value' => 'sometimes|nullable|numeric',
            'features.metrics.*.actual_value' => 'sometimes|nullable|numeric',
            'assumptions' => 'nullable|array',
            'assumptions.*.description' => 'sometimes|nullable|string',
            'assumptions.*.test_method' => 'sometimes|nullable|string',
            'assumptions.*.success_criteria' => 'sometimes|nullable|string',
            'assumptions.*.metrics' => 'nullable|array',
            'assumptions.*.metrics.*.name' => 'sometimes|nullable|string',
            'assumptions.*.metrics.*.target_value' => 'sometimes|nullable|numeric',
            'assumptions.*.metrics.*.actual_value' => 'sometimes|nullable|numeric',
            'timelines' => 'nullable|array',
            'timelines.*.name' => 'sometimes|nullable|string',
            'timelines.*.duration' => 'sometimes|nullable|string',
            'timelines.*.milestones' => 'nullable|array',
            'timelines.*.metrics' => 'nullable|array',
            'timelines.*.metrics.*.name' => 'sometimes|nullable|string',
            'timelines.*.metrics.*.target_value' => 'sometimes|nullable|numeric',
            'timelines.*.metrics.*.actual_value' => 'sometimes|nullable|numeric',
        ]);
       if (isset($validatedData['features'])) {
            $featuresData = $validatedData['features'];
            $features = $mvpDevelopment->features()->updateOrCreate([], $featuresData);
       if (isset($featuresData['metrics'])) {
                $features->metrics()->delete();
                foreach ($featuresData['metrics'] as $metric) {
                    $metric['section_id'] = $features->id;
                    $metric['section_type'] = 'features';
                    $metric['user_id'] = Auth::id();
                    $metric['business_id'] = $businessId;
                    $mvpDevelopment->metrics()->create($metric);
                }
            }
        }
       if (isset($validatedData['assumptions'])) {
            $mvpDevelopment->assumptions()->delete();
            foreach ($validatedData['assumptions'] as $assumption) {
                $assumption['user_id'] = Auth::id();
                $assumption['business_id'] = $businessId;
                $assumptionRecord = $mvpDevelopment->assumptions()->create($assumption);
      if (isset($assumption['metrics'])) {
                    foreach ($assumption['metrics'] as $metric) {
                        $metric['section_id'] = $assumptionRecord->id;
                        $metric['section_type'] = 'assumptions';
                        $metric['user_id'] = Auth::id();
                        $metric['business_id'] = $businessId;
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
       if (isset($validatedData['timelines'])) {
            $mvpDevelopment->timelines()->delete();
            foreach ($validatedData['timelines'] as $timeline) {
                $timeline['user_id'] = Auth::id();
                $timeline['business_id'] = $businessId;
                $timelineRecord = $mvpDevelopment->timelines()->create($timeline);
       if (isset($timeline['metrics'])) {
                    foreach ($timeline['metrics'] as $metric) {
                        $metric['section_id'] = $timelineRecord->id;
                        $metric['section_type'] = 'timelines';
                        $metric['user_id'] = Auth::id();
                        $metric['business_id'] = $businessId;
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
    
        return response()->json([
            'message' => 'MVP development updated successfully',
            'data' => $mvpDevelopment->load(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
        ], 200);
    }

    public function destroy($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        $mvpDevelopment = MVPDevelopment::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $mvpDevelopment->delete();
        return response()->json(['message' => 'MVP development deleted successfully'], 204);
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
