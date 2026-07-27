@extends('layouts.app')

@section('title', 'Edit Exam')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Edit Examination</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Exams</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card-box">
            <h5 class="text-primary mb-4"><i class="fa-solid fa-pen-to-square me-2"></i>Modify Exam Schedule</h5>
            
            <form action="{{ route('exams.update', $exam->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Exam Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $exam->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="class_id" class="form-label">Class</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ $exam->class_id == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $exam->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $exam->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="academic_session" class="form-label">Academic Session</label>
                        <input type="text" name="academic_session" id="academic_session" class="form-control" value="{{ old('academic_session', $exam->academic_session) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $exam->start_date->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $exam->end_date->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Update Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
