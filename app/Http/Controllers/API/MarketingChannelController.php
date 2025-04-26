<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\MarketingChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class MarketingChannelController extends Controller
{
    public function index($marketingId)
    {
        $businessId = $this->getValidatedBusinessId(request());

        $latestChannel = MarketingChannel::where('marketing_id', $marketingId)
            ->where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->latest()
            ->get();

        return response()->json($latestChannel, 200);
    }

    public function store(Request $request, $marketingId)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'strategy' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'expected_roi' => 'nullable|string',
        ]);
 
        $businessId = $this->getValidatedBusinessId($request);
        $validatedData['user_id'] = Auth::id();
        $validatedData['marketing_id'] = $marketingId;
        $validatedData['business_id'] = $businessId;

        $channel = MarketingChannel::create($validatedData);
        return response()->json(['message' => 'Marketing channel created successfully', 'data' => $channel], 201);
    }

    public function show($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        return MarketingChannel::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function update(Request $request, $id)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $channel = MarketingChannel::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validatedData = $request->validate([
            'name' => 'sometimes|nullable|string',
            'strategy' => 'sometimes|nullable|string',
            'budget' => 'sometimes|nullable|numeric',
            'expected_roi' => 'sometimes|nullable|string',
        ]);

        $channel->update($validatedData);

        return response()->json(['message' => 'Marketing channel updated successfully', 'data' => $channel], 200);
    }

    public function destroy($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        $channel = MarketingChannel::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $channel->delete();

        return response()->json(['message' => 'Marketing channel deleted successfully'], 204);
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
