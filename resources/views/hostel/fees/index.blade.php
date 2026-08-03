@extends('layouts.app')

@section('title', 'Hostel Fee Collection Ledger')

@section('content')
<div class="page-title-box d-flex align-items-center justify-content-between">
    <div>
        <h3 class="mb-1"><i class="fa-solid fa-file-invoice-dollar me-2 text-primary"></i>Hostel Fee Ledger</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.dashboard') }}">Sukkur Hostel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fee Ledger</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('hostel.resident-fees.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-hand-holding-dollar me-1"></i> Collect Fee Payment
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card-stat bg-white">
            <div class="icon-box bg-success-subtle text-success">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalCollected, 2) }}</div>
            <div class="stat-label">Total Fee Collection (All-Time)</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-stat bg-white">
            <div class="icon-box bg-primary-subtle text-primary">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalCollectedMonth, 2) }}</div>
            <div class="stat-label">Fee Collection (This Month)</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card-box mb-4">
    <form method="GET" action="{{ route('hostel.resident-fees.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">Filter by Student/Staff</label>
                <select name="resident_id" class="form-select">
                    <option value="">-- All Students/Staff --</option>
                    @foreach($residents as $res)
                        <option value="{{ $res->id }}" {{ request('resident_id') == $res->id ? 'selected' : '' }}>
                            {{ $res->name }} (Room: {{ $res->room_number }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold">Billing Month</label>
                <input type="month" name="billing_month" class="form-control" value="{{ request('billing_month') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">Payment Date Range</label>
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center pb-2" style="height: 38px;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="has_arrears" id="has_arrears" value="1" {{ request('has_arrears') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label small fw-semibold text-muted" for="has_arrears">
                        Show Arrears Only
                    </label>
                </div>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            </div>
        </div>
    </form>
</div>

<!-- Ledger Table -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Receipt No.</th>
                    <th>Student Name</th>
                    <th>Room Assigned</th>
                    <th>Billing Month</th>
                    <th class="text-end">Due Amount</th>
                    <th class="text-end">Paid Amount</th>
                    <th class="text-end">Arrears</th>
                    <th>Payment Date</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th class="text-end" style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $pay)
                    <tr>
                        <td><code>#H-{{ str_pad($pay->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                        <td><span class="fw-semibold text-dark">{{ $pay->resident->name ?? 'Removed Student' }}</span></td>
                        <td><span class="fw-bold"><i class="fa-solid fa-door-open text-muted me-1"></i>{{ $pay->resident->room_number ?? '-' }}</span></td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1.5">
                                {{ \Carbon\Carbon::parse($pay->billing_month)->format('F Y') }}
                            </span>
                        </td>
                        <td class="text-end fw-semibold text-secondary">Rs. {{ number_format($pay->due_amount ?? ($pay->resident->monthly_fee ?? 0.00), 2) }}</td>
                        <td class="text-end fw-bold text-success">Rs. {{ number_format($pay->amount, 2) }}</td>
                        <td class="text-end fw-bold {{ ($pay->arrears ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">Rs. {{ number_format($pay->arrears ?? 0.00, 2) }}</td>
                        <td>{{ $pay->date->format('d-M-Y') }}</td>
                        <td><i class="fa-solid fa-wallet text-muted me-1 small"></i>{{ $pay->payment_method }}</td>
                        <td><code>{{ $pay->reference_no ?? '-' }}</code></td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('hostel.resident-fees.receipt', $pay->id) }}" target="_blank" class="btn btn-outline-info btn-sm" title="Print Fee Receipt">
                                    <i class="fa-solid fa-print"></i> Print
                                </a>
                                <a href="{{ route('hostel.resident-fees.edit', $pay->id) }}" class="btn btn-outline-primary btn-sm ms-1" title="Edit details">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('hostel.resident-fees.destroy', $pay->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm ms-1" title="Delete payment record">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-receipt fs-2 mb-3 d-block text-black-50"></i>
                            No fee collections registered.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection
