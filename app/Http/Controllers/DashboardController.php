<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Admission;
use App\Models\FeeReceipt;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $school = School::first();

        // 1. Dashboard Cards Data
        $activeStudentsCount = Student::active()->count();
        $inactiveStudentsCount = Student::inactive()->count();
        $classesCount = SchoolClass::count();
        
        $todayCollection = FeeReceipt::whereDate('date', now()->toDateString())->sum('paid_amount');
        $monthCollection = FeeReceipt::whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('paid_amount');
        
        // Arrears sum directly from student model (latest updated arrears state)
        $totalArrears = Student::sum('arrears');
        
        // Total admissions in current academic year/session
        $totalAdmissions = Admission::count();

        // 2. Chart 1: Monthly Fee Collection (Past 6 Months)
        $monthlyCollectionData = FeeReceipt::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('SUM(paid_amount) as amount')
        )
        ->where('date', '>=', now()->subMonths(6)->startOfMonth())
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $monthlyChartLabels = [];
        $monthlyChartValues = [];
        foreach ($monthlyCollectionData as $data) {
            $monthlyChartLabels[] = date('F', mktime(0, 0, 0, $data->month, 10));
            $monthlyChartValues[] = (float)$data->amount;
        }

        // 3. Chart 2: Class-wise Student Count (Active Students only)
        $classDistribution = SchoolClass::withCount(['students as active_count' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        $classChartLabels = $classDistribution->pluck('name')->toArray();
        $classChartValues = $classDistribution->pluck('active_count')->toArray();

        // 4. Chart 3: Fee Collection Summary Breakdown (Admission vs Monthly vs Exam vs Arrears)
        $feeBreakdown = FeeReceipt::select(
            DB::raw('SUM(admission_fee) as admission'),
            DB::raw('SUM(monthly_fee) as monthly'),
            DB::raw('SUM(exam_fee) as exam'),
            DB::raw('SUM(previous_arrears - remaining_arrears) as recovered_arrears'), // Arrears portion collected
            DB::raw('SUM(paid_amount) as total')
        )->first();

        $feeSummary = [
            'admission' => (float)($feeBreakdown->admission ?? 0.0),
            'monthly' => (float)($feeBreakdown->monthly ?? 0.0),
            'exam' => (float)($feeBreakdown->exam ?? 0.0),
            'arrears' => (float)max(0, ($feeBreakdown->recovered_arrears ?? 0.0)),
            'total' => (float)($feeBreakdown->total ?? 0.0),
        ];

        return view('dashboard.index', compact(
            'school',
            'activeStudentsCount',
            'inactiveStudentsCount',
            'classesCount',
            'todayCollection',
            'monthCollection',
            'totalArrears',
            'totalAdmissions',
            'monthlyChartLabels',
            'monthlyChartValues',
            'classChartLabels',
            'classChartValues',
            'feeSummary'
        ));
    }

    public function settings()
    {
        $school = School::first();
        return view('settings.index', compact('school'));
    }

    public function updateSettings(Request $request)
    {
        $school = School::first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'principal_name' => 'required|string|max:255',
            'academic_session' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('school_logos', 'public');
            $validated['logo'] = $logoPath;
        }

        if ($school) {
            $school->update($validated);
        } else {
            School::create($validated);
        }

        return redirect()->route('settings.index')->with('success', 'School settings updated successfully.');
    }
}
