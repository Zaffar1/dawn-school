<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ClassController extends Controller
{
    public function index()
    {
        // Get classes with count of active students
        $classes = SchoolClass::withCount(['students as active_students_count' => function ($query) {
            $query->where('status', 'active');
        }])
        ->withCount(['students as inactive_students_count' => function ($query) {
            $query->where('status', 'inactive');
        }])
        ->orderBy('id')
        ->paginate(15);

        return view('classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
        ]);

        SchoolClass::create([
            'name' => $validated['name'],
            'status' => 'active',
        ]);

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function edit($id)
    {
        $class = SchoolClass::findOrFail($id);
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
            'status' => 'required|in:active,inactive',
        ]);

        $class->update($validated);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    /**
     * Show students belonging to a class.
     * Accessible via Route /classes/{id}/students
     */
    public function students(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);
        $statusFilter = $request->input('status', 'active'); // Default: Active students
        $search = $request->input('search');

        $query = Student::where('class_id', $class->id);

        if ($statusFilter === 'active') {
            $query->active();
        } elseif ($statusFilter === 'inactive') {
            $query->inactive();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('admission_number', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('classes.students', compact('class', 'students', 'statusFilter', 'search'));
    }
}
