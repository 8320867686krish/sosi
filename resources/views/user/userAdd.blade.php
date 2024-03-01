@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/css/bootstrap4-toggle.min.css') }}">
@endsection

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">User Management</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('users') }}" class="breadcrumb-link">User</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"
                                        class="breadcrumb-link">{{ $head_title ?? 'Add' }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div class="card">
                    <h5 class="card-header">{{ $head_title ?? '' }} User</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('users.store') }}" class="needs-validation" novalidate
                            id="userForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="name">First Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" value="{{ old('name', $user->name ?? '') }}" name="name"
                                            placeholder="First Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" value="{{ old('last_name', $user->last_name ?? '') }}"
                                            name="last_name" placeholder="Last Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                                            placeholder="User Email..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="roles">Role</label>
                                        <select name="roles" id="roles" class="form-control"
                                            @if (!empty($user->role)) readonly @endif>
                                            <option value="">Select Role</option>
                                            @if (isset($roles) && $roles->count() > 0)
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ old('roles') == $role->name || (isset($user) && $role->name == $user->role) ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    @if (isset($user))
                                        <input type="checkbox" name="isVerified" data-offstyle="danger" data-toggle="toggle" data-on="Enabled" data-off="Disabled" {{ $user->isVerified ? 'checked' : '' }}>
                                    @else
                                        <input type="checkbox" name="isVerified" data-offstyle="danger" data-toggle="toggle" data-on="Enabled" data-off="Disabled" checked>
                                    @endif
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('users') }}" class="btn btn-info" type="button">Back</a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right"
                                            type="submit">{{ $button ?? 'Add' }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/libs/js/bootstrap4-toggle.min.js') }}"></script>
    <script>
        function removeInvalidClass(input) {
            // Check if the input value is empty or whitespace only
            const isValid = input.value.trim() !== '';

            // Toggle the 'is-invalid' class based on the validity
            input.classList.toggle('is-invalid', !isValid);
        }
    </script>
@endsection
