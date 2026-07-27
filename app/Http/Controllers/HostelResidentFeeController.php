<?php

namespace App\Http\Controllers;

use App\Models\HostelFeePayment;
use App\Models\HostelResident;
use App\Models\School;
use Illuminate\Http\Request;

class HostelResidentFeeController extends Controller
{
    public function index(Request $request)
    {
        $query = HostelFeePayment::with(['resident']);

        if ($request->filled('resident_id')) {
            $query->where('hostel_resident_id', $request->resident_id);
        }

        if ($request->filled('billing_month')) {
            $query->where('billing_month', $request->billing_month);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $payments = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $residents = HostelResident::active()->orderBy('name')->get();

        $totalCollected = HostelFeePayment::sum('amount');
        $totalCollectedMonth = HostelFeePayment::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('hostel.fees.index', compact('payments', 'residents', 'totalCollected', 'totalCollectedMonth'));
    }

    public function create(Request $request)
    {
        $residents = HostelResident::active()->orderBy('name')->get();
        $selectedResident = null;

        if ($request->has('resident_id')) {
            $selectedResident = HostelResident::find($request->resident_id);
        }

        return view('hostel.fees.create', compact('residents', 'selectedResident'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostel_resident_id' => 'required|exists:hostel_residents,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'billing_month' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'payment_method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $payment = HostelFeePayment::create($validated);

        return redirect()->route('hostel.resident-fees.index')->with('success', 'Fee payment recorded successfully.');
    }

    public function edit($id)
    {
        $payment = HostelFeePayment::findOrFail($id);
        $residents = HostelResident::orderBy('name')->get();

        return view('hostel.fees.edit', compact('payment', 'residents'));
    }

    public function update(Request $request, $id)
    {
        $payment = HostelFeePayment::findOrFail($id);

        $validated = $request->validate([
            'hostel_resident_id' => 'required|exists:hostel_residents,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'billing_month' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'payment_method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('hostel.resident-fees.index')->with('success', 'Fee payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = HostelFeePayment::findOrFail($id);
        $payment->delete();

        return redirect()->route('hostel.resident-fees.index')->with('success', 'Fee payment entry deleted.');
    }

    public function receipt($id)
    {
        $payment = HostelFeePayment::with(['resident'])->findOrFail($id);

        return view('hostel.fees.receipt', compact('payment'));
    }
}
