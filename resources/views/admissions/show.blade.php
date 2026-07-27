@extends('layouts.app')

@section('title', 'Admission Details - ' . $admission->student->name)

@section('content')
<div class="page-title-box">
    <div>
        <h3>Admission Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $admission->student->name }}</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('admissions.pdf', $admission->id) }}" class="btn btn-danger me-2"><i class="fa-solid fa-file-pdf me-2"></i>Download PDF</a>
        <a href="{{ route('admissions.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>
</div>

<div class="card-box">
    <div class="row">
        <!-- Student Badge Left (Col 4) -->
        <div class="col-12 col-md-4 text-center border-end py-3">
            @if($admission->student->photo)
                <img src="{{ asset('storage/' . $admission->student->photo) }}" alt="Photo" class="profile-avatar-large mb-3">
            @else
                <div class="mx-auto rounded-circle bg-light border text-muted d-flex align-items-center justify-content-center mb-3" style="width: 140px; height: 140px; font-size: 3rem; font-weight:700;">
                    {{ substr($admission->student->name, 0, 1) }}
                </div>
            @endif
            <h4 class="mb-1">{{ $admission->student->name }}</h4>
            <div class="text-muted mb-2">Admission No: <code class="text-dark fw-bold fs-6">{{ $admission->student->admission_number }}</code></div>
            <span class="badge bg-light text-primary border px-3 py-2 fs-6">Class: {{ $admission->class->name }}</span>
            <div class="mt-3">
                <span class="badge-status {{ $admission->student->status === 'active' ? 'badge-status-active' : 'badge-status-inactive' }} px-3 py-2 font-size-14">
                    Status: {{ strtoupper($admission->student->status) }}
                </span>
            </div>
        </div>

        <!-- Details Right (Col 8) -->
        <div class="col-12 col-md-8 py-3 ps-md-4">
            <h5 class="text-primary mb-3"><i class="fa-solid fa-address-card me-2"></i>Personal & Academic Information</h5>
            
            <div class="row g-3 small mb-4">
                <div class="col-6 col-sm-4 text-muted">Father Name:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">{{ $admission->student->father_name }}</div>
                
                <div class="col-6 col-sm-4 text-muted">Date of Birth:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">{{ $admission->student->date_of_birth->format('d-F-Y') }}</div>
                
                <div class="col-6 col-sm-4 text-muted">Gender:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">{{ ucfirst($admission->student->gender) }}</div>
                
                <div class="col-6 col-sm-4 text-muted">Contact Phone:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">{{ $admission->student->phone ?? '-' }}</div>

                <div class="col-6 col-sm-4 text-muted">Address:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">{{ $admission->student->address }}</div>

                <div class="col-6 col-sm-4 text-muted">Admission Date:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">{{ $admission->admission_date->format('d-M-Y') }}</div>

                <div class="col-6 col-sm-4 text-muted">Class Roll Number:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark"><span class="badge bg-light text-dark border">{{ $admission->student->roll_number }}</span></div>
            </div>

            <h5 class="text-primary mb-3 border-top pt-3"><i class="fa-solid fa-coins me-2"></i>Financial & Fee Schedule</h5>
            
            <div class="row g-3 small">
                <div class="col-6 col-sm-4 text-muted">Admission Registration Fee:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">Rs. {{ number_format($admission->admission_fee, 2) }}</div>

                <div class="col-6 col-sm-4 text-muted">Monthly Tuition Fee:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">Rs. {{ number_format($admission->monthly_fee, 2) }}</div>

                <div class="col-6 col-sm-4 text-muted">Class Examination Fee:</div>
                <div class="col-6 col-sm-8 fw-semibold text-dark">Rs. {{ number_format($admission->exam_fee, 2) }}</div>

                <div class="col-6 col-sm-4 text-muted">Outstanding Arrears:</div>
                <div class="col-6 col-sm-8 fw-semibold text-warning">Rs. {{ number_format($admission->arrears, 2) }}</div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <a href="{{ route('students.show', $admission->student->id) }}" class="btn btn-primary"><i class="fa-solid fa-user-graduate me-2"></i>Go to Student Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection
