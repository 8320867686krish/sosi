@extends('layouts.app')

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
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Change Password</a></li>
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
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <x-input-label for="current_password" :value="__('Current Password')" />
                                <input id="current_password" class="form-control @error('current_password') is-invalid @enderror" type="password" name="current_password" placeholder="Current Password" required onchange="removeInvalidClass(this)" aria-describedby="currentPasswordError" />
                                @error('current_password')
                                    <div id="currentPasswordError" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <x-input-label for="password" :value="__('Password')" />
                                <input class="form-control @error('password') is-invalid @enderror" id="password"
                                    type="password" required="" placeholder="Password" name="password" minlength="8"
                                    onchange="removeInvalidClass(this)" aria-describedby="passwordError" />
                                @error('password')
                                    <div id="passwordError" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" required="" placeholder="Confirm Password"
                                    onchange="removeInvalidClass(this)" aria-describedby="confirmPasswordError" />
                                @error('password_confirmation')
                                    <div id="confirmPasswordError" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group pt-2">
                                <button class="btn btn-block btn-primary" type="submit">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function removeInvalidClass(input) {
            // Check if the input value is empty or whitespace only
            const isValid = input.value.trim() !== '';

            // Toggle the 'is-invalid' class based on the validity
            input.classList.toggle('is-invalid', !isValid);
        }
    </script>
@endsection
