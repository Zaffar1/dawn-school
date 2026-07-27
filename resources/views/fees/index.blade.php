@extends('layouts.app')

@section('title', 'Fees Collections')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Fees Collection Directory</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fee Collections</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('fee-collection.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-dollar-to-slot me-2"></i>Collect Student Fee</a>
    </div>
</div>

<div class="card-box">
    <h5 class="text-primary mb-3"><i class="fa-solid fa-list me-2"></i>Fee Collections History</h5>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Receipt No</th>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th class="text-end">Due Total</th>
                    <th class="text-end">Paid Amount</th>
                    <th class="text-end">Arrears Balance</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $index => $rec)
                    <tr>
                        <td>{{ $receipts->firstItem() + $index }}</td>
                        <td><code class="text-dark fw-bold">{{ $rec->receipt_number }}</code></td>
                        <td>{{ $rec->date->format('d-M-Y') }}</td>
                        <td>
                            <a href="{{ route('students.show', $rec->student->id) }}" class="fw-semibold text-decoration-none">
                                {{ $rec->student->name }}
                            </a>
                            <div class="text-muted small">S/O: {{ $rec->student->father_name }}</div>
                        </td>
                        <td><span class="badge bg-light text-primary border">{{ $rec->student->class->name }}</span></td>
                        <td class="text-end fw-semibold text-secondary">Rs. {{ number_format($rec->total_amount, 2) }}</td>
                        <td class="text-end fw-bold text-success">Rs. {{ number_format($rec->paid_amount, 2) }}</td>
                        <td class="text-end text-warning">Rs. {{ number_format($rec->remaining_arrears, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('receipts.show', $rec->id) }}" class="btn btn-outline-info btn-sm me-1">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <a href="{{ route('receipts.pdf', $rec->id) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-file-pdf text-danger"></i> PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No collections recorded yet.</td>
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
