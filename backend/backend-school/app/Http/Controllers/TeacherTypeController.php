<?php

namespace App\Http\Controllers;

use App\Models\TeacherType;
use Illuminate\Http\Request;

class TeacherTypeController extends Controller
{
    public function index()
    {
        $teacherTypes = TeacherType::all();
        return response()->json($teacherTypes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
        ]);

        $teacherType = TeacherType::create($request->all());
        return response()->json($teacherType, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
        ]);

        $teacherType = TeacherType::find($id);
        if ($teacherType) {
            $teacherType->update($request->all());
            return response()->json($teacherType, 200);
        }
        return response()->json(['message' => 'Teacher type not found'], 404);
    }

    public function destroy($id)
    {
        $teacherType = TeacherType::find($id);
        if ($teacherType) {
            $teacherType->delete();
            return response()->json(['message' => 'Teacher type deleted successfully'], 200);
        }
        return response()->json(['message' => 'Teacher type not found'], 404);
    }
}
