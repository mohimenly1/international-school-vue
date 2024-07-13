<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LogCheckInProcessController;
use App\Http\Controllers\ParentInfoController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
  
});
Route::post('/check-in', [LogCheckInProcessController::class, 'checkInOrOut']);
Route::post('/check-out', [LogCheckInProcessController::class, 'checkOut']);
Route::apiResource('students', StudentController::class);
Route::apiResource('parents', ParentInfoController::class);
Route::apiResource('classes', SchoolClassController::class);
Route::apiResource('buses', BusController::class);
Route::apiResource('users', UserController::class)->only(['index', 'store']);
Route::post('/employees/{employeeId}/set-salary', [SalaryController::class, 'setSalary']);
Route::post('/employees/{employeeId}/adjust-salary', [SalaryController::class, 'adjustSalary']);
Route::get('/subscribers', [SubscriberController::class, 'index']);
Route::post('/employees/{employeeId}/calculate-absences', [SalaryController::class, 'calculateAbsences']);
Route::get('/employees/salary/{id}', [EmployeeController::class, 'showFinancial']);
Route::post('/subscribers', [SubscriberController::class, 'store']);
Route::get('/salaries/{employeeId}', [SalaryController::class, 'show']);

Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees', [EmployeeController::class, 'store']);
Route::get('/employees/{id}', [EmployeeController::class, 'show']);
Route::put('/employees/{id}', [EmployeeController::class, 'update']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
Route::get('/departments-teacher-types', [EmployeeController::class, 'getDepartmentsAndTeacherTypes']);
Route::post('/login', [AuthController::class, 'login']);