<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $classFilter = $request->input('class_id');
        $query = Subject::with(['class']);

        if ($classFilter) {
            $query->where('class_id', $classFilter);
        }

        $subjects = $query->orderBy('class_id')->orderBy('name')->paginate(20)->withQueryString();
        $classes = SchoolClass::where('status', 'active')->get();

        return view('subjects.index', compact('subjects', 'classes', 'classFilter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
        ]);

        Subject::create($validated + ['status' => 'active']);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $classes = SchoolClass::where('status', 'active')->get();
        return view('subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
            'status' => 'required|in:active,inactive',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }
}
