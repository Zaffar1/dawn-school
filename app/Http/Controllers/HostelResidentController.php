<?php

namespace App\Http\Controllers;

use App\Models\HostelResident;
use Illuminate\Http\Request;

class HostelResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = HostelResident::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('room_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $residents = $query->orderBy('status', 'asc') // active first
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Statistics
        $totalActive = HostelResident::active()->count();
        $totalInactive = HostelResident::inactive()->count();
        $totalCollectedThisMonth = \App\Models\HostelFeePayment::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('hostel.residents.index', compact('residents', 'totalActive', 'totalInactive', 'totalCollectedThisMonth'));
    }

    public function create()
    {
        return view('hostel.residents.create');
    }

    public function store(Request $request)
    {
        if ($request->phone === '+92') {
            $request->merge(['phone' => null]);
        }

        if ($request->filled('phone')) {
            $request->merge([
                'phone' => preg_replace('/[^0-9+]/', '', $request->phone),
            ]);
        }

        $validated = $request->validate([
            'resident_type' => 'required|in:resident,staff',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|regex:/^((\+92)|(92)|(0))3\d{9}$/',
            'room_number' => 'required|string|max:50',
            'monthly_fee' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ], [
            'phone.regex' => 'The phone number must be a valid Pakistani mobile number (e.g., 03001234567 or +923001234567).',
        ]);

        HostelResident::create($validated);

        return redirect()->route('hostel.residents.index')->with('success', 'Hostel person registered successfully.');
    }

    public function edit($id)
    {
        $resident = HostelResident::findOrFail($id);
        return view('hostel.residents.edit', compact('resident'));
    }

    public function update(Request $request, $id)
    {
        $resident = HostelResident::findOrFail($id);

        if ($request->phone === '+92') {
            $request->merge(['phone' => null]);
        }

        if ($request->filled('phone')) {
            $request->merge([
                'phone' => preg_replace('/[^0-9+]/', '', $request->phone),
            ]);
        }

        $validated = $request->validate([
            'resident_type' => 'required|in:resident,staff',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|regex:/^((\+92)|(92)|(0))3\d{9}$/',
            'room_number' => 'required|string|max:50',
            'monthly_fee' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'leaving_date' => 'nullable|date|after_or_equal:joining_date',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ], [
            'phone.regex' => 'The phone number must be a valid Pakistani mobile number (e.g., 03001234567 or +923001234567).',
        ]);

        // Set leaving date automatically if deactivated and no leaving date specified
        if ($validated['status'] === 'inactive' && empty($validated['leaving_date'])) {
            $validated['leaving_date'] = now()->toDateString();
        } elseif ($validated['status'] === 'active') {
            $validated['leaving_date'] = null;
        }

        $resident->update($validated);

        return redirect()->route('hostel.residents.index')->with('success', 'Hostel person details updated.');
    }

    public function destroy($id)
    {
        $resident = HostelResident::findOrFail($id);
        $resident->delete();

        return redirect()->route('hostel.residents.index')->with('success', 'Hostel person removed from records.');
    }
}
