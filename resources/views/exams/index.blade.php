@extends('layouts.app')

@section('title', 'Exams Management')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Exams Management</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Exams</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- List Exams (Col 8) -->
    <div class="col-12 col-lg-8">
        <div class="card-box mb-4">
            <!-- Filter Toolbar -->
            <form action="{{ route('exams.index') }}" method="GET" class="row g-3 align-items-end mb-3">
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
                            <th>Exam Name</th>
                            <th>Class</th>
                            <th>Session</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $index => $exam)
                            <tr>
                                <td>{{ $exams->firstItem() + $index }}</td>
                                <td><span class="fw-semibold">{{ $exam->name }}</span></td>
                                <td><span class="badge bg-light text-primary border">{{ $exam->class->name }}</span></td>
                                <td><code>{{ $exam->academic_session }}</code></td>
                                <td>{{ $exam->start_date->format('d-M-Y') }}</td>
                                <td>{{ $exam->end_date->format('d-M-Y') }}</td>
                                <td>
                                    @if($exam->status === 'active')
                                        <span class="badge-status badge-status-active">Active</span>
                                    @else
                                        <span class="badge-status badge-status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-outline-primary btn-sm" title="Edit Exam">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No examinations set. Choose a class or schedule an exam.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $exams->links() }}
            </div>
        </div>
    </div>

    <!-- Create Exam (Col 4) -->
    <div class="col-12 col-lg-4">
        <div class="card-box">
            <h5 class="text-primary mb-3"><i class="fa-solid fa-circle-plus me-2"></i>Schedule New Exam</h5>
            
            <form action="{{ route('exams.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Exam Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Mid Term, Annual Exam" required>
                </div>

                <div class="mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <select name="class_id" id="class_id" class="form-select" required>
                        <option value="">Select Class...</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="academic_session" class="form-label">Academic Session</label>
                    <input type="text" name="academic_session" id="academic_session" class="form-control" value="{{ $defaultSession }}" required>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-check me-2"></i>Save Schedule</button>
            </form>
        </div>
    </div>
</div>
@endsection
