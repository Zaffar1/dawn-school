@extends('layouts.app')

@section('title', 'Edit Subject')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Edit Subject</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card-box">
            <h5 class="text-primary mb-4"><i class="fa-solid fa-pen-to-square me-2"></i>Modify Subject Details</h5>
            
            <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Subject Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $subject->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="class_id" class="form-label">Class</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ $subject->class_id == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $subject->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $subject->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="total_marks" class="form-label">Total Marks</label>
                        <input type="number" name="total_marks" id="total_marks" class="form-control" value="{{ old('total_marks', $subject->total_marks) }}" min="1" required>
                    </div>

                    <div class="col-md-6">
                        <label for="passing_marks" class="form-label">Passing Marks</label>
                        <input type="number" name="passing_marks" id="passing_marks" class="form-control" value="{{ old('passing_marks', $subject->passing_marks) }}" min="1" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
