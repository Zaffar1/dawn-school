@extends('layouts.app')

@section('title', $config['name'])

@section('content')
<div class="page-title-box d-flex align-items-center justify-content-between">
    <div>
        <h3 class="mb-1"><i class="{{ $config['icon'] }} me-2 text-primary"></i>{{ $config['name'] }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.dashboard') }}">Sukkur Hostel</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $config['name'] }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('hostel.create', $category) }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            @if($category === 'salaries')
                Record Salary Payment
            @elseif($category === 'rent')
                Record Rent Payment
            @elseif($category === 'electric-bill')
                Record Electric Bill
            @elseif($category === 'expenditures')
                Record Expense Entry
            @else
                Record {{ $config['name'] }}
            @endif
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-stat bg-white">
            <div class="icon-box bg-primary-subtle text-primary">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalSpending, 2) }}</div>
            <div class="stat-label">Total spent (All-Time)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat bg-white">
            <div class="icon-box bg-success-subtle text-success">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($monthSpending, 2) }}</div>
            <div class="stat-label">Spent This Month</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat bg-white">
            <div class="icon-box bg-info-subtle text-info">
                <i class="fa-solid fa-list-ol"></i>
            </div>
            <div class="stat-value">{{ $transactionCount }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card-box mb-4">
    <form method="GET" action="{{ route('hostel.index', $category) }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">Search keyword</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by title, payee, or reference..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
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
                    @foreach($config['headers'] as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                    <th class="text-end" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenditures as $item)
                    <tr>
                        <!-- Date -->
                        <td><span class="fw-semibold text-dark">{{ $item->date->format('d-M-Y') }}</span></td>
                        
                        <!-- Title (General/Rent/Other) -->
                        @if(in_array('title', $config['fields']))
                            <td>{{ $item->title }}</td>
                        @endif

                        <!-- Amount -->
                        <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fw-bold">Rs. {{ number_format($item->amount, 2) }}</span></td>

                        <!-- Payee Name / Staff Name / Landlord -->
                        @if(in_array('payee_name', $config['fields']))
                            <td>{{ $item->payee_name ?? '-' }}</td>
                        @endif

                        <!-- Billing Month (Salaries/Rent/Electric Bill) -->
                        @if(in_array('billing_month', $config['fields']))
                            <td>
                                @if($item->billing_month)
                                    <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1.5">
                                        {{ \Carbon\Carbon::parse($item->billing_month)->format('F Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        @endif

                        <!-- Reference No. (General/Electric Bill/Other) -->
                        @if(in_array('reference_no', $config['fields']))
                            <td>
                                @if($item->reference_no)
                                    <code>{{ $item->reference_no }}</code>
                                @else
                                    -
                                @endif
                            </td>
                        @endif

                        <!-- Units Consumed (Electric Bill Only) -->
                        @if(in_array('units_consumed', $config['fields']))
                            <td>{{ $item->units_consumed ? $item->units_consumed . ' units' : '-' }}</td>
                        @endif

                        <!-- Payment Method -->
                        <td>
                            <span class="text-muted"><i class="fa-solid fa-wallet me-1 small"></i>{{ $item->payment_method }}</span>
                        </td>

                        <!-- Notes -->
                        <td><small class="text-muted text-wrap d-inline-block" style="max-width: 150px;">{{ $item->notes ?? '-' }}</small></td>

                        <!-- Actions -->
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('hostel.edit', [$category, $item->id]) }}" class="btn btn-outline-primary btn-sm" title="Edit details">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('hostel.destroy', [$category, $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this expenditure record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm ms-1" title="Delete record">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($config['headers']) + 1 }}" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-receipt fs-2 mb-3 d-block text-black-50"></i>
                            No records registered in this category.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $expenditures->links() }}
    </div>
</div>
@endsection
