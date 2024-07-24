<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::all();
        return response()->json($classes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $class = ClassRoom::create($request->all());
        return response()->json($class, 201);
    }

    public function destroy($id)
    {
        $class = ClassRoom::find($id);
        if ($class) {
            $class->delete();
            return response()->json(['message' => 'Class deleted successfully'], 200);
        }
        return response()->json(['message' => 'Class not found'], 404);
    }
}