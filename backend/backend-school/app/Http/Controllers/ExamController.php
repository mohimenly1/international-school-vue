<?php

// ExamController.php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with('sections.subjects');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('sections', function($query) use ($search) {
                      $query->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('sections.subjects', function($query) use ($search) {
                      $query->where('type', 'LIKE', "%{$search}%");
                  });
        }

        $exams = $query->paginate(10);

        return response()->json($exams);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subjects' => 'required|array',
            'subjects.*.id' => 'required|exists:teachers_type,id',
            'subjects.*.exam_date' => 'required|date'
        ]);

        $exam = Exam::create($request->only('name', 'description'));

        foreach ($request->subjects as $subject) {
            DB::table('exam_section_subject')->insert([
                'exam_id' => $exam->id,
                'section_id' => $request->section_id,
                'subject_id' => $subject['id'],
                'exam_date' => date('Y-m-d', strtotime($subject['exam_date'])) // Format date
            ]);
        }

        return response()->json($exam->load('sections.subjects'), 201);
    }
}
