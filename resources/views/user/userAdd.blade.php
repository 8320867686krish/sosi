@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/css/switchButton.css') }}">
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
                    {{-- <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('users') }}" class="breadcrumb-link">User</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#" class="breadcrumb-link">{{ $head_title ?? 'Add' }}</a></li>
                            </ol>
                        </nav>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div class="card">
                    <h5 class="card-header">{{ $head_title ?? '' }} User</h5>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate id="userForm">
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
                                        {{-- @error('name') --}}
                                        <div class="invalid-feedback error" id="nameError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" value="{{ old('last_name', $user->last_name ?? '') }}"
                                            name="last_name" placeholder="Last Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        {{-- @error('name') --}}
                                        <div class="invalid-feedback error" id="lastNameError"></div>
                                        {{-- @enderror --}}
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
                                        {{-- @error('email') --}}
                                        <div class="invalid-feedback error" id="emailError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="roles">Role</label>
                                        @if (isset($user) && !empty($user->role))
                                            <input type="hidden" name="roles" id="roles" value="{{ $user->role }}">
                                        @endif
                                        <select name="roles" id="roles" class="form-control"
                                            @if (!empty($user->role)) disabled @endif
                                            onchange="removeInvalidClass(this)">
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
                                        <div class="invalid-feedback error" id="rolesError"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                            placeholder="User Phone..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="phoneError"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location"
                                            value="{{ old('location', $user->location ?? '') }}"
                                            placeholder="User Location..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="locationError"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('users') }}" class="btn pl-0" type="button"><i
                                                class="fas fa-arrow-left"></i> <b>Back</b></a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right formSubmitBtn" id="formSubmitBtn"
                                            type="submit">{!! $button ?? '<i class="fas fa-plus"></i>  Add' !!}</button>
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

@push('js')
    <script>
        function removeInvalidClass(input) {

            const isValid = input.value.trim() !== '';

            input.classList.toggle('is-invalid', !isValid);

            const errorMessageElement = input.parentElement.querySelector('.invalid-feedback');

            if (errorMessageElement) {
                errorMessageElement.style.display = isValid ? 'none' : 'block';
            }
        }

        $(document).ready(function() {
            $('#userForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                var $submitButton = $(this).find('button[type="submit"]');
                var originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                // Clear previous error messages and invalid classes
                $('.error').empty().hide();
                $('input').removeClass('is-invalid');
                $('select').removeClass('is-invalid');

                // Serialize form data
                let formData = $(this).serialize();

                // Submit form via AJAX
                $.ajax({
                    url: "{{ route('users.store') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.message) {
                            localStorage.setItem('message', response.message);
                        }
                        window.location.href = "{{ route('users') }}";
                    },
                    error: function(xhr, status, error) {
                        // If there are errors, display them
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            // Loop through errors and display them
                            $.each(errors, function(field, messages) {
                                // Display error message for each field
                                $('#' + field + 'Error').text(messages[0]).show();
                                // Add is-invalid class to input or select field
                                $('[name="' + field + '"]').addClass('is-invalid');
                            });
                        } else {
                            console.error('Error submitting form:', error);
                        }
                    },
                    complete: function() {
                        // $submitButton.html('<i class="fas fa-plus"></i>  Add');
                        $submitButton.html(originalText); // Restore original text
                        $submitButton.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
