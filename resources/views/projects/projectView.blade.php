@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-select/css/bootstrap-select.css') }}">
@endsection

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Project Management</h2>
                    {{-- <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('projects') }}"
                                        class="breadcrumb-link">Projects</a></li>
                                <li class="breadcrumb-item active"><a href="#"
                                        class="breadcrumb-link">{{ $head_title ?? 'Ship Particulars' }}</a></li>
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
            <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div class="card">
                    <h5 class="card-header">{{ $head_title ?? '' }} Projects</h5>
                    <div class="card-body">
                        <div class="alert alert-success sucessMsg" role="alert" style="display: none" ;>
                            Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </a>
                        </div>
                        <form method="post" action="{{ route('projects.store') }}" class="needs-validation" novalidate
                            id="projectForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="ship_name">Name</label>
                                        <input type="text" class="form-control @error('ship_name') is-invalid @enderror"
                                            id="ship_name" value="{{ old('ship_name', $project->ship_name ?? '') }}"
                                            name="ship_name" placeholder="Ship Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }} required>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="manager_name">Manager Name</label>
                                        <input type="text"
                                            class="form-control @error('manager_name') is-invalid @enderror"
                                            id="manager_name"
                                            value="{{ old('manager_name', $project->manager_name ?? '') }}"
                                            name="manager_name" placeholder="Manager Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                        @error('manager_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="client_id">Ship Owner</label>
                                        <select name="client_id" id="client_id" class="form-control" {{ $readonly }}>
                                            <option value="" {{ empty($readonly) ? 'disabled' : '' }}>Select Client</option>
                                            @if (isset($clients) && $clients->count() > 0)
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}" data-identi="{{ $client->identification }}"
                                                        {{ old('client_id') == $client->id || (isset($project) && $project->client_id == $client->id) ? 'selected' : '' }}
                                                        {{ empty($readonly) ? '' : 'disabled' }}>
                                                        {{ $client->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="project_name">Ship Type</label>
                                        <input type="text" class="form-control @error('ship_type') is-invalid @enderror"
                                            id="ship_type" name="ship_type"
                                            value="{{ old('ship_type', $project->ship_type ?? '') }}"
                                            placeholder="Ship Type..." autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }} required>
                                        @error('ship_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="manager_name">Manager Details</label>
                                        <input type="text"
                                            class="form-control @error('manager_details') is-invalid @enderror"
                                            id="manager_name"
                                            value="{{ old('manager_details', $project->manager_details ?? '') }}"
                                            name="manager_details" placeholder="Manager Details.." autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="dimensions">Dimensions</label>
                                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror"
                                            id="dimensions" value="{{ old('dimensions', $project->dimensions ?? '') }}"
                                            name="dimensions" placeholder="Dimensions" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="builder_name">Builder Name</label>
                                        <input type="text"
                                            class="form-control @error('builder_name') is-invalid @enderror"
                                            id="builder_name"
                                            value="{{ old('builder_name', $project->builder_name ?? '') }}"
                                            name="builder_name" placeholder="Builder Name" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                        @error('builder_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="builder_name">Builder Details</label>
                                        <input type="text"
                                            class="form-control @error('builder_details') is-invalid @enderror"
                                            id="builder_details"
                                            value="{{ old('builder_details', $project->builder_details ?? '') }}"
                                            name="builder_details" placeholder="Builder Details" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                        @error('builder_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="build_date">Builder Date</label>
                                        <input type="text"
                                            class="form-control @error('build_date	') is-invalid @enderror"
                                            id="build_date	" value="{{ old('build_date', $project->build_date ?? '') }}"
                                            name="build_date" placeholder="Build Date" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                        @error('builder_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="builder_name">Flag</label>
                                        <input type="text" class="form-control @error('flag') is-invalid @enderror"
                                            id="builder_name" value="{{ old('flag', $project->flag ?? '') }}"
                                            name="flag" placeholder="Flag" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                        @error('flag')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="gross_tonnage">Gross Tonnage</label>
                                        <input type="text"
                                            class="form-control @error('gross_tonnage') is-invalid @enderror"
                                            id="gross_tonnage"
                                            value="{{ old('gross_tonnage', $project->gross_tonnage ?? '') }}"
                                            name="gross_tonnage" placeholder="Gross Tonnage" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                        @error('builder_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="build_date">Delivery Date</label>
                                        <input type="text"
                                            class="form-control @error('delivery_date') is-invalid @enderror"
                                            id="delivery_date"
                                            value="{{ old('delivery_date', $project->delivery_date ?? '') }}"
                                            name="delivery_date" placeholder="Delivery Date" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="gross_tonnage">Port Of Registry</label>
                                        <input type="text"
                                            class="form-control @error('port_of_registry') is-invalid @enderror"
                                            id="port_of_registry"
                                            value="{{ old('port_of_registry', $project->port_of_registry ?? '') }}"
                                            name="port_of_registry" placeholder="Port Of Registry" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="vessal_class">Vessal Class</label>
                                        <input type="text"
                                            class="form-control @error('vessal_class') is-invalid @enderror"
                                            id="vessal_class"
                                            value="{{ old('vessal_class', $project->vessal_class ?? '') }}"
                                            name="vessal_class" placeholder="Vessal Class" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="builder_name">Ihm Class</label>
                                        <input type="text"
                                            class="form-control @error('ihm_class') is-invalid @enderror" id="ihm_class"
                                            value="{{ old('flag', $project->ihm_class ?? '') }}" name="ihm_class"
                                            placeholder="Ihm Class" autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>
                                        @error('flag')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn btn-info btn-rounded formBackBtn"
                                            type="button">Back</a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        @can('projects.edit')
                                            <button class="btn btn-primary float-right btn-rounded formSubmitBtn"
                                                type="button" style="display:none">Edit</button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Assign Project</h5>
                    <div class="card-body">
                        <div class="alert alert-success sucessteamMsg" role="alert" style="display: none" ;>
                            Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </a>
                        </div>
                        <form method="post" action="#" class="needs-validation" novalidate id="teamsForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="project_no">User</label>
                                        <select
                                            class="selectpicker show-tick form-control @error('user_id') is-invalid @enderror"
                                            name="user_id[]" id="user_id" multiple data-live-search="true"
                                            data-actions-box="true" {{ $readonly }}>
                                            @if ($users->count() > 0)
                                                @foreach ($users as $user)
                                                    @if (in_array($user->id, $project->user_id))
                                                        <option value="{{ $user->id }}" selected>{{ $user->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="assign_date">Assign Date</label>
                                        <input type="date" class="form-control @error('assign_date') is-invalid @enderror" id="assign_date" value="{{ old('assign_date', $project->assign_date[0] ?? '') }}" name="assign_date" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }} >
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="assign_date">End Date</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date', $project->end_date[0] ?? '') }}" name="end_date" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }} >
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn pl-0" type="button"><i class="fas fa-arrow-left"></i> <b>Back</b></a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        @can('projects.edit')
                                            <button class="btn btn-primary float-right btn-rounded formteamButton"
                                                type="button" style="display:none">Edit</button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div class="card">
                    <h5 class="card-header">Survey Information</h5>
                    <div class="card-body">
                        <div class="alert alert-success sucessSurveylMsg" role="alert" style="display: none" ;>
                            Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </a>
                        </div>
                        <form method="post" action="#" class="needs-validation" novalidate id="SurveyForm">
                            @csrf

                            <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="project_no">Survey Location Name</label>
                                        <input type="text"
                                            class="form-control @error('survey_location_name') is-invalid @enderror"
                                            id="survey_location_name"
                                            value="{{ old('survey_location_name', $project->survey_location_name ?? '') }}"
                                            name="survey_location_name" placeholder="Survey Location Name"
                                            autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="additional_hazmats">Survey Location Address</label>
                                        <input type="text"
                                            class="form-control @error('survey_location_address') is-invalid @enderror"
                                            id="survey_location_address"
                                            value="{{ old('survey_location_address', $project->additional_hazmats ?? '') }}"
                                            name="survey_location_address" placeholder="Survey Location Address..."
                                            autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    </div>
                                </div>


                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="client_name">Survey Type</label>
                                        <input type="text"
                                            class="form-control @error('survey_type') is-invalid @enderror"
                                            id="survey_type" name="survey_type"
                                            value="{{ old('survey_type', $project->survey_type ?? '') }}"
                                            placeholder="Survey Type..." autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="survey_date">Survey Date</label>
                                        <input type="text"
                                            class="form-control @error('survey_date') is-invalid @enderror"
                                            id="survey_date"
                                            value="{{ old('survey_date', $project->survey_date ?? '') }}"
                                            name="survey_date" placeholder="Survey Date.." autocomplete="off"
                                            onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn btn-info btn-rounded formBackBtn"
                                            type="button">Back</a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        @can('projects.edit')
                                            <button class="btn btn-primary float-right btn-rounded SurveyFormButton"
                                                type="button" style="display:none">Edit</button>
                                        @endcan
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
    <script src="{{ asset('assets/vendor/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script>
        function removeInvalidClass(input) {
            // Check if the input value is empty or whitespace only
            const isValid = input.value.trim() !== '';

            // Toggle the 'is-invalid' class based on the validity
            input.classList.toggle('is-invalid', !isValid);
        }

        $(document).ready(function() {

            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 15000);

            var ship_identi = $("#client_id").find('option:selected').data('identi');
            var imo = $('#imo_number').val();

            $('#imo_number').blur(function() {
                imo = $(this).val();
                $("#project_no").val("sosi/" + ship_identi + "/" + imo);
            });
            $('#client_id').change(function() {
                ship_identi = $(this).find('option:selected').data('identi');
                $("#project_no").val("sosi/" + ship_identi + (imo ? "/" + imo : ""));
            })

            $("#projectForm").on("input", function() {
                $(".formSubmitBtn").show();
            });

            $("#projectForm").on("input", function() {
                $(".formSubmitBtn").show();
            });

            $("#teamsForm").on("input select", function() {
                $(".formteamButton").show();
            });

            $("#SurveyForm").on("input", function() {
                $(".SurveyFormButton").show();
            });
            $(".SurveyFormButton").click(function() {
                $('span').html("");

                $.ajax({
                    type: "POST",
                    url: "{{ url('detail/save') }}",
                    data: $("#SurveyForm").serialize(),
                    success: function(msg) {
                        $(".sucessSurveylMsg").show();
                    }
                });
            });

            $(".formteamButton").click(function() {
                // $('span').html("");
                $.ajax({
                    type: "POST",
                    url: "{{ url('detail/assignProject') }}",
                    data: $("#teamsForm").serialize(),
                    success: function(msg) {
                        $(".sucessteamMsg").text(msg.message);
                        $(".sucessteamMsg").show();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            $(".formgenralButton").click(function() {
                $('span').html("");

                $.ajax({
                    type: "POST",
                    url: "{{ url('detail/save') }}",
                    data: $("#genralForm").serialize(),
                    success: function(msg) {
                        $(".sucessgenralMsg").show();
                    }
                });
            });
            $(".formSubmitBtn").click(function(e) {
                $('.error-message').remove("");
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ url('detail/save') }}",
                    data: $("#projectForm").serialize(),
                    success: function(msg) {
                        $(".sucessMsg").show();
                    },
                    error: function(err) {
                        $.each(err.responseJSON.errors, function(i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="error-message" style="color: red;">' +
                                error[0] + '</span>'));
                        })
                    }

                });
            });
        });
    </script>
@endsection
