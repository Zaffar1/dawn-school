@extends('layouts.app')

@section('title', 'Classes Management')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Classes Management</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Classes</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- List Classes (Col 8) -->
    <div class="col-12 col-lg-8">
        <div class="card-box">
            <h5 class="text-primary mb-3"><i class="fa-solid fa-list me-2"></i>Classes Directory</h5>
            
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Class Name</th>
                            <th class="text-center">Active Students</th>
                            <th class="text-center">Inactive Students</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td>{{ $class->id }}</td>
                                <td>
                                    <a href="{{ route('classes.students', $class->id) }}" class="fw-semibold text-decoration-none">
                                        {{ $class->name }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-primary border px-2.5 py-1.5">{{ $class->active_students_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-danger border px-2.5 py-1.5">{{ $class->inactive_students_count }}</span>
                                </td>
                                <td>
                                    @if($class->status === 'active')
                                        <span class="badge-status badge-status-active">Active</span>
                                    @else
                                        <span class="badge-status badge-status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('classes.students', $class->id) }}" class="btn btn-outline-info btn-sm me-1" title="View Students">
                                        <i class="fa-solid fa-users"></i>
                                    </a>
                                    <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-outline-primary btn-sm" title="Edit Class">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No classes found in the directory.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $classes->links() }}
            </div>
        </div>
    </div>

    <!-- Create Class (Col 4) -->
    <div class="col-12 col-lg-4">
        <div class="card-box">
            <h5 class="text-primary mb-3"><i class="fa-solid fa-circle-plus me-2"></i>Create New Class</h5>
            
            <form action="{{ route('classes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Class Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Play, Nursery, Class 5" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-check me-2"></i>Save Class</button>
            </form>
        </div>
        
        <div class="card-box bg-light border-0 shadow-none mt-4">
            <h6 class="text-secondary fw-semibold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Note on Deletion</h6>
            <p class="small text-muted mb-0">Classes cannot be permanently deleted once historical records (like fee receipt history, admissions, and marksheets) exist. Instead of deletion, deactivate the class to hide it from selection lists.</p>
        </div>
    </div>
</div>
@endsection
