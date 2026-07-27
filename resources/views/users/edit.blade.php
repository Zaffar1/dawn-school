@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="page-title-box">
    <div>
        <h3>Edit Staff User</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card-box">
            <h5 class="text-primary mb-4"><i class="fa-solid fa-user-pen me-2"></i>Modify User Profile</h5>
            
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">System Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }} ({{ $role->description }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="card-box bg-light border-0 shadow-none mb-3 p-3">
                    <div class="fw-semibold text-secondary mb-2 small"><i class="fa-solid fa-key me-1"></i>Change Password (Optional)</div>
                    <p class="small text-muted mb-3">Leave blank if you do not want to change the current account password.</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••">
                        </div>
                        <div class="col-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-check me-2"></i>Update User Account</button>
            </form>
        </div>
    </div>
</div>
@endsection
