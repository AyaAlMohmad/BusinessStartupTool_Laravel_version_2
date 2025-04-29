<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\FinancialPlanningController;
use App\Http\Controllers\API\BusinessIdeaController;
use App\Http\Controllers\API\BusinessSetupController;
use App\Http\Controllers\API\ConversionRateController;
use App\Http\Controllers\API\DownloadController;
use App\Http\Controllers\API\FinancialPlannerController;
use App\Http\Controllers\API\LaunchPreparationController;
use App\Http\Controllers\API\LegalStructureController;
use App\Http\Controllers\API\MarketingChannelController;
use App\Http\Controllers\API\MarketingController;
use App\Http\Controllers\API\MarketingNewController;
use App\Http\Controllers\API\MarketResearchController;
use App\Http\Controllers\API\MVPDevelopmentController;
use App\Http\Controllers\API\SalesStrategyController;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\SimpleSolutionController;
use App\Http\Controllers\API\TestingYourIdeaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset/{token}', [PasswordResetController::class, 'resetPassword']);






Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); 
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('businesses', BusinessController::class);
    Route::apiResource('marketing-new', MarketingNewController::class);
    Route::get('businesses/{business}/logs', [BusinessController::class, 'showLogs']);
    Route::apiResource('testing-ideas', TestingYourIdeaController::class);
// Business Idea Routes
Route::prefix('business-ideas')->group(function() {
    Route::get('/', [BusinessIdeaController::class, 'index']);
      Route::get('/{id}', [BusinessIdeaController::class, 'show']);
      Route::put('/{id}', [BusinessIdeaController::class, 'update']);
    Route::post('/', [BusinessIdeaController::class, 'store']);
});


Route::prefix('simple-solutions')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [SimpleSolutionController::class, 'index']);          
    Route::post('/', [SimpleSolutionController::class, 'store']);          
    Route::get('/{id}', [SimpleSolutionController::class, 'show']);        
    Route::put('/{id}', [SimpleSolutionController::class, 'update']);      
    Route::delete('/{id}', [SimpleSolutionController::class, 'destroy']);  
});

Route::get('/download-business-data', [DownloadController::class, 'downloadBusinessData']);

Route::get('/videos/search', [VideoController::class, 'searchByTitle']);
Route::get('/videos', [VideoController::class, 'index']); 
Route::get('/videos/{id}', [VideoController::class, 'show']); 
// Business Setup Routes 
Route::prefix('business-setups')->group(function() {
    // Route::get('/', [BusinessSetupController::class, 'index']);
    // Route::post('/', [BusinessSetupController::class, 'store']);
    // Route::get('/{id}', [BusinessSetupController::class, 'show']);
    // Route::put('/{id}', [BusinessSetupController::class, 'update']);
    // Route::delete('/{id}', [BusinessSetupController::class, 'destroy']);
    Route::get('/', [LegalStructureController::class, 'index']);
    Route::post('/', [LegalStructureController::class, 'store']);
    Route::get('/{id}', [LegalStructureController::class, 'show']);
    Route::put('/{id}', [LegalStructureController::class, 'update']);
    Route::delete('/{id}', [LegalStructureController::class, 'destroy']);
});


Route::get('/financial-planning', [FinancialPlanningController::class, 'index']); // GET all
Route::get('/financial-planning/{id}', [FinancialPlanningController::class, 'show']); // GET by ID
Route::put('/financial-planning/{id}', [FinancialPlanningController::class, 'update']);
Route::post('/financial-planning', [FinancialPlanningController::class, 'store']); // POST create


Route::get('/financial-planner', [FinancialPlannerController::class, 'index']); // GET all
Route::get('/financial-planner/{id}', [FinancialPlannerController::class, 'show']); // GET by ID
Route::put('/financial-planner/{id}', [FinancialPlannerController::class, 'update']);
Route::post('/financial-planner', [FinancialPlannerController::class, 'store']);



Route::apiResource('market-researches', MarketResearchController::class);

 
Route::apiResource('marketing', MarketingController::class);
// Route::apiResource('marketing.channels', MarketingChannelController::class)->shallow();


Route::apiResource('mvp-development', MVPDevelopmentController::class);


Route::apiResource('launch-preparations', LaunchPreparationController::class);

Route::apiResource('sales-strategies', SalesStrategyController::class);
Route::apiResource('sales-conversion-notes', ConversionRateController::class);
});