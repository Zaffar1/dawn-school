<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\School;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $classFilter = $request->input('class_id');
        $query = Exam::with(['class']);

        if ($classFilter) {
            $query->where('class_id', $classFilter);
        }

        $exams = $query->orderBy('start_date', 'desc')->paginate(15)->withQueryString();
        $classes = SchoolClass::where('status', 'active')->get();
        $school = School::first();
        
        $defaultSession = $school ? $school->academic_session : '2026-2027';

        return view('exams.index', compact('exams', 'classes', 'classFilter', 'defaultSession'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'academic_session' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Exam::create($validated + ['status' => 'active']);

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $classes = SchoolClass::where('status', 'active')->get();
        return view('exams.edit', compact('exam', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'academic_session' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $exam->update($validated);

        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }
}
