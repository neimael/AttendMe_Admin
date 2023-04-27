<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SanitaryIssuesController;
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
//employees
Route::get('employees', [EmployeeController::class, 'index']);
//sanitaryIssues
Route::post('add_sanitary_issue', [SanitaryIssuesController::class, 'store']);
//admins
Route::get('admins', [AdminController::class, 'index']);
Route::post('add_admin', [AdminController::class, 'store']);



