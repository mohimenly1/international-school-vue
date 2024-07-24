<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\TeacherType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function showFinancial($id)
    {
        $employee = Employee::with(['salary','teacherType','department'])->findOrFail($id);
        return response()->json([
            'employee' => $employee,
            'salary' => $employee->salary,
        ]);
    }
    
    

    public function show($id)
    {
        $employee = Employee::with('department', 'teacherType')->findOrFail($id);
        return response()->json($employee);
    }

    public function getDepartmentsAndTeacherTypes()
    {
        $departments = Department::all();
        $teacherTypes = TeacherType::all();

        return response()->json([
            'departments' => $departments,
            'teacherTypes' => $teacherTypes
        ]);
    }
    public function index()
    {
        $employees = Employee::with(['department', 'teacherType'])->get();

        return response()->json($employees);
    }


// app/Http/Controllers/EmployeeController.php
// app/Http/Controllers/EmployeeController.php
// app/Http/Controllers/EmployeeController.php
public function store(Request $request)
{
    if ($request->input('teacher_type_id') === null) {
        $request->request->remove('teacher_type_id');
    }

    $request->validate([
        'name' => 'required|string',
        'department_id' => 'nullable|exists:departments,id',
        'teacher_type_id' => 'nullable|exists:teachers_type,id',
        'national_number' => 'required|string|unique:employees,national_number',
        'phone_number' => 'required|string',
        'phone_number_two' => 'nullable|string',
        'address' => 'required|string',
        'years_of_experience' => 'nullable|integer',
        'pin' => 'required|string|unique:employees,pin',
        'photos.*' => 'nullable|file|mimes:jpeg,png,jpg|max:2048' // Allow multiple photos
    ]);

    // Create a new Employee instance
    $employee = new Employee([
        'name' => $request->input('name'),
        'department_id' => $request->input('department_id'),
        'teacher_type_id' => $request->input('teacher_type_id'),
        'national_number' => $request->input('national_number'),
        'phone_number' => $request->input('phone_number'),
        'phone_number_two' => $request->input('phone_number_two'),
        'address' => $request->input('address'),
        'years_of_experience' => $request->input('years_of_experience'),
        'pin' => $request->input('pin'),
    ]);

    // Store multiple photo file paths in a JSON array
    $photos = [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $photos[] = $photo->store('employees', 'public');
        }
    }
    $employee->photos = json_encode($photos);

    // Save the employee record
    $employee->save();

    return response()->json(['message' => 'Employee created successfully', 'employee' => $employee]);
}




    
    


    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'teacher_type_id' => 'nullable|exists:teachers_type,id',
            'national_number' => 'required|string|unique:employees,national_number,' . $employee->id,
            'phone_number' => 'required|string',
            'phone_number_two' => 'nullable|string',
            'address' => 'required|string',
            'photos' => 'nullable|json',
            'years_of_experience' => 'nullable|integer',
        ]);

        $employee->fill($request->all());

        if ($request->hasFile('photos')) {
            $photos = json_decode($employee->photos, true) ?? [];
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('employees', 'public');
            }
            $employee->photos = json_encode($photos);
        }

        $employee->save();

        return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->photos) {
            foreach (json_decode($employee->photos, true) as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully']);
    }

    
}
