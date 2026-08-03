@extends('layouts.app')

@section('title', 'Sukkur Hostel Dashboard')

@section('content')
<div class="page-title-box">
    <div>
        <h3 class="mb-1"><i class="fa-solid fa-hotel me-2 text-primary"></i>Sukkur Hostel</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Sukkur Hostel</li>
                <li class="breadcrumb-item active" aria-current="page">Control Panel</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Financial Summary Row -->
<div class="row g-4 mb-4">
    <!-- Active Students Card -->
    <div class="col-md-3">
        <div class="card-stat bg-white h-100">
            <div class="icon-box bg-primary-subtle text-primary">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="stat-value">{{ $activeResidents }} <span class="fs-6 text-muted font-monospace">/ {{ $totalResidents }}</span></div>
            <div class="stat-label">Active Students/Staff</div>
        </div>
    </div>

    <!-- Inbound Fees Card -->
    <div class="col-md-3">
        <div class="card-stat bg-white h-100">
            <div class="icon-box bg-success-subtle text-success">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalCollectedThisMonth, 0) }}</div>
            <div class="stat-label">Inbound Fees (This Month)</div>
            <div class="text-muted small mt-2">Rs. {{ number_format($totalCollected, 0) }} (All-Time)</div>
        </div>
    </div>

    <!-- Outbound Expenditures Card -->
    <div class="col-md-3">
        <div class="card-stat bg-white h-100">
            <div class="icon-box bg-danger-subtle text-danger">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalExpensesThisMonth, 0) }}</div>
            <div class="stat-label">Outbound Costs (This Month)</div>
            <div class="text-muted small mt-2">Rs. {{ number_format($totalExpenses, 0) }} (All-Time)</div>
        </div>
    </div>

    <!-- Balance Status Card -->
    @php
        $netBalance = $totalCollected - $totalExpenses;
        $netMonthBalance = $totalCollectedThisMonth - $totalExpensesThisMonth;
        $balanceColor = $netBalance >= 0 ? 'success' : 'danger';
        $balanceBg = $netBalance >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
        $balanceIcon = $netBalance >= 0 ? 'fa-solid fa-arrow-trend-up' : 'fa-solid fa-arrow-trend-down';
    @endphp
    <div class="col-md-3">
        <div class="card-stat bg-white h-100 border-{{ $balanceColor }}">
            <div class="icon-box {{ $balanceBg }}">
                <i class="{{ $balanceIcon }}"></i>
            </div>
            <div class="stat-value text-{{ $balanceColor }}">Rs. {{ number_format($netBalance, 0) }}</div>
            <div class="stat-label">Net Operational Balance</div>
            <div class="small text-{{ $balanceColor }} fw-semibold mt-2">
                Monthly: {{ $netMonthBalance >= 0 ? '+' : '' }}Rs. {{ number_format($netMonthBalance, 0) }}
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts -->
<div class="card-box mb-4">
    <h5 class="text-dark fw-bold mb-3"><i class="fa-solid fa-circle-nodes text-primary me-2"></i>Quick Action Shortcuts</h5>
    <div class="row g-3">
        <div class="col-6 col-lg-3 col-xl-2">
            <a href="{{ route('hostel.residents.create') }}" class="btn btn-outline-primary py-3 w-100 text-center h-100 d-flex flex-column align-items-center justify-content-center border-dashed rounded-3">
                <i class="fa-solid fa-user-plus fs-4 mb-2"></i>
                <span class="small fw-semibold">Add Student</span>
            </a>
        </div>
        <div class="col-6 col-lg-3 col-xl-2">
            <a href="{{ route('hostel.resident-fees.create') }}" class="btn btn-outline-success py-3 w-100 text-center h-100 d-flex flex-column align-items-center justify-content-center border-dashed rounded-3">
                <i class="fa-solid fa-hand-holding-dollar fs-4 mb-2"></i>
                <span class="small fw-semibold">Collect Fee</span>
            </a>
        </div>
        <div class="col-6 col-lg-3 col-xl-2">
            <a href="{{ route('hostel.create', 'salaries') }}" class="btn btn-outline-info py-3 w-100 text-center h-100 d-flex flex-column align-items-center justify-content-center border-dashed rounded-3">
                <i class="fa-solid fa-handshake fs-4 mb-2"></i>
                <span class="small fw-semibold">Record Salary</span>
            </a>
        </div>
        <div class="col-6 col-lg-3 col-xl-2">
            <a href="{{ route('hostel.create', 'rent') }}" class="btn btn-outline-warning py-3 w-100 text-center h-100 d-flex flex-column align-items-center justify-content-center border-dashed rounded-3">
                <i class="fa-solid fa-house-chimney fs-4 mb-2"></i>
                <span class="small fw-semibold">Pay Hostel Rent</span>
            </a>
        </div>
        <div class="col-6 col-lg-3 col-xl-2">
            <a href="{{ route('hostel.create', 'electric-bill') }}" class="btn btn-outline-danger py-3 w-100 text-center h-100 d-flex flex-column align-items-center justify-content-center border-dashed rounded-3">
                <i class="fa-solid fa-bolt fs-4 mb-2"></i>
                <span class="small fw-semibold">Electric Bill</span>
            </a>
        </div>
        <div class="col-6 col-lg-3 col-xl-2">
            <a href="{{ route('hostel.create', 'expenditures') }}" class="btn btn-outline-secondary py-3 w-100 text-center h-100 d-flex flex-column align-items-center justify-content-center border-dashed rounded-3">
                <i class="fa-solid fa-cart-shopping fs-4 mb-2"></i>
                <span class="small fw-semibold">Log Expense</span>
            </a>
        </div>
    </div>
</div>

<!-- Ledger Splitting: Inbound vs Outbound Activities -->
<div class="row g-4 mb-4">
    <!-- Inbound Fees split -->
    <div class="col-xl-6">
        <div class="card-box h-100">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h5 class="text-dark fw-bold mb-0"><i class="fa-solid fa-file-invoice-dollar text-success me-2"></i>Recent Fee Collections</h5>
                <a href="{{ route('hostel.resident-fees.index') }}" class="btn btn-link btn-sm p-0 text-decoration-none">View All Ledger</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle" style="font-size: 0.9rem;">
                    <thead>
                        <tr class="table-light">
                            <th>Student</th>
                            <th>Room</th>
                            <th>Month</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td><span class="fw-semibold text-dark">{{ $payment->resident->name ?? 'Removed' }}</span></td>
                                <td><code>{{ $payment->resident->room_number ?? '-' }}</code></td>
                                <td>{{ \Carbon\Carbon::parse($payment->billing_month)->format('M Y') }}</td>
                                <td class="text-end fw-bold text-success">Rs. {{ number_format($payment->amount, 0) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('hostel.resident-fees.receipt', $payment->id) }}" target="_blank" class="btn btn-outline-info btn-xs py-0.5 px-2" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No recent payments logged.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Outbound Expenditures split -->
    <div class="col-xl-6">
        <div class="card-box h-100">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h5 class="text-dark fw-bold mb-0"><i class="fa-solid fa-money-bill-transfer text-danger me-2"></i>Recent Outbound Cost Logs</h5>
                <div class="dropdown">
                    <button class="btn btn-link btn-sm p-0 text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        View Category
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="{{ route('hostel.index', 'expenditures') }}">General Exp.</a></li>
                        <li><a class="dropdown-item" href="{{ route('hostel.index', 'salaries') }}">Salaries</a></li>
                        <li><a class="dropdown-item" href="{{ route('hostel.index', 'rent') }}">Rent</a></li>
                        <li><a class="dropdown-item" href="{{ route('hostel.index', 'electric-bill') }}">Electric Bills</a></li>
                        <li><a class="dropdown-item" href="{{ route('hostel.index', 'other') }}">Other Exp.</a></li>
                    </ul>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle" style="font-size: 0.9rem;">
                    <thead>
                        <tr class="table-light">
                            <th>Category</th>
                            <th>Description/Vendor</th>
                            <th>Date</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExpenses as $exp)
                            @php
                                $badgeColor = [
                                    'expenditure' => 'bg-secondary-subtle text-secondary',
                                    'salary' => 'bg-info-subtle text-info border border-info-subtle',
                                    'rent' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                    'electric_bill' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    'other' => 'bg-dark-subtle text-dark border',
                                ][$exp->category] ?? 'bg-light text-dark';
                                $categoryName = [
                                    'expenditure' => 'General',
                                    'salary' => 'Salary',
                                    'rent' => 'Rent',
                                    'electric_bill' => 'Electric',
                                    'other' => 'Other',
                                ][$exp->category] ?? ucfirst($exp->category);
                            @endphp
                            <tr>
                                <td><span class="badge {{ $badgeColor }}">{{ $categoryName }}</span></td>
                                <td><span class="text-dark fw-semibold">{{ $exp->payee_name ?? $exp->title }}</span></td>
                                <td>{{ $exp->date->format('d-M-y') }}</td>
                                <td class="text-end fw-bold text-danger">Rs. {{ number_format($exp->amount, 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No recent cost items logged.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Expenditure Distribution Analysis -->
<div class="card-box">
    <h5 class="text-dark fw-bold mb-3"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Outbound Expenditures Distribution</h5>
    @php
        $generalSpent = $expenseBreakdown['expenditure'] ?? 0.0;
        $salariesSpent = $expenseBreakdown['salary'] ?? 0.0;
        $rentSpent = $expenseBreakdown['rent'] ?? 0.0;
        $electricitySpent = $expenseBreakdown['electric_bill'] ?? 0.0;
        $otherSpent = $expenseBreakdown['other'] ?? 0.0;

        $totalVal = $generalSpent + $salariesSpent + $rentSpent + $electricitySpent + $otherSpent;
        
        $generalPercent = $totalVal > 0 ? ($generalSpent / $totalVal) * 100 : 0;
        $salariesPercent = $totalVal > 0 ? ($salariesSpent / $totalVal) * 100 : 0;
        $rentPercent = $totalVal > 0 ? ($rentSpent / $totalVal) * 100 : 0;
        $electricityPercent = $totalVal > 0 ? ($electricitySpent / $totalVal) * 100 : 0;
        $otherPercent = $totalVal > 0 ? ($otherSpent / $totalVal) * 100 : 0;
    @endphp

    @if($totalVal > 0)
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <!-- Stacked Progress Bar -->
                <div class="progress rounded-4 mb-3" style="height: 24px;">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $generalPercent }}%" title="General Expenditures"></div>
                    <div class="progress-bar bg-info text-dark" role="progressbar" style="width: {{ $salariesPercent }}%" title="Staff Salaries"></div>
                    <div class="progress-bar bg-warning text-dark" role="progressbar" style="width: {{ $rentPercent }}%" title="Building Rent"></div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $electricityPercent }}%" title="Electricity Utility"></div>
                    <div class="progress-bar bg-dark" role="progressbar" style="width: {{ $otherPercent }}%" title="Other Costs"></div>
                </div>

                <!-- Info Grid -->
                <div class="row g-2">
                    <div class="col-sm-6">
                        <span class="d-inline-block rounded-circle bg-secondary me-2" style="width:10px; height:10px;"></span>
                        <span class="small">General: <strong>Rs. {{ number_format($generalSpent, 0) }}</strong> ({{ number_format($generalPercent, 1) }}%)</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="d-inline-block rounded-circle bg-info me-2" style="width:10px; height:10px;"></span>
                        <span class="small">Salaries: <strong>Rs. {{ number_format($salariesSpent, 0) }}</strong> ({{ number_format($salariesPercent, 1) }}%)</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="d-inline-block rounded-circle bg-warning me-2" style="width:10px; height:10px;"></span>
                        <span class="small">Rent: <strong>Rs. {{ number_format($rentSpent, 0) }}</strong> ({{ number_format($rentPercent, 1) }}%)</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="d-inline-block rounded-circle bg-danger me-2" style="width:10px; height:10px;"></span>
                        <span class="small">Electricity: <strong>Rs. {{ number_format($electricitySpent, 0) }}</strong> ({{ number_format($electricityPercent, 1) }}%)</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="d-inline-block rounded-circle bg-dark me-2" style="width:10px; height:10px;"></span>
                        <span class="small">Other Exp: <strong>Rs. {{ number_format($otherSpent, 0) }}</strong> ({{ number_format($otherPercent, 1) }}%)</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center border-start d-none d-lg-block">
                <div class="p-3">
                    <div class="text-muted small fw-semibold text-uppercase">Total Ledger Outflow</div>
                    <h2 class="text-primary fw-bold font-monospace mt-1">Rs. {{ number_format($totalVal, 2) }}</h2>
                    <span class="badge bg-secondary-subtle text-secondary border px-3 py-1.5 mt-2">Operational Outflows</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-chart-line fs-2 mb-2 text-black-50"></i>
            No cost distributions to analyze yet.
        </div>
    @endif
</div>
@endsection
