<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TenantController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\QuestionStatusController;
use App\Http\Controllers\API\TerminalStatusController;

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

Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group( function () {
   
    Route::resource('tenants', TenantController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('users', UserController::class);
    Route::resource('questions', QuestionController::class);
    Route::resource('questionstatus', QuestionStatusController::class);
    Route::resource('terminalstatus', TerminalStatusController::class);

    

});

Route::resource('roles', RoleController::class);