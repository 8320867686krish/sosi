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
                    <h2 class="pageheader-title">Document Declaration Management</h2>
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
                    <h5 class="card-header">{{ $head_title ?? '' }} Document</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('documentdeclaration.store') }}" class="needs-validation" novalidate id="makeModelForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $model->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="hazmat_id">Hazmat <span class="text-danger">*</span></label>
                                        <select name="hazmat_id" id="hazmat_id" class="form-control" onchange="removeInvalidClass(this)">
                                            <option value="">Select hazmat</option>
                                            @if (isset($hazmats) && $hazmats->count() > 0)
                                                @foreach ($hazmats as $hazmat)
                                                    <option value="{{ $hazmat->id }}" {{ old('hazmat_id') == $hazmat->id || (isset($model) && $model->hazmat_id == $hazmat->id) ? 'selected' : '' }} >
                                                        {{ $hazmat->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="invalid-feedback error" id="hazmat_idError"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="equipment">Equipment <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="equipment" value="{{ old('equipment', $model->equipment ?? '') }}" name="equipment" autocomplete="off" onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="equipmentError"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="model">Model <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="model" value="{{ old('model', $model->model ?? '') }}" name="model" autocomplete="off" onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="modelError"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="make">Make <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                            id="make" name="make" value="{{ old('make', $model->make ?? '') }}" autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                            <div class="invalid-feedback error" id="makeError"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="manufacturer">Manufacturer <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                            id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $model->manufacturer ?? '') }}" autocomplete="off" onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="manufacturerError"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="part">Part <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="part" name="part" value="{{ old('part', $model->part ?? '') }}" autocomplete="off" onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="partError"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="document1">Document 1 <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="document1" name="document1" onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="document1Error"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="document2">Document 2</label>
                                        <input type="file" class="form-control" id="document2" name="document2" onchange="removeInvalidClass(this)">
                                        <div class="invalid-feedback error" id="document2Error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('documentdeclaration') }}" id="modelBackBtn" type="button"><i class="fas fa-arrow-left"></i> <b>Back</b></a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right" id="modelFormSubmitBtn" type="submit">{{ $button }}</button>
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
            $('#makeModelForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                let $submitButton = $(this).find('button[type="submit"]');
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                // Clear previous error messages and invalid classes
                $('.error').empty().hide();
                $('input').removeClass('is-invalid');
                $('select').removeClass('is-invalid');

                // Serialize form data
                let formData = new FormData(this);

                let formAction = $(this).attr('action');

                // Submit form via AJAX
                $.ajax({
                    url: formAction,
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.isStatus) {
                            localStorage.setItem('message', response.message);
                        }
                        window.location.href = "{{ route('documentdeclaration') }}";
                    },
                    error: function(xhr, status, error) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(field, messages) {
                                $('#' + field + 'Error').text(messages[0]).show();
                                $('[name="' + field + '"]').addClass('is-invalid');
                            });
                        } else {
                            console.error('Error submitting form:', error);
                        }
                    },
                    complete: function() {
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
