@extends('layouts.app')

@section('title', 'Staff User Directory')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Staff User Directory</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-plus me-2"></i>Create New User</a>
    </div>
</div>

<div class="card-box">
    <h5 class="text-primary mb-3"><i class="fa-solid fa-users-gear me-2"></i>System Staff Accounts</h5>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Staff Name</th>
                    <th>Email Address</th>
                    <th>Assigned Role</th>
                    <th>Registered At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><span class="fw-semibold text-dark">{{ $user->name }}</span></td>
                        <td><code>{{ $user->email }}</code></td>
                        <td>
                            @php
                                $roleColors = [
                                    'super-admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    'admin' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                    'accountant' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                    'teacher' => 'bg-info-subtle text-info border border-info-subtle',
                                ];
                                $color = $roleColors[$user->role->slug] ?? 'bg-light text-dark';
                            @endphp
                            <span class="badge {{ $color }} px-2.5 py-1.5">{{ $user->role->name }}</span>
                        </td>
                        <td>{{ $user->created_at->format('d-M-Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm" title="Edit User">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No staff accounts registered.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection
