<?php

namespace App\Http\Controllers;

use App\Models\HostelExpenditure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HostelController extends Controller
{
    protected $categories = [
        'expenditures' => [
            'name' => 'General Expenditures',
            'db_value' => 'expenditure',
            'icon' => 'fa-solid fa-money-bill-wave',
            'fields' => ['date', 'title', 'amount', 'payee_name', 'payment_method', 'reference_no', 'notes'],
            'headers' => ['Date', 'Title/Item', 'Amount', 'Vendor/Payee', 'Payment Method', 'Ref No.', 'Notes']
        ],
        'salaries' => [
            'name' => 'Staff Salaries',
            'db_value' => 'salary',
            'icon' => 'fa-solid fa-handshake',
            'fields' => ['date', 'payee_name', 'billing_month', 'amount', 'payment_method', 'notes'],
            'headers' => ['Date', 'Staff Name', 'Billing Month', 'Amount', 'Payment Method', 'Notes']
        ],
        'rent' => [
            'name' => 'Hostel Rent',
            'db_value' => 'rent',
            'icon' => 'fa-solid fa-house-chimney',
            'fields' => ['date', 'title', 'payee_name', 'billing_month', 'amount', 'payment_method', 'notes'],
            'headers' => ['Date', 'Building/Facility', 'Landlord', 'Billing Month', 'Amount', 'Payment Method', 'Notes']
        ],
        'electric-bill' => [
            'name' => 'Electric Bills',
            'db_value' => 'electric_bill',
            'icon' => 'fa-solid fa-bolt',
            'fields' => ['date', 'billing_month', 'reference_no', 'units_consumed', 'amount', 'payment_method', 'notes'],
            'headers' => ['Date', 'Billing Month', 'Bill/Consumer No.', 'Units Consumed', 'Amount', 'Payment Method', 'Notes']
        ],
        'other' => [
            'name' => 'Other Expenditures',
            'db_value' => 'other',
            'icon' => 'fa-solid fa-coins',
            'fields' => ['date', 'title', 'amount', 'payee_name', 'payment_method', 'reference_no', 'notes'],
            'headers' => ['Date', 'Title/Description', 'Amount', 'Payee', 'Payment Method', 'Ref No.', 'Notes']
        ]
    ];

    protected function getCategoryConfig($category)
    {
        if (!array_key_exists($category, $this->categories)) {
            abort(404, 'Category not found');
        }
        return $this->categories[$category];
    }

    public function dashboard()
    {
        // 1. Stats
        $activeResidents = \App\Models\HostelResident::active()->count();
        $totalResidents = \App\Models\HostelResident::count();
        
        $totalCollected = \App\Models\HostelFeePayment::sum('amount');
        $totalCollectedThisMonth = \App\Models\HostelFeePayment::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $totalExpenses = HostelExpenditure::sum('amount');
        $totalExpensesThisMonth = HostelExpenditure::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Category breakdown
        $expenseBreakdown = HostelExpenditure::select('category', \Illuminate\Support\Facades\DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Recent payments (limit 5)
        $recentPayments = \App\Models\HostelFeePayment::with('resident')->orderBy('date', 'desc')->take(5)->get();

        // Recent expenses (limit 5)
        $recentExpenses = HostelExpenditure::orderBy('date', 'desc')->take(5)->get();

        return view('hostel.dashboard', compact(
            'activeResidents', 'totalResidents', 
            'totalCollected', 'totalCollectedThisMonth',
            'totalExpenses', 'totalExpensesThisMonth',
            'expenseBreakdown', 'recentPayments', 'recentExpenses'
        ));
    }

    public function index(Request $request, $category)
    {
        $config = $this->getCategoryConfig($category);
        $dbValue = $config['db_value'];

        $query = HostelExpenditure::where('category', $dbValue);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('payee_name', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $expenditures = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // Calculations for stat cards
        $totalSpending = HostelExpenditure::where('category', $dbValue)->sum('amount');
        $monthSpending = HostelExpenditure::where('category', $dbValue)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
        $transactionCount = HostelExpenditure::where('category', $dbValue)->count();

        return view('hostel.index', compact('expenditures', 'category', 'config', 'totalSpending', 'monthSpending', 'transactionCount'));
    }

    public function create($category)
    {
        $config = $this->getCategoryConfig($category);
        return view('hostel.create', compact('category', 'config'));
    }

    public function store(Request $request, $category)
    {
        $config = $this->getCategoryConfig($category);
        $dbValue = $config['db_value'];

        $rules = [
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ];

        // Dynamic validation based on configuration requirements
        if (in_array('title', $config['fields'])) {
            $rules['title'] = 'required|string|max:255';
        }
        if (in_array('payee_name', $config['fields'])) {
            $rules['payee_name'] = 'required|string|max:255';
        }
        if (in_array('billing_month', $config['fields'])) {
            $rules['billing_month'] = 'required|string|regex:/^\d{4}-\d{2}$/';
        }
        if (in_array('reference_no', $config['fields'])) {
            if ($category === 'electric-bill') {
                $rules['reference_no'] = 'required|string|max:255';
            } else {
                $rules['reference_no'] = 'nullable|string|max:255';
            }
        }
        if (in_array('units_consumed', $config['fields'])) {
            $rules['units_consumed'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules);
        $validated['category'] = $dbValue;

        // Auto-assign titles for categories that don't have direct inputs
        if ($category === 'salaries') {
            $validated['title'] = "Salary for Month: " . Carbon::parse($validated['billing_month'])->format('F Y');
        } elseif ($category === 'electric-bill') {
            $validated['title'] = "Electricity Bill: " . Carbon::parse($validated['billing_month'])->format('F Y');
        }

        HostelExpenditure::create($validated);

        return redirect()->route('hostel.index', $category)->with('success', $config['name'] . ' recorded successfully.');
    }

    public function edit($category, $id)
    {
        $config = $this->getCategoryConfig($category);
        $expenditure = HostelExpenditure::findOrFail($id);

        if ($expenditure->category !== $config['db_value']) {
            abort(404);
        }

        return view('hostel.edit', compact('expenditure', 'category', 'config'));
    }

    public function update(Request $request, $category, $id)
    {
        $config = $this->getCategoryConfig($category);
        $expenditure = HostelExpenditure::findOrFail($id);

        if ($expenditure->category !== $config['db_value']) {
            abort(404);
        }

        $rules = [
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ];

        if (in_array('title', $config['fields'])) {
            $rules['title'] = 'required|string|max:255';
        }
        if (in_array('payee_name', $config['fields'])) {
            $rules['payee_name'] = 'required|string|max:255';
        }
        if (in_array('billing_month', $config['fields'])) {
            $rules['billing_month'] = 'required|string|regex:/^\d{4}-\d{2}$/';
        }
        if (in_array('reference_no', $config['fields'])) {
            if ($category === 'electric-bill') {
                $rules['reference_no'] = 'required|string|max:255';
            } else {
                $rules['reference_no'] = 'nullable|string|max:255';
            }
        }
        if (in_array('units_consumed', $config['fields'])) {
            $rules['units_consumed'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules);

        if ($category === 'salaries') {
            $validated['title'] = "Salary for Month: " . Carbon::parse($validated['billing_month'])->format('F Y');
        } elseif ($category === 'electric-bill') {
            $validated['title'] = "Electricity Bill: " . Carbon::parse($validated['billing_month'])->format('F Y');
        }

        $expenditure->update($validated);

        return redirect()->route('hostel.index', $category)->with('success', $config['name'] . ' updated successfully.');
    }

    public function destroy($category, $id)
    {
        $config = $this->getCategoryConfig($category);
        $expenditure = HostelExpenditure::findOrFail($id);

        if ($expenditure->category !== $config['db_value']) {
            abort(404);
        }

        $expenditure->delete();

        return redirect()->route('hostel.index', $category)->with('success', $config['name'] . ' deleted successfully.');
    }
}
