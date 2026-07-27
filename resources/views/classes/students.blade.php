@extends('layouts.app')

@section('title', $class->name . ' Students')

@section('content')
<div class="page-title-box">
    <div>
        <h3>{{ $class->name }} - Student Roster</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Classes</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $class->name }} Students</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Classes</a>
    </div>
</div>

<div class="card-box mb-4">
    <!-- Filter Toolbar -->
    <form action="{{ route('classes.students', $class->id) }}" method="GET" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label for="search" class="form-label">Search Students</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Name, Father Name, Admission No..." value="{{ $search }}">
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <label for="status" class="form-label">Status Filter</label>
            <select name="status" id="status" class="form-select">
                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active Students</option>
                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive Students</option>
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Students</option>
            </select>
        </div>

        <div class="col-12 col-sm-6 col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-2"></i>Filter</button>
        </div>
        <div class="col-12 col-sm-6 col-md-2">
            <a href="{{ route('classes.students', $class->id) }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Student Name</th>
                    <th>Father Name</th>
                    <th>Admission No</th>
                    <th>Roll No</th>
                    <th>Phone</th>
                    <th>Admission Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    <tr>
                        <td>{{ $students->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover; border: 1px solid var(--border-color);">
                                @else
                                    <div class="rounded-circle bg-light border text-muted d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.8rem; font-weight:600;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('students.show', $student->id) }}" class="fw-semibold text-decoration-none">{{ $student->name }}</a>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->father_name }}</td>
                        <td><code class="text-dark fw-semibold">{{ $student->admission_number }}</code></td>
                        <td><span class="badge bg-light text-dark border px-2 py-1">{{ $student->roll_number }}</span></td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td>{{ $student->admission_date->format('d-M-Y') }}</td>
                        <td>
                            @if($student->status === 'active')
                                <span class="badge-status badge-status-active">Active</span>
                            @else
                                <span class="badge-status badge-status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-info btn-sm" title="View Profile">
                                <i class="fa-solid fa-eye"></i> View Profile
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No students matching the query found in this class.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $students->links() }}
    </div>
</div>
@endsection
