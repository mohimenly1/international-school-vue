<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::with('subjects');

        if ($request->has('class_id')) {
            $classId = $request->query('class_id');
            $query->where('class_id', $classId);
        }

        $sections = $query->get();

        return response()->json($sections);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'array|exists:teachers_type,id'
        ]);

        $section = Section::create($request->only('name', 'class_id'));

        if ($request->has('subject_ids')) {
            $section->subjects()->attach($request->subject_ids);
        }

        return response()->json($section->load('subjects'), 201);
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'array|exists:teachers_type,id'
        ]);

        $section->update($request->only('name', 'class_id'));

        if ($request->has('subject_ids')) {
            $section->subjects()->sync($request->subject_ids);
        }

        return response()->json($section->load('subjects'));
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return response()->noContent();
    }
}
