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
use App\Http\Controllers\PresenceRegulationsController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


//employees
Route::get('employees', [EmployeeController::class,'index']);
Route::post('add_employee', [EmployeeController::class, 'store']);
Route::delete('delete_employee/{id}', [EmployeeController::class, 'destroy']);
Route::get('/get_employee/{id}', [EmployeeController::class, 'getEmployee']);
Route::put('/update_employee/{id}', [EmployeeController::class, 'update']);
Route::get('export_employees', [EmployeeController::class, 'export']);
Route::get('export_employees_pdf', [EmployeeController::class, 'exportToPDF']);
//sanitaryIssues
Route::get('sanitaryIssues', [SanitaryIssuesController::class, 'index']);
Route::get('export_sanitaries', [SanitaryIssuesController::class, 'export']);
Route::get('export_sanitaries_pdf', [SanitaryIssuesController::class, 'exportToPDF']);

//admins
Route::get('admins', [AdminController::class, 'index']);
Route::post('add_admin', [AdminController::class, 'store']);
Route::delete('delete_admin/{id}', [AdminController::class, 'destroy']);
Route::get('/get_admin/{id}', [AdminController::class, 'getAdmin']);
Route::put('/update_admin/{id}', [AdminController::class, 'update']);
Route::get('export_admins', [AdminController::class, 'export']);
Route::get('export_admins_pdf', [AdminController::class, 'exportToPDF']);
//Asignement
Route::post('addAsignment', [AssignmentElevatorController::class, 'store']);
Route::get('assignmentElevator', [AssignmentElevatorController::class, 'index']);
Route::delete('delete_asignment/{id}', [AssignmentElevatorController::class, 'destroy']);
Route::get('export_asignments', [AssignmentElevatorController::class, 'export']);
Route::get('export_asignments_pdf', [AssignmentElevatorController::class, 'exportToPDF']);
Route::get('missions', [AssignmentElevatorController::class, 'missions']);
Route::get('getNames', [AssignmentElevatorController::class, 'getNames']);
Route::get('getEmployees', [AssignmentElevatorController::class, 'getEmployees']);
Route::post('information', [AssignmentElevatorController::class, 'getInformation']);
Route::post('informationEmployee', [AssignmentElevatorController::class, 'getEmployeeInfo']);
Route::get('getAssignment/{id}', [AssignmentElevatorController::class, 'getAssignment']);
Route::put('/update_asignment/{id}', [AssignmentElevatorController::class, 'update']);

//Elevator
Route::post('add_elevator', [ElevatorController::class, 'store']);
Route::get('elevators', [ElevatorController::class, 'index']);
Route::get('get_elevator/{id}', [ElevatorController::class, 'getElevator']);
Route::get('get_elevatoor/{id}', [ElevatorController::class, 'getOneElevator']);
Route::get('get_all', [ElevatorController::class, 'show']);
Route::delete('delete_elevator/{id}', [ElevatorController::class, 'destroy']);
Route::put('update_elevator/{id}', [ElevatorController::class, 'update']);
Route::get('export_elevators', [ElevatorController::class, 'export']);
Route::get('export_elevators_pdf', [ElevatorController::class, 'exportToPDF']);
//Location
Route::get('cities', [ElevatorController::class, 'cities']);
//Presence
Route::post('add_presence', [PresenceController::class, 'store']);
Route::get('presences', [PresenceController::class, 'index']);
//PresenceRegulation
Route::post('add_presence_regulation', [PresenceRegulationController::class, 'store']);
Route::get('presence_regulations', [PresenceRegulationsController::class, 'index']);
Route::put('reject_presence_regulation/{id}', [PresenceRegulationsController::class, 'updateStatusToRejected']);
Route::put('aprove_presence_regulation/{id}', [PresenceRegulationsController::class, 'updateStatusToApproved']);
Route::get('get_presence_regulation/{id}', [PresenceRegulationsController::class, 'getRegulation']);
Route::get('export_regulations', [PresenceRegulationsController::class, 'export']);
Route::get('export_regulations_pdf', [PresenceRegulationsController::class, 'exportToPDF']);
//Auth

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/sendOTP', [AuthController::class, 'sendOTP']);
Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']);
Route::post('/changePassword2', [AuthController::class, 'changePassword2']);
// Route::post('/getPresence', [AuthController::class, 'getPresence']);




Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/changePassword', [AuthController::class, 'changePassword']);
    Route::post('/addAttIssues', [AuthController::class, 'addAttIssues']);
    Route::post('/addSanitary', [AuthController::class, 'addSanitary']);
    Route::post('/getAssignmentElevator', [AuthController::class, 'getAssignmentElevator']);
    Route::post('/getPresence', [AuthController::class, 'getPresence']);

});

