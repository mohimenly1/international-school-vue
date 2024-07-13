<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'parent', 'class', 'bus'])->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'parent_id' => 'nullable|exists:users,id',
            'class_id' => 'nullable|exists:classes,id',
            'bus_id' => 'nullable|exists:buses,id',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
        ]);

        $student = Student::create($request->all());

        return response()->json($student, 201);
    }
}
