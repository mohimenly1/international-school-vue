<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LogCheckInProcessController;
use App\Http\Controllers\ParentInfoController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionFeeController;
use App\Http\Controllers\TeacherTypeController;
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
Route::apiResource('buses', BusController::class);
Route::apiResource('users', UserController::class)->only(['index', 'store']);
Route::post('/employees/{employeeId}/set-salary', [SalaryController::class, 'setSalary']);
Route::post('/employees/{employeeId}/adjust-salary', [SalaryController::class, 'adjustSalary']);
Route::apiResource('/subscribers', SubscriberController::class);
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


Route::get('/classes', [SchoolClassController::class, 'index']);
Route::post('/classes', [SchoolClassController::class, 'store']);
Route::delete('/classes/{id}', [SchoolClassController::class, 'destroy']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/exams',ExamController::class);
Route::get('/teacher-types', [TeacherTypeController::class, 'index']);
Route::post('/teacher-types', [TeacherTypeController::class, 'store']);
Route::put('/teacher-types/{id}', [TeacherTypeController::class, 'update']);
Route::delete('/teacher-types/{id}', [TeacherTypeController::class, 'destroy']);



Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/departments', [DepartmentController::class, 'store']);
Route::put('/departments/{id}', [DepartmentController::class, 'update']);
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);


Route::apiResource('sections', SectionController::class);
Route::get('/subjects', [TeacherTypeController::class, 'index']);
Route::apiResource('/subscription-fees',SubscriptionFeeController::class);
Route::post('/students/{student}/subscription-fees', [SubscriptionFeeController::class, 'storeSubscriptionFees']);

// Route::get('/buses', [BusController::class, 'index']);
// Route::post('/buses', [BusController::class, 'store']);
// Route::put('/buses/{id}', [BusController::class, 'update']);
// Route::delete('/buses/{id}', [BusController::class, 'destroy']);