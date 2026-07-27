@extends('layouts.app')

@section('title', 'Edit Class')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Edit Class</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Classes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card-box">
            <h5 class="text-primary mb-4"><i class="fa-solid fa-pen-to-square me-2"></i>Modify Class Details</h5>
            
            <form action="{{ route('classes.update', $class->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Class Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $class->name) }}" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active" {{ $class->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $class->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Update Class</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
