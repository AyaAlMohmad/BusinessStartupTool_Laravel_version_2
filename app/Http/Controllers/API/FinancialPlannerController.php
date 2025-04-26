<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\FinancialPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FinancialPlannerController extends Controller
{
    // الحصول على البيانات
    public function index(Request $request)
    {
        try {
            $businessId = $this->validateBusiness($request);
            
            $data = FinancialPlanner::where('business_id', $businessId)
                ->where('user_id', Auth::id())
                ->latest()
                ->first();

            return response()->json($data ?? null);

        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    // إنشاء أو تحديث البيانات
    public function store(Request $request)
    {
        try {
            $businessId = $this->validateBusiness($request);
            
            $validator = Validator::make($request->all(), [
                'operational_details' => 'required|array',
                'notes' => 'nullable|string',
                'excel_file' => 'nullable|file|mimes:xlsx,xls|max:5120'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ], 422);
            }

            $data = $request->except('excel_file');
            $data['user_id'] = Auth::id();
            $data['business_id'] = $businessId;

            // معالجة ملف الإكسل
            if ($request->hasFile('excel_file')) {
                $file = $request->file('excel_file');
                $path = $file->store('financial-planners');
                $data['excel_file'] = $path;
            }

            // تحديث أو إنشاء سجل
            $planner = FinancialPlanner::updateOrCreate(
                ['business_id' => $businessId, 'user_id' => Auth::id()],
                $data
            );

            return response()->json($planner, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    // تنزيل الملف
    public function downloadFile(Request $request)
    {
        try {
            $businessId = $this->validateBusiness($request);
            
            $planner = FinancialPlanner::where('business_id', $businessId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if (!$planner->excel_file || !Storage::exists($planner->excel_file)) {
                throw new \Exception('File not found', 404);
            }

            return Storage::download($planner->excel_file);

        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    // حذف البيانات والملف
    public function destroy(Request $request)
    {
        try {
            $businessId = $this->validateBusiness($request);
            
            $planner = FinancialPlanner::where('business_id', $businessId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($planner->excel_file) {
                Storage::delete($planner->excel_file);
            }

            $planner->delete();

            return response()->json(['message' => 'Deleted successfully']);

        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    private function validateBusiness(Request $request)
    {
        $businessId = $request->header('business-id');
        if (!$businessId) {
            throw new \Exception('Business ID required', 422);
        }

        $business = Business::where('id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $businessId;
    }

    private function errorResponse(\Exception $e)
    {
        $code = $e->getCode() ?: 500;
        return response()->json([
            'error' => $e->getMessage()
        ], $code);
    }
}