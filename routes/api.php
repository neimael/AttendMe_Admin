<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignmentElevatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SanitaryIssuesController;
use App\Http\Controllers\ElevatorController;
use App\Http\Controllers\PresenceController;



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
Route::get('employees', [EmployeeController::class,'index']);
Route::post('add_employee', [EmployeeController::class, 'store']);
Route::delete('delete_employee/{id}', [EmployeeController::class, 'destroy']);
//sanitaryIssues
Route::post('add_sanitary_issue', [SanitaryIssuesController::class, 'store']);
//admins
Route::get('admins', [AdminController::class, 'index']);
Route::post('add_admin', [AdminController::class, 'store']);
Route::delete('delete_admin/{id}', [AdminController::class, 'destroy']);

//Asignement
Route::post('add_asignment', [AssignmentElevatorController::class, 'store']);
Route::get('assignmentElevator', [AssignmentElevatorController::class, 'index']);
//Elevator
Route::post('add_elevator', [ElevatorController::class, 'store']);
Route::get('elevators', [ElevatorController::class, 'index']);
//Presence
Route::post('add_presence', [PresenceController::class, 'store']);
Route::get('presences', [PresenceController::class, 'index']);
//PresenceRegulation
Route::post('add_presence_regulation', [PresenceRegulationController::class, 'store']);
Route::get('presence_regulations', [PresenceRegulationController::class, 'index']);

