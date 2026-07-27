@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Create Staff User</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
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
            <h5 class="text-primary mb-4"><i class="fa-solid fa-user-plus me-2"></i>Register System Account</h5>
            
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Ghulam Nabi" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="user@superdawn.com" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Select System Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">Choose Role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }} ({{ $role->description }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••" required>
                    </div>
                    <div class="col-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-check me-2"></i>Register Staff Account</button>
            </form>
        </div>
    </div>
</div>
@endsection
