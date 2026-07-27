@extends('layouts.app')

@section('title', 'Fee Settings')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Fee Settings Configuration</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fee Settings</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-10">
        <div class="card-box">
            <h5 class="text-primary mb-3"><i class="fa-solid fa-gears me-2"></i>Class-wise Default Fee Standards</h5>
            <p class="text-muted small mb-4">Set the default fees for each class below. These configurations apply automatically during student admissions and monthly collections. You can override these fees on individual student profiles if needed.</p>
            
            <form action="{{ route('fee-settings.update') }}" method="POST">
                @csrf
                
                <div class="table-responsive">
                    <table class="table table-custom align-middle text-center">
                        <thead>
                            <tr>
                                <th class="text-start">Class Name</th>
                                <th style="width: 250px;">Default Admission Fee (Rs.)</th>
                                <th style="width: 250px;">Default Monthly Tuition (Rs.)</th>
                                <th style="width: 250px;">Default Exam Fee (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $index => $class)
                                <tr>
                                    <td class="text-start">
                                        <input type="hidden" name="fees[{{ $index }}][class_id]" value="{{ $class->id }}">
                                        <span class="fw-semibold text-dark">{{ $class->name }}</span>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rs.</span>
                                            <input type="number" name="fees[{{ $index }}][admission_fee]" class="form-control text-center" value="{{ $class->feeSetting->admission_fee ?? 3000.00 }}" min="0" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rs.</span>
                                            <input type="number" name="fees[{{ $index }}][monthly_fee]" class="form-control text-center" value="{{ $class->feeSetting->monthly_fee ?? 2000.00 }}" min="0" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rs.</span>
                                            <input type="number" name="fees[{{ $index }}][exam_fee]" class="form-control text-center" value="{{ $class->feeSetting->exam_fee ?? 500.00 }}" min="0" required>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5"><i class="fa-solid fa-check me-2"></i>Save Fee Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
