<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\ConversionRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
class ConversionRateController extends Controller
{
    public function index(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);
    
        $rates = ConversionRate::where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->latest()
            ->get();
    
        return response()->json(['data' => $rates]);
    }
    private function findRateForUser($id, $request)
    {
        $businessId = $this->getValidatedBusinessId($request);
    
        $rate = ConversionRate::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->first();
    
        if (!$rate) {
            abort(Response::HTTP_NOT_FOUND, 'Conversion Rate not found or access denied');
        }
    
        return $rate;
    }
        

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_revenue' => 'nullable|numeric',
            'unit_price' => 'nullable|numeric',
            'interactions_needed' => 'nullable|numeric',
            'engagement_needed' => 'nullable|numeric',
            'reach_needed' => 'nullable|numeric'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $businessId = $this->getValidatedBusinessId($request);
    
        $rate = ConversionRate::create([
            'user_id' => Auth::id(),
            'business_id' => $businessId,
            'target_revenue' => $request->target_revenue,
            'unit_price' => $request->unit_price,
            'interactions_needed' => $request->interactions_needed,
            'engagement_needed' => $request->engagement_needed,
            'reach_needed' => $request->reach_needed,
        ]);
    
        return response()->json(['data' => $rate], 201);
    }
    

    public function show(Request $request, $id)
    {
        $rate = $this->findRateForUser($id, $request);
        return response()->json(['data' => $rate]);
    }
    
    public function update(Request $request, $id)
    {
        $rate = $this->findRateForUser($id, $request);
    
        $validator = Validator::make($request->all(), [
            'target_revenue' => 'nullable|numeric',
            'unit_price' => 'nullable|numeric',
            'interactions_needed' => 'nullable|numeric',
            'engagement_needed' => 'nullable|numeric',
            'reach_needed' => 'nullable|numeric'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $rate->update($request->all());
        return response()->json(['data' => $rate]);
    }
    

    public function destroy(Request $request, $id)
    {
        $rate = $this->findRateForUser($id, $request);
        $rate->delete();
        return response()->json(null, 204);
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