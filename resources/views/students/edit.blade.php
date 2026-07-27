@extends('layouts.app')

@section('title', 'Edit Student - ' . $student->name)

@section('content')
<div class="page-title-box">
    <div>
        <h3>Edit Student Profile</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
    </div>
</div>

<div class="card-box">
    <h5 class="text-primary mb-4 border-bottom pb-2"><i class="fa-solid fa-user-pen me-2"></i>Modify Student Profile</h5>
    
    <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- SECTION 1: Personal Profile -->
        <h6 class="text-secondary fw-semibold mb-3">1. Student Details</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="name" class="form-label">Student Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $student->name) }}" required>
            </div>
            
            <div class="col-md-6">
                <label for="father_name" class="form-label">Father Name</label>
                <input type="text" name="father_name" id="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}" required>
            </div>

            <div class="col-sm-6 col-md-4">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}" required>
            </div>

            <div class="col-sm-6 col-md-4">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="male" {{ $student->gender === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $student->gender === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $student->gender === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
            </div>

            <div class="col-md-8">
                <label for="address" class="form-label">Residential Address</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $student->address) }}" required>
            </div>

            <div class="col-md-4">
                <label for="photo" class="form-label">Replace Student Photo</label>
                <input type="file" name="photo" id="photo" class="form-control">
            </div>
        </div>

        <!-- SECTION 2: Academic Details -->
        <h6 class="text-secondary fw-semibold mb-3">2. Academic Information</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Admission Number</label>
                <input type="text" class="form-control bg-light" value="{{ $student->admission_number }}" readonly>
            </div>

            <div class="col-md-3">
                <label for="class_id" class="form-label">Class</label>
                <select name="class_id" id="class_id" class="form-select" required>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" {{ $student->class_id == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="roll_number" class="form-label">Roll Number</label>
                <input type="text" name="roll_number" id="roll_number" class="form-control" value="{{ old('roll_number', $student->roll_number) }}" required>
            </div>

            <div class="col-md-3">
                <label for="admission_date" class="form-label">Admission Date</label>
                <input type="date" name="admission_date" id="admission_date" class="form-control" value="{{ old('admission_date', $student->admission_date->format('Y-m-d')) }}" required>
            </div>
        </div>

        <!-- SECTION 3: Fee Configurations -->
        <h6 class="text-secondary fw-semibold mb-3">3. Individual Fee Settings & Arrears Balance</h6>
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-md-3">
                <label for="admission_fee" class="form-label">Admission Override (Rs.)</label>
                <input type="number" name="admission_fee" id="admission_fee" class="form-control" value="{{ old('admission_fee', $student->admission_fee) }}" min="0" required>
            </div>

            <div class="col-sm-6 col-md-3">
                <label for="monthly_fee" class="form-label">Monthly Override (Rs.)</label>
                <input type="number" name="monthly_fee" id="monthly_fee" class="form-control" value="{{ old('monthly_fee', $student->monthly_fee) }}" min="0" required>
            </div>

            <div class="col-sm-6 col-md-3">
                <label for="exam_fee" class="form-label">Exam Override (Rs.)</label>
                <input type="number" name="exam_fee" id="exam_fee" class="form-control" value="{{ old('exam_fee', $student->exam_fee) }}" min="0" required>
            </div>

            <div class="col-sm-6 col-md-3">
                <label for="arrears" class="form-label">Current Outstanding Arrears (Rs.)</label>
                <input type="number" name="arrears" id="arrears" class="form-control" value="{{ old('arrears', $student->arrears) }}" required>
            </div>
        </div>

        <!-- SECTION 4: Enrollment Status -->
        <h6 class="text-secondary fw-semibold mb-3">4. Status Settings</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="active" {{ $student->status === 'active' ? 'selected' : '' }}>Active (Enrolled)</option>
                    <option value="inactive" {{ $student->status === 'inactive' ? 'selected' : '' }}>Inactive (Left / Graduated)</option>
                </select>
                <small class="text-muted mt-1 d-block">Setting a student to Inactive automatically hides them from active dropdowns but retains history.</small>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="text-end border-top pt-3">
            <button type="submit" class="btn btn-primary px-5"><i class="fa-solid fa-check me-2"></i>Update Profile</button>
        </div>
    </form>
</div>
@endsection
