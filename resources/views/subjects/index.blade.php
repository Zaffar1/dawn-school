@extends('layouts.app')

@section('title', 'Subjects Management')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Subjects Management</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Subjects</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- List Subjects (Col 8) -->
    <div class="col-12 col-lg-8">
        <div class="card-box mb-4">
            <!-- Filter Toolbar -->
            <form action="{{ route('subjects.index') }}" method="GET" class="row g-3 align-items-end mb-3">
                <div class="col-8">
                    <label for="class_id_filter" class="form-label">Filter by Class</label>
                    <select name="class_id" id="class_id_filter" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" {{ $classFilter == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject Name</th>
                            <th>Class</th>
                            <th class="text-center">Total Marks</th>
                            <th class="text-center">Passing Marks</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $index => $subject)
                            <tr>
                                <td>{{ $subjects->firstItem() + $index }}</td>
                                <td><span class="fw-semibold">{{ $subject->name }}</span></td>
                                <td><span class="badge bg-light text-primary border">{{ $subject->class->name }}</span></td>
                                <td class="text-center fw-medium">{{ $subject->total_marks }}</td>
                                <td class="text-center"><span class="badge bg-light text-danger border">{{ $subject->passing_marks }}</span></td>
                                <td>
                                    @if($subject->status === 'active')
                                        <span class="badge-status badge-status-active">Active</span>
                                    @else
                                        <span class="badge-status badge-status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-outline-primary btn-sm" title="Edit Subject">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No subjects found. Select a class or add a subject.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $subjects->links() }}
            </div>
        </div>
    </div>

    <!-- Create Subject (Col 4) -->
    <div class="col-12 col-lg-4">
        <div class="card-box">
            <h5 class="text-primary mb-3"><i class="fa-solid fa-circle-plus me-2"></i>Create New Subject</h5>
            
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Subject Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Mathematics, English" required>
                </div>

                <div class="mb-3">
                    <label for="class_id" class="form-label">Target Class</label>
                    <select name="class_id" id="class_id" class="form-select" required>
                        <option value="">Select Class...</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label for="total_marks" class="form-label">Total Marks</label>
                        <input type="number" name="total_marks" id="total_marks" class="form-control" value="100" min="1" required>
                    </div>
                    <div class="col-6">
                        <label for="passing_marks" class="form-label">Passing Marks</label>
                        <input type="number" name="passing_marks" id="passing_marks" class="form-control" value="40" min="1" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-check me-2"></i>Save Subject</button>
            </form>
        </div>
    </div>
</div>
@endsection
