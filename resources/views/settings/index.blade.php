@extends('layouts.app')

@section('title', 'School Settings')

@section('content')
<div class="page-title-box">
    <div>
        <h3>School Settings</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card-box">
            <h5 class="text-primary mb-4"><i class="fa-solid fa-sliders me-2"></i>Configure Institution Information</h5>
            
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-3">
                    <!-- School Name -->
                    <div class="col-12">
                        <label for="name" class="form-label">School Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $school->name ?? 'SUPER DAWN SCHOOL LAKHI') }}" required>
                    </div>

                    <!-- Principal Name -->
                    <div class="col-md-6">
                        <label for="principal_name" class="form-label">Principal Name</label>
                        <input type="text" name="principal_name" id="principal_name" class="form-control" value="{{ old('principal_name', $school->principal_name ?? '') }}" required>
                    </div>

                    <!-- Academic Session -->
                    <div class="col-md-6">
                        <label for="academic_session" class="form-label">Academic Session</label>
                        <input type="text" name="academic_session" id="academic_session" class="form-control" value="{{ old('academic_session', $school->academic_session ?? '2026-2027') }}" required placeholder="e.g. 2026-2027">
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone / Contact No.</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $school->phone ?? '') }}" required>
                    </div>

                    <!-- Email Address -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Official Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $school->email ?? '') }}" required>
                    </div>

                    <!-- School Address -->
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control" required>{{ old('address', $school->address ?? '') }}</textarea>
                    </div>

                    <!-- School Logo -->
                    <div class="col-12">
                        <label for="logo" class="form-label">School Logo</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($school && $school->logo)
                                <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" class="img-thumbnail" style="width: 70px; height: 70px; object-fit: cover;">
                            @else
                                <div class="bg-light border text-muted d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; border-radius: 6px;">
                                    <i class="fa-solid fa-image fs-4"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" name="logo" id="logo" class="form-control">
                                <small class="text-muted">Recommended: Square PNG with transparent background. Max 2MB.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-check me-2"></i>Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <div class="card-box bg-light border-0 shadow-none">
            <h5 class="text-secondary mb-3"><i class="fa-solid fa-circle-info me-2"></i>Settings Scope</h5>
            <p class="small text-muted mb-3">Updating institutional values propagates changes sitewide. The configured settings automatically apply to:</p>
            <ul class="small text-muted ps-3 mb-0">
                <li class="mb-2">Admin Dashboard summaries</li>
                <li class="mb-2">Student Admission letters & printable forms</li>
                <li class="mb-2">Academic Marksheets (PDF and web)</li>
                <li class="mb-2">Financial Receipt documents and invoice tallies</li>
                <li class="mb-2">Academic, demographic, and financial reports</li>
            </ul>
        </div>
    </div>
</div>
@endsection
