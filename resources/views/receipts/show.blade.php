@extends('layouts.app')

@section('title', 'Receipt #' . $receipt->receipt_number)

@section('content')
<div class="page-title-box d-print-none">
    <div>
        <h3>Payment Receipt</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('receipts.index') }}">Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $receipt->receipt_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <button onclick="window.print();" class="btn btn-outline-primary me-2"><i class="fa-solid fa-print me-2"></i>Print Receipt</button>
        <a href="{{ route('receipts.pdf', $receipt->id) }}" class="btn btn-danger me-2"><i class="fa-solid fa-file-pdf me-2"></i>Download PDF</a>
        <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>
</div>

<!-- RECEIPT SHEET CONTAINER -->
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card-box p-5 border shadow-sm bg-white" style="border-radius: 12px; margin-top: 10px;">
            
            <!-- School Header -->
            <div class="row align-items-center mb-4 pb-3 border-bottom">
                <div class="col-8">
                    <h3 class="text-primary fw-bold mb-1 uppercase" style="font-family:'Outfit', sans-serif;">{{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }}</h3>
                    <p class="text-muted small mb-0">
                        <i class="fa-solid fa-location-dot me-1"></i> {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}<br>
                        <i class="fa-solid fa-phone me-1"></i> {{ $school->phone ?? '0300-1234567' }} | <i class="fa-solid fa-envelope me-1"></i> {{ $school->email ?? 'info@superdawnschool.edu.pk' }}
                    </p>
                </div>
                <div class="col-4 text-end">
                    <div class="p-2 border rounded bg-light d-inline-block text-start">
                        <div class="small text-muted font-monospace" style="font-size:0.75rem;">RECEIPT NO.</div>
                        <div class="fw-bold text-dark font-monospace fs-6">{{ $receipt->receipt_number }}</div>
                    </div>
                </div>
            </div>

            <!-- Receipt Info Grid -->
            <div class="row g-3 mb-4 small">
                <div class="col-6 col-md-3 text-muted">Student Name:</div>
                <div class="col-6 col-md-3 fw-bold text-dark">{{ $receipt->student->name }}</div>

                <div class="col-6 col-md-3 text-muted">Payment Date:</div>
                <div class="col-6 col-md-3 fw-semibold text-dark">{{ $receipt->date->format('d-M-Y') }}</div>

                <div class="col-6 col-md-3 text-muted">Father Name:</div>
                <div class="col-6 col-md-3 fw-semibold text-dark">{{ $receipt->student->father_name }}</div>

                <div class="col-6 col-md-3 text-muted">Academic Session:</div>
                <div class="col-6 col-md-3 fw-semibold text-dark">{{ $school->academic_session ?? '2026-2027' }}</div>

                <div class="col-6 col-md-3 text-muted">Class & Section:</div>
                <div class="col-6 col-md-3 fw-bold text-primary">{{ $receipt->student->class->name }}</div>

                <div class="col-6 col-md-3 text-muted">Admission Roll No:</div>
                <div class="col-6 col-md-3 fw-semibold text-dark">{{ $receipt->student->roll_number }}</div>
            </div>

            <!-- Fee Breakdown Table -->
            <h6 class="text-secondary fw-bold uppercase mb-3"><i class="fa-solid fa-cash-register me-2"></i>Fee Breakdown</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle text-center small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="text-start">Fee Category</th>
                            <th>Previous Arrears</th>
                            <th>Admission Fee</th>
                            <th>Monthly Fee</th>
                            <th>Exam Fee</th>
                            <th class="text-end">Total Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start fw-semibold">Standard Dues</td>
                            <td>Rs. {{ number_format($receipt->previous_arrears, 2) }}</td>
                            <td>Rs. {{ number_format($receipt->admission_fee, 2) }}</td>
                            <td>Rs. {{ number_format($receipt->monthly_fee, 2) }}</td>
                            <td>Rs. {{ number_format($receipt->exam_fee, 2) }}</td>
                            <td class="text-end fw-bold text-dark">Rs. {{ number_format($receipt->total_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Total Box & Payment Status -->
            <div class="row align-items-center mb-5">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class="alert alert-success border-0 py-2.5 px-3 d-inline-block small">
                        <i class="fa-solid fa-circle-check me-1"></i> Status: <strong>PAID</strong> (Transaction Complete)
                    </div>
                </div>
                <div class="col-12 col-md-6 text-end">
                    <div class="bg-light p-3 border rounded-3 d-inline-block text-start" style="min-width: 250px;">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Total Due:</span>
                            <span class="fw-semibold">Rs. {{ number_format($receipt->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Amount Paid:</span>
                            <span class="fw-bold text-success">Rs. {{ number_format($receipt->paid_amount, 2) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Remaining Arrears:</span>
                            <span class="fw-bold text-warning">Rs. {{ number_format($receipt->remaining_arrears, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signature footer area -->
            <div class="row mt-5 pt-4 text-center small text-muted">
                <div class="col-6">
                    <div style="width: 160px; border-bottom: 1px solid #ddd; margin: 0 auto 5px auto;"></div>
                    Class Teacher Signature
                </div>
                <div class="col-6">
                    <div style="width: 160px; border-bottom: 1px solid #ddd; margin: 0 auto 5px auto;"></div>
                    Principal Signature<br>
                    ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
