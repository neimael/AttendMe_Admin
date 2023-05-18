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
use Maatwebsite\Excel\Row;

    // Your restricted routes go here

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
Route::get('get_admin/{id}', [AdminController::class, 'getAdmin']);
Route::put('update_admin/{id}', [AdminController::class, 'update']);
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
Route::post('addManualPresence', [PresenceController::class, 'store']);
Route::get('presences', [PresenceController::class, 'index']);
Route::get('get_presence/{id}', [PresenceController::class, 'getPresence']);
Route::get('export_presences', [PresenceController::class, 'export']);
Route::get('export_presences_pdf', [PresenceController::class, 'exportToPDF']);
Route::get('get_employee_presence/{id}', [PresenceController::class, 'getPresenceByIdEmployee']);
Route::get('export_employee_presence_pdf/{id}', [PresenceController::class, 'singleexportToPDF']);
Route::get('export_employee_presence/{id}', [PresenceController::class, 'singleexport']);
//PresenceRegulation
Route::post('add_presence_regulation', [PresenceRegulationController::class, 'store']);
Route::get('presence_regulations', [PresenceRegulationsController::class, 'index']);
Route::put('reject_presence_regulation/{id}', [PresenceRegulationsController::class, 'updateStatusToRejected']);
Route::put('aprove_presence_regulation/{id}', [PresenceRegulationsController::class, 'updateStatusToApproved']);
Route::get('get_presence_regulation/{id}', [PresenceRegulationsController::class, 'getRegulation']);
Route::get('export_regulations', [PresenceRegulationsController::class, 'export']);
Route::get('export_regulations_pdf', [PresenceRegulationsController::class, 'exportToPDF']);
//Auth 
Route::post('/logoutAdmin', [AdminController::class, 'logoutAdmin']);
Route::put('update_profile/{id}', [AdminController::class, 'updateProfile']);
Route::put('update_password/{id}', [AdminController::class, 'updatePassword']);
Route::middleware('auth:admin')->get('/getAuthenticatedAdmin', [AdminController::class, 'getAuthenticatedAdmin']);
//login admin
Route::post('/loginAdmin', [AdminController::class, 'loginAdmin']);
//Auth

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/sendOTP', [AuthController::class, 'sendOTP']);
Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']);
Route::post('/changePassword2', [AuthController::class, 'changePassword2']);
// Route::post('/getPresence', [AuthController::class, 'getPresence']);
// Route::post('/getIdPresence',[AuthController::class,'getIdPresence']);    


// Route::post('/getPresenceById', [AuthController::class, 'getPresenceById']);



Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/changePassword', [AuthController::class, 'changePassword']);
    Route::post('/addAttIssues', [AuthController::class, 'addAttIssues']);
    Route::post('/addSanitary', [AuthController::class, 'addSanitary']);
    Route::post('/getAssignmentElevator', [AuthController::class, 'getAssignmentElevator']);
    Route::post('/getPresence', [AuthController::class, 'getPresence']);
    Route::post('/getPresenceById', [AuthController::class, 'getPresenceById']);
    Route::post('/addPresence',[AuthController::class, 'addPresence']);
    Route::post('/getIdPresence',[AuthController::class,'getIdPresence']);    
    Route::put('/updatePresence',[AuthController::class,'updatePresence']);    
    Route::post('/getPresenceByIdEmp',[PresenceController::class,'getPresenceByIdEmp']);    

});

