@extends('layouts.app')

@section('title', 'Receipts List')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Receipts Management</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Receipts</li>
            </ol>
        </nav>
    </div>
    @can('manage-fee-collection')
    <div class="text-end">
        <a href="{{ route('fee-collection.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-dollar-to-slot me-2"></i>Collect Student Fee</a>
    </div>
    @endcan
</div>

<!-- Filters Toolbar -->
<div class="card-box mb-4">
    <form action="{{ route('receipts.index') }}" method="GET" class="row g-3 align-items-end">
        <!-- Search -->
        <div class="col-12 col-md-3">
            <label for="search" class="form-label">Search Receipt No</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="e.g. SDS-2026-00001" value="{{ $search }}">
        </div>

        <!-- Date -->
        <div class="col-12 col-sm-6 col-md-2">
            <label for="date" class="form-label">Payment Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $dateFilter }}">
        </div>

        <!-- Class -->
        <div class="col-12 col-sm-6 col-md-2">
            <label for="class_id" class="form-label">Class</label>
            <select name="class_id" id="class_id" class="form-select">
                <option value="">All Classes</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ $classFilter == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Student -->
        <div class="col-12 col-md-3">
            <label for="student_id" class="form-label">Student</label>
            <select name="student_id" id="student_id" class="form-select">
                <option value="">All Students</option>
                @foreach($students as $st)
                    <option value="{{ $st->id }}" {{ $studentFilter == $st->id ? 'selected' : '' }}>{{ $st->name }} ({{ $st->admission_number }})</option>
                @endforeach
            </select>
        </div>

        <!-- Actions -->
        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i>Filter</button>
            <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Receipts Table Card -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Receipt Number</th>
                    <th>Payment Date</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th class="text-end">Paid Amount</th>
                    <th class="text-end">Remaining Arrears</th>
                    <th class="text-end" style="min-width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $index => $rec)
                    <tr>
                        <td>{{ $receipts->firstItem() + $index }}</td>
                        <td><code class="text-dark fw-bold fs-6">{{ $rec->receipt_number }}</code></td>
                        <td>{{ $rec->date->format('d-M-Y') }}</td>
                        <td>
                            <a href="{{ route('students.show', $rec->student->id) }}" class="fw-semibold text-decoration-none">
                                {{ $rec->student->name }}
                            </a>
                            <div class="text-muted small">S/O: {{ $rec->student->father_name }}</div>
                        </td>
                        <td><span class="badge bg-light text-primary border">{{ $rec->student->class->name }}</span></td>
                        <td class="text-end fw-bold text-success">Rs. {{ number_format($rec->paid_amount, 2) }}</td>
                        <td class="text-end text-warning fw-semibold">Rs. {{ number_format($rec->remaining_arrears, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('receipts.show', $rec->id) }}" class="btn btn-outline-info btn-sm me-1" title="View Receipt">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <a href="{{ route('receipts.pdf', $rec->id) }}" class="btn btn-outline-secondary btn-sm" title="Download PDF">
                                <i class="fa-solid fa-file-pdf text-danger"></i> PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No receipts matched the search filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $receipts->links() }}
    </div>
</div>
@endsection
