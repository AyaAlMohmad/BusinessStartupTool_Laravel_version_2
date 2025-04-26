<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class BusinessSetupController extends Controller
{
    public function index(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);
        
        $latestSetup = BusinessSetup::where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->with(['licenses', 'locations', 'insurances'])
            ->latest()
            ->first();

        return response()->json($latestSetup, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'legal_structure.business_type' => 'nullable|string',
            'legal_structure.requirements' => 'nullable|array',
            'legal_structure.timeline' => 'nullable|string',
            'legal_structure.setup_costs' => 'nullable|numeric',
            'licenses_and_permits' => 'nullable|array',
            'licenses_and_permits.*.name' => 'nullable|string',
            'licenses_and_permits.*.requirements' => 'nullable|array',
            'licenses_and_permits.*.status' => 'nullable|string',
            'licenses_and_permits.*.deadline' => 'nullable|date',
            'locations' => 'nullable|array',
            'locations.*.type' => 'nullable|string',
            'locations.*.address' => 'nullable|string',
            'locations.*.size' => 'nullable|numeric',
            'locations.*.monthly_cost' => 'nullable|numeric',
            'insurance' => 'nullable|array',
            'insurance.*.type' => 'nullable|string',
            'insurance.*.provider' => 'nullable|string',
            'insurance.*.coverage' => 'nullable|string',
            'insurance.*.annual_cost' => 'nullable|numeric',
        ]);
        $businessId = $this->getValidatedBusinessId($request);
      
        $data['user_id'] = Auth::id();
        $data['business_id'] = $businessId;
       
        $businessSetup = BusinessSetup::create([
            'user_id' => $data['user_id'],
            'business_id'=>$data['business_id'],
                'business_type' => $data['legal_structure']['business_type'],
            'requirements' => $data['legal_structure']['requirements'],
            'timeline' => $data['legal_structure']['timeline'],
            'setup_costs' => $data['legal_structure']['setup_costs'],
            
        ]);

        if (isset($data['licenses_and_permits'])) {
            foreach ($data['licenses_and_permits'] as $licenseData) {
                $licenseData['user_id'] = $data['user_id'];
                $licenseData['business_id'] = $data['business_id'];
                $businessSetup->licenses()->create($licenseData);
            }
        }

        if (isset($data['locations'])) {
            foreach ($data['locations'] as $locationData) {
                $locationData['user_id'] = $data['user_id'];
                $locationData['business_id'] = $data['business_id'];
                $businessSetup->locations()->create($locationData);
            }
        }

        
        if (isset($data['insurance'])) {
            foreach ($data['insurance'] as $insuranceData) {
                $insuranceData['user_id'] = $data['user_id'];
                $insuranceData['business_id'] = $data['business_id'];
                $businessSetup->insurances()->create($insuranceData);
            }
        }

        return response()->json($businessSetup->load(['licenses', 'locations', 'insurances']), 201);
    }

    public function show($id)
    {
        $businessId = $this->getValidatedBusinessId(request());
        
        $businessSetup = BusinessSetup::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->with(['licenses', 'locations', 'insurances'])
            ->firstOrFail();

        return response()->json($businessSetup, 200);
    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'legal_structure.business_type' => 'sometimes|nullable|string',
            'legal_structure.requirements' => 'sometimes|nullable|array',
            'legal_structure.timeline' => 'sometimes|nullable|string',
            'legal_structure.setup_costs' => 'sometimes|nullable|numeric',
            'licenses_and_permits' => 'nullable|array',
            'licenses_and_permits.*.name' => 'sometimes|nullable|string',
            'licenses_and_permits.*.requirements' => 'sometimes|nullable|array',
            'licenses_and_permits.*.status' => 'sometimes|nullable|string',
            'licenses_and_permits.*.deadline' => 'sometimes|nullable|date',
            'locations' => 'nullable|array',
            'locations.*.type' => 'sometimes|nullable|string',
            'locations.*.address' => 'sometimes|nullable|string',
            'locations.*.size' => 'sometimes|nullable|numeric',
            'locations.*.monthly_cost' => 'sometimes|nullable|numeric',
            'insurance' => 'nullable|array',
            'insurance.*.type' => 'sometimes|nullable|string',
            'insurance.*.provider' => 'sometimes|nullable|string',
            'insurance.*.coverage' => 'sometimes|nullable|string',
            'insurance.*.annual_cost' => 'sometimes|nullable|numeric',
        ]);
        $businessId = $this->getValidatedBusinessId($request);
     
        $businessSetup = BusinessSetup::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            $data['business_id'] = $businessId;
        
        $businessSetup->update([
            'business_type' => $data['legal_structure']['business_type'] ?? $businessSetup->business_type,
            'requirements' => $data['legal_structure']['requirements'] ?? $businessSetup->requirements,
            'timeline' => $data['legal_structure']['timeline'] ?? $businessSetup->timeline,
            'setup_costs' => $data['legal_structure']['setup_costs'] ?? $businessSetup->setup_costs,
        ]);
    
        
        if (isset($data['licenses_and_permits'])) {
            $businessSetup->licenses()->delete();
            foreach ($data['licenses_and_permits'] as $licenseData) {
                $licenseData['user_id'] = Auth::id();
                $licenseData['business_id'] =   $data['business_id'];
                $businessSetup->licenses()->create($licenseData);
            }
        }
    
      
        if (isset($data['locations'])) {
            $businessSetup->locations()->delete();
            foreach ($data['locations'] as $locationData) {
                $locationData['user_id'] = Auth::id();
                $locationData['business_id'] =   $data['business_id'];
                $businessSetup->locations()->create($locationData);
            }
        }
    
  
        if (isset($data['insurance'])) {
            $businessSetup->insurances()->delete();
            foreach ($data['insurance'] as $insuranceData) {
                $insuranceData['user_id'] = Auth::id();
                $insuranceData['business_id'] =   $data['business_id'];
                $businessSetup->insurances()->create($insuranceData);
            }
        }
    
        return response()->json($businessSetup->load(['licenses', 'locations', 'insurances']), 200);
    }
    public function destroy($id)
    {
        $businessId = $this->getValidatedBusinessId(request());
        
        $businessSetup = BusinessSetup::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $businessSetup->delete();
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