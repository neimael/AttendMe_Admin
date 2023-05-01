<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignmentElevatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SanitaryIssuesController;
use App\Http\Controllers\ElevatorController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


//employees
Route::get('employees', [EmployeeController::class,'index']);
Route::post('add_employee', [EmployeeController::class, 'store']);
Route::delete('delete_employee/{id}', [EmployeeController::class, 'destroy']);
Route::get('/get_employee/{id}', [EmployeeController::class, 'getEmployee']);
//sanitaryIssues
Route::post('add_sanitary_issue', [SanitaryIssuesController::class, 'store']);
//admins
Route::get('admins', [AdminController::class, 'index']);
Route::post('add_admin', [AdminController::class, 'store']);
Route::delete('delete_admin/{id}', [AdminController::class, 'destroy']);
Route::get('/get_admin/{id}', [AdminController::class, 'getAdmin']);

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



Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);

});

