<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BusinessController extends Controller
{
   
    public function index()
    {
        $businesses = Business::where('user_id', auth()->id())->get();
        return response()->json([
            'success' => true,
            'data' => $businesses
        ],200);
    }
    public function show($id)
    {
        $businesses = Business::where('user_id', auth()->id())->where('id', $id)->first();
        return response()->json([
            'success' => true,
            'data' => $businesses
        ],200);
    }

   
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'products_services' => 'nullable|array',
            'about_me' => 'nullable|array',
            'is_migrant' => 'nullable|boolean',
            'years_here' => 'nullable|integer',
            'english_level' => 'nullable|array',
            'is_business_old' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();

        $business = Business::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Business created successfully',
            'data' => $business
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $businessId)
    {
        $business = Business::where('id', $businessId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'products_services' => 'nullable|array',
            'about_me' => 'nullable|array',
            'is_migrant' => 'nullable|boolean',
            'years_here' => 'nullable|integer',
            'english_level' => 'nullable|array',
            'is_business_old' => 'nullable|boolean',
        ]);

        $business->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Business updated successfully',
            'data' => $business
        ], 200);
    }

    public function destroy($businessId)
    {
        $business = Business::where('id', $businessId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $business->delete();

        return response()->json([
            'success' => true,
            'message' => 'Business deleted successfully'
        ], 200);
    }

    public function showLogs($businessId)
    {
        $business = Business::where('id', $businessId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $logs = AuditLog::where('table_name', 'businesses')
            ->where('record_id', $business->id)
            ->with('user')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'business' => $business,
                'logs' => $logs
            ]
        ], 200);
    }
}