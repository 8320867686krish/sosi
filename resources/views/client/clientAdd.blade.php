@extends('layouts.app')

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Client Management</h2>
                    {{-- <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('clients') }}" class="breadcrumb-link">Client</a></li>
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
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div class="card">
                    <h5 class="card-header">Client Manager</h5>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate id="clientForm"
                            enctype="multipart/form-data">
                            @csrf
                            <h5>Ship Manager Details</h5>
                            <input type="hidden" name="id" value="{{ $client->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="manager_name">Name</label>
                                        <input type="text"
                                            class="form-control @error('manager_name') is-invalid @enderror"
                                            id="manager_name" value="{{ old('manager_name', $client->manager_name ?? '') }}"
                                            name="manager_name" placeholder="Ship Manager Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        {{-- @error('manager_name') --}}
                                        <div class="invalid-feedback error" id="manager_nameError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="manager_email">Email</label>
                                        <input type="email"
                                            class="form-control @error('manager_email') is-invalid @enderror"
                                            id="manager_email" name="manager_email"
                                            value="{{ old('manager_email', $client->manager_email ?? '') }}"
                                            placeholder="Ship Manager Email..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        {{-- @error('manager_email') --}}
                                        <div class="invalid-feedback error" id="manager_emailError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="manager_phone">Phone</label>
                                        <input type="number"
                                            class="form-control @error('manager_phone') is-invalid @enderror"
                                            id="manager_phone" name="manager_phone"
                                            value="{{ old('manager_phone', $client->manager_phone ?? '') }}"
                                            placeholder="Ship Manager Phone..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        {{-- @error('manager_phone') --}}
                                        <div class="invalid-feedback error" id="manager_phoneError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="manager_contact_person_name">Contact Person Name</label>
                                        <input type="text"
                                            class="form-control @error('manager_contact_person_name') is-invalid @enderror"
                                            id="manager_contact_person_name"
                                            value="{{ old('manager_contact_person_name', $client->manager_contact_person_name ?? '') }}"
                                            name="manager_contact_person_name" placeholder="Contact Person Name..."
                                            autocomplete="off" onchange="removeInvalidClass(this)">
                                        {{-- @error('manager_contact_person_name') --}}
                                        <div class="invalid-feedback error" id="manager_contact_person_nameError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="manager_contact_person_email">Contact Person Email</label>
                                        <input type="email"
                                            class="form-control @error('manager_contact_person_email') is-invalid @enderror"
                                            id="manager_contact_person_email" name="manager_contact_person_email"
                                            value="{{ old('manager_contact_person_email', $client->manager_contact_person_email ?? '') }}"
                                            placeholder="Contact Person Email..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        {{-- @error('manager_contact_person_email') --}}
                                        <div class="invalid-feedback error" id="manager_contact_person_emailError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="manager_contact_person_phone">Contact Person Phone</label>
                                        <input type="number"
                                            class="form-control @error('manager_contact_person_phone') is-invalid @enderror"
                                            id="manager_contact_person_phone" name="manager_contact_person_phone"
                                            value="{{ old('manager_contact_person_phone', $client->manager_contact_person_phone ?? '') }}"
                                            placeholder="Contact Person Phone..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        {{-- @error('manager_contact_person_phone') --}}
                                        <div class="invalid-feedback error" id="manager_contact_person_phoneError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="rpsl">RPSL Details</label>
                                        <input type="text" class="form-control @error('rpsl') is-invalid @enderror"
                                            id="rpsl" name="rpsl" value="{{ old('rpsl', $client->rpsl ?? '') }}"
                                            placeholder="RPSL Details..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        {{-- @error('rpsl') --}}
                                        <div class="invalid-feedback error" id="rpslError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="manager_website">Website</label>
                                        <input type="text"
                                            class="form-control @error('manager_website') is-invalid @enderror"
                                            id="manager_website" name="manager_website"
                                            onchange="removeInvalidClass(this)" placeholder="Enter website URL..."
                                            autocomplete="off"
                                            value="{{ old('manager_website', $client->manager_website ?? '') }}">
                                        {{-- @error('manager_website') --}}
                                        <div class="invalid-feedback error" id="manager_websiteError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="manager_tax_information">Tax Information</label>
                                        <input type="text" name="manager_tax_information" id="manager_tax_information"
                                            class="form-control @error('manager_tax_information') is-invalid @enderror"
                                            placeholder="Enter information..."
                                            value="{{ old('manager_tax_information', $client->manager_tax_information ?? '') }}"
                                            onchange="removeInvalidClass(this)" autocomplete="off">
                                        {{-- @error('manager_tax_information') --}}
                                        <div class="invalid-feedback error" id="manager_tax_informationError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="manager_address">Address</label>
                                        <textarea name="manager_address" id="manager_address" rows="2"
                                            class="form-control @error('manager_address') is-invalid @enderror" onchange="removeInvalidClass(this)">{{ old('manager_address', $client->manager_address ?? '') }}</textarea>
                                        {{-- @error('manager_address') --}}
                                        <div class="invalid-feedback error" id="manager_addressError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="manager_logo">Ship Manager Logo</label>
                                        <input type="file"
                                            class="form-control @error('manager_logo') is-invalid @enderror"
                                            id="manager_logo" name="manager_logo" onchange="removeInvalidClass(this)"
                                            accept="image/*">
                                        {{-- @error('manager_logo') --}}
                                        <div class="invalid-feedback error" id="manager_logoError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="manager_initials">Ship Manager Initials</label>
                                        <input type="text"
                                            class="form-control @error('manager_initials') is-invalid @enderror"
                                            id="manager_initials" name="manager_initials"
                                            onchange="removeInvalidClass(this)" placeholder="Ship Manager Initials..."
                                            autocomplete="off"
                                            value="{{ old('manager_initials', $client->manager_initials ?? '') }}">
                                        {{-- @error('manager_initials') --}}
                                        <div class="invalid-feedback error" id="manager_initialsError"></div>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                @if (isset($client))
                                    <div class="col-12 mb-2"><img src="{{ $client->managerLogoPath }}" alt=""
                                            width="15%"></div>
                                @endif
                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="isSameAsManager"
                                            id="isSameAsManager" @if (isset($client) && $client->isSameAsManager == 1) checked @endif>
                                        <label class="custom-control-label" for="isSameAsManager">Same As SHIP
                                            MANAGER</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr class="mb-4">
                                </div>
                            </div>

                            <h5>Ship Owner Details</h5>

                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="owner_name">Name</label>
                                        <input type="text"
                                            class="form-control @error('owner_name') is-invalid @enderror"
                                            id="owner_name" value="{{ old('owner_name', $client->owner_name ?? '') }}"
                                            name="owner_name" placeholder="Ship Owner Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('owner_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="owner_email">Email</label>
                                        <input type="email"
                                            class="form-control @error('owner_email') is-invalid @enderror"
                                            id="owner_email" name="owner_email"
                                            value="{{ old('owner_email', $client->owner_email ?? '') }}"
                                            placeholder="Ship Owner Email..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('owner_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="owner_phone">Phone</label>
                                        <input type="number"
                                            class="form-control @error('owner_phone') is-invalid @enderror"
                                            id="owner_phone" name="owner_phone"
                                            value="{{ old('owner_phone', $client->owner_phone ?? '') }}"
                                            placeholder="Ship Owner Phone..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('owner_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="owner_address">Address</label>
                                        <textarea name="owner_address" id="owner_address" rows="2"
                                            class="form-control @error('owner_address') is-invalid @enderror">{{ old('owner_address', $client->owner_address ?? '') }}</textarea>
                                        @error('owner_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="IMO_ship_owner_details">IMO Ship owner details</label>
                                        <textarea name="IMO_ship_owner_details" id="IMO_ship_owner_details" rows="2"
                                            class="form-control @error('IMO_ship_owner_details') is-invalid @enderror">{{ old('IMO_ship_owner_details', $client->IMO_ship_owner_details ?? '') }}</textarea>
                                        @error('IMO_ship_owner_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="owner_contact_person_name">Contact Person Name</label>
                                        <input type="text"
                                            class="form-control @error('owner_contact_person_name') is-invalid @enderror"
                                            id="owner_contact_person_name"
                                            value="{{ old('owner_contact_person_name', $client->owner_contact_person_name ?? '') }}"
                                            name="owner_contact_person_name" placeholder="Contact Person Name..."
                                            autocomplete="off" onchange="removeInvalidClass(this)">
                                        @error('owner_contact_person_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="owner_contact_person_email">Email</label>
                                        <input type="email"
                                            class="form-control @error('owner_contact_person_email') is-invalid @enderror"
                                            id="owner_contact_person_email" name="owner_contact_person_email"
                                            value="{{ old('owner_contact_person_email', $client->owner_contact_person_email ?? '') }}"
                                            placeholder="Contact Person Email..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('owner_contact_person_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="owner_contact_person_phone">Phone</label>
                                        <input type="number"
                                            class="form-control @error('owner_contact_person_phone') is-invalid @enderror"
                                            id="owner_contact_person_phone" name="owner_contact_person_phone"
                                            value="{{ old('owner_contact_person_phone', $client->owner_contact_person_phone ?? '') }}"
                                            placeholder="Contact Person Phone..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('owner_contact_person_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="owner_website">Website</label>
                                        <input type="text"
                                            class="form-control @error('owner_website') is-invalid @enderror"
                                            id="owner_website" name="owner_website" onchange="removeInvalidClass(this)"
                                            placeholder="Enter website URL...">
                                        @error('owner_website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="owner_logo">Ship Owner Logo</label>
                                        <input type="file"
                                            class="form-control @error('owner_logo') is-invalid @enderror"
                                            id="owner_logo" name="owner_logo" onchange="removeInvalidClass(this)"
                                            accept="image/*" value="">
                                        @error('owner_logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if (isset($client))
                                    <div class="col-6"></div>
                                    <div class="col-6"><img src="{{ $client->ownerLogoPath }}" alt=""
                                            width="15%"></div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('clients') }}" type="button" class="btn pl-0"><i
                                                class="fas fa-arrow-left"></i> <b>Back</b></a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right formSubmitBtn"
                                            type="submit">{!! $button ?? 'Add' !!}</button>
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
            $('#isSameAsManager').click(function() {
                if ($(this).is(':checked')) {
                    // Copy manager details to owner details
                    $('#owner_name').val($('#manager_name').val());
                    $('#owner_email').val($('#manager_email').val());
                    $('#owner_phone').val($('#manager_phone').val());
                    $('#owner_address').val($('#manager_address').val());
                    $('#owner_contact_person_name').val($('#manager_contact_person_name').val());
                    $('#owner_contact_person_email').val($('#manager_contact_person_email').val());
                    $('#owner_contact_person_phone').val($('#manager_contact_person_phone').val());
                    $('#owner_website').val($('#manager_website').val());

                    var fileName = $("#manager_logo").val().split('\\').pop();
                    console.log(fileName);
                    $("#owner_logo").val(fileName);
                    // $('#manager_logo').trigger('change');
                } else {
                    // Clear owner fields if checkbox is unchecked
                    $('#owner_name, #owner_email, #owner_phone, #owner_address, #owner_contact_person_name, #owner_contact_person_email, #owner_contact_person_phone, #owner_website')
                        .val('');
                    $('#owner_logo').text('');
                }
            });


            $('#clientForm').submit(function(e) {
                e.preventDefault();

                var $submitButton = $(this).find('button[type="submit"]');
                var originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                // Clear previous error messages and invalid classes
                $('.error').empty().hide();
                $('input').removeClass('is-invalid');
                $('select').removeClass('is-invalid');

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('clients.store') }}", // Change this to the URL where you handle the form submission
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response
                        if (response.message) {
                            localStorage.setItem('message', response.message);
                        }
                        window.location.href = "{{ route('clients') }}";
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
@endsection
