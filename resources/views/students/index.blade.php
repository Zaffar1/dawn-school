@extends('layouts.app')

@section('title', 'Students Directory')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Students Directory</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Students</li>
            </ol>
        </nav>
    </div>
    @can('manage-admissions')
    <div class="text-end">
        <a href="{{ route('admissions.create') }}" class="btn btn-primary"><i class="fa-solid fa-user-plus me-2"></i>New Admission</a>
    </div>
    @endcan
</div>

<!-- Search & Filter Card -->
<div class="card-box mb-4">
    <form action="{{ route('students.index') }}" method="GET" class="row g-3 align-items-end">
        <!-- Search Query -->
        <div class="col-12 col-md-4">
            <label for="search" class="form-label">Search Students</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Name, Roll No, Admission No..." value="{{ $search }}">
            </div>
        </div>

        <!-- Class Filter -->
        <div class="col-12 col-sm-6 col-md-3">
            <label for="class_id" class="form-label">Filter by Class</label>
            <select name="class_id" id="class_id" class="form-select">
                <option value="">All Classes</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ $classFilter == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div class="col-12 col-sm-6 col-md-3">
            <label for="status" class="form-label">Status Filter</label>
            <select name="status" id="status" class="form-select">
                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active Students</option>
                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive Students</option>
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Students</option>
            </select>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i>Filter</button>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Students Table Card -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Father Name</th>
                    <th>Admission No</th>
                    <th>Class</th>
                    <th>Roll No</th>
                    <th>Phone</th>
                    <th>Arrears</th>
                    <th>Status</th>
                    <th class="text-end" style="min-width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    <tr>
                        <td>{{ $students->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover; border: 1px solid var(--border-color);">
                                @else
                                    <div class="rounded-circle bg-light border text-muted d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.85rem; font-weight:600;">
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
                        <td><span class="badge bg-light text-primary border">{{ $student->class->name }}</span></td>
                        <td><span class="badge bg-light text-dark border px-2 py-1">{{ $student->roll_number }}</span></td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td class="fw-semibold text-warning">Rs. {{ number_format($student->arrears, 2) }}</td>
                        <td>
                            @if($student->status === 'active')
                                <span class="badge-status badge-status-active">Active</span>
                            @else
                                <span class="badge-status badge-status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-info btn-sm me-1" title="View Profile">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline-primary btn-sm" title="Edit Student">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">No students matching the criteria found.</td>
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
