<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status', 'active'); // Default: Active students
        $classFilter = $request->input('class_id');
        $search = $request->input('search');

        $query = Student::with(['class']);

        // Filter by status
        if ($statusFilter === 'active') {
            $query->active();
        } elseif ($statusFilter === 'inactive') {
            $query->inactive();
        } // If status is 'all', do not apply status filter

        // Filter by class
        if ($classFilter) {
            $query->where('class_id', $classFilter);
        }

        // Search by name, father name, admission number, roll number
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('admission_number', 'like', "%{$search}%")
                  ->orWhere('roll_number', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();
        $classes = SchoolClass::where('status', 'active')->get();

        return view('students.index', compact('students', 'classes', 'statusFilter', 'classFilter', 'search'));
    }

    public function show($id)
    {
        // Load student with all related histories
        // Inactive students must retain all their records (Fee history, receipt history, marksheet history)
        $student = Student::with([
            'class',
            'admissions.class',
            'feeTransactions',
            'feeReceipts',
            'marksheets.exam'
        ])->findOrFail($id);

        return view('students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = SchoolClass::where('status', 'active')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'class_id' => 'required|exists:classes,id',
            'roll_number' => 'required|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string',
            'admission_date' => 'required|date',
            'admission_fee' => 'required|numeric|min:0',
            'monthly_fee' => 'required|numeric|min:0',
            'exam_fee' => 'required|numeric|min:0',
            'arrears' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('student_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $student->update($validated);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Student profile updated successfully.');
    }

    public function deactivate($id)
    {
        $student = Student::findOrFail($id);
        $student->update(['status' => 'inactive']);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Student status changed to INACTIVE. Historical records are preserved.');
    }

    public function reactivate($id)
    {
        $student = Student::findOrFail($id);
        $student->update(['status' => 'active']);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Student reactivated and status set to ACTIVE.');
    }
}
