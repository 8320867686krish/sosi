@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/cropper/dist/cropper.min.css">
    <style>
        #pdf-container {
            position: relative;
            width: 100%;
            overflow: auto;
        }

        #pdf-page {
            display: block;
            margin: auto;
        }

        #cropper-container {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
    </style>
@endsection
@section('content')

    <div class="container-fluid dashboard-content">
        <aside class="page-aside">
            <div class="aside-content">
                <div class="aside-header">
                    <button class="navbar-toggle" data-target=".aside-nav" data-toggle="collapse" type="button"><span
                            class="icon"><i class="fas fa-caret-down"></i></span></button><span class="title">Project
                        Information</span>
                    <p class="description">Service description</p>
                </div>
                <div class="aside-nav collapse">
                    <ul class="nav">
                        <li class="active">
                            <a href="#ship_particulars">
                                <span class="icon"><i class="fas fa-ship"></i></span>Ship Particulars
                            </a>
                        </li>
                        <li>
                            <a href="#create_vscp"><span class="icon"><i class="fas fa-fw fa-briefcase"></i></span>Create
                                VSCP</a>
                        </li>
                        <li>
                            <a href="#assign_project"><span class="icon"><i
                                        class="fas fa-fw fa-briefcase"></i></span>Assign Project</a>
                        </li>
                        <li>
                            <a href="#onboard_survey"><span class="icon"><i
                                        class="fas fa-fw fa-envelope"></i></span>Onboard Survey</a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <div class="main-content container-fluid p-0" id="ship_particulars">
            <div class="email-head">
                <div class="email-head-subject">
                    <div class="title"><a class="active" href="#"><span class="icon"><i
                                    class="fas fa-star"></i></span></a> <span>Ship Particulars</span>
                    </div>
                </div>
            </div>
            <div class="email-body">
                <div class="alert alert-success sucessMsg" role="alert" style="display: none" ;>
                    Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </a>
                </div>
                <form method="post" class="needs-validation" novalidate id="projectForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                    <div class="row mb-5">
                        <div class="col-offset-2 col-sm-12 col-md-6 col-lg-3">
                            <img id="previewImg" src="{{ $project->imagePath }}" height="200px" alt="Upload Image">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-2 pt-10">
                            <div class="form-group">
                                <input type="file" class="form-control  @error('image') is-invalid @enderror"
                                    id="image" name="image" autocomplete="off"
                                    onchange="previewFile(this); removeInvalidClass(this)" {{ $readonly }}
                                    accept="image/*">
                                <div class="invalid-feedback" id="imageError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="ship_name">Ship Name</label>
                                <input type="text" class="form-control  @error('ship_name') is-invalid @enderror"
                                    id="ship_name" value="{{ old('ship_name', $project->ship_name ?? '') }}"
                                    name="ship_name" placeholder="Ship Name..." autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }} required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="imo_number">Ship IMO Number</label>
                                <input type="text" class="form-control  @error('imo_number') is-invalid @enderror"
                                    id="imo_number" name="imo_number" placeholder="IMO Number..."
                                    value="{{ old('imo_number', $project->imo_number ?? '') }}">
                                @error('imo_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="call_sign">Call Sign</label>
                                <input type="text" class="form-control  @error('call_sign') is-invalid @enderror"
                                    id="call_sign" name="call_sign" placeholder="Call Sign..."
                                    value="{{ old('call_sign', $project->call_sign ?? '') }}">
                                @error('call_sign')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="manager_name">Manager Name</label>
                                <input type="text" class="form-control  @error('manager_name') is-invalid @enderror"
                                    id="manager_name"
                                    value="{{ old('manager_name', $project->client->manager_name ?? '') }}"
                                    name="manager_name" placeholder="Manager Name..." autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                @error('manager_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="client_id">Ship Owner</label>
                                <input type="hidden" name="client_id" id="client_id"
                                    value="{{ old('client_id', $project->client->id ?? '') }}">
                                <input type="text" class="form-control " name="owner_name" id="owner_name"
                                    value="{{ old('owner_name', $project->client->owner_name ?? '') }}">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="ship_type">Type of ship</label>
                                <input type="text" class="form-control  @error('ship_type') is-invalid @enderror"
                                    id="ship_type" name="ship_type"
                                    value="{{ old('ship_type', $project->ship_type ?? '') }}" placeholder="Ship Type..."
                                    autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }} required>
                                @error('ship_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="port_of_registry">Port Of Registry</label>
                                <input type="text"
                                    class="form-control  @error('port_of_registry') is-invalid @enderror"
                                    id="port_of_registry"
                                    value="{{ old('port_of_registry', $project->port_of_registry ?? '') }}"
                                    name="port_of_registry" placeholder="Port Of Registry" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="vessel_class">Vessel Class</label>
                                <input type="text" class="form-control  @error('vessel_class') is-invalid @enderror"
                                    id="vessel_class" value="{{ old('vessel_class', $project->vessel_class ?? '') }}"
                                    name="vessel_class" placeholder="Vessel Class" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="ihm_class">IHM Certifying Class</label>
                                <input type="text" class="form-control  @error('ihm_class') is-invalid @enderror"
                                    id="ihm_class" value="{{ old('ihm_class', $project->ihm_class ?? '') }}"
                                    name="ihm_class" placeholder="Ihm Class" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                @error('ihm_class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="flag_of_ship">Flag of ship</label>
                                <input type="text" class="form-control  @error('flag_of_ship') is-invalid @enderror"
                                    id="flag_of_ship" value="{{ old('flag_of_ship', $project->flag_of_ship ?? '') }}"
                                    name="flag_of_ship" placeholder="Flag of ship..." autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                @error('flag_of_ship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="delivery_date">Delivery Date</label>
                                <input type="date" class="form-control  @error('delivery_date') is-invalid @enderror"
                                    id="delivery_date" value="{{ old('delivery_date', $project->delivery_date ?? '') }}"
                                    name="delivery_date" placeholder="Delivery Date" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="building_details">Building Yard Details</label>
                                <input type="text"
                                    class="form-control  @error('building_details') is-invalid @enderror"
                                    id="building_details"
                                    value="{{ old('building_details', $project->building_details ?? '') }}"
                                    name="building_details" placeholder="Builder Details" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                @error('building_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="x_breadth_depth">Length x breadth x depth</label>
                                <input type="text"
                                    class="form-control  @error('x_breadth_depth') is-invalid @enderror"
                                    id="x_breadth_depth"
                                    value="{{ old('x_breadth_depth', $project->x_breadth_depth ?? '') }}"
                                    name="x_breadth_depth" placeholder="Length x breadth x depth" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                @error('x_breadth_depth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="gross_tonnage">Gross Registered Tonnage (GRT)</label>
                                <input type="text" class="form-control  @error('gross_tonnage') is-invalid @enderror"
                                    id="gross_tonnage" value="{{ old('gross_tonnage', $project->gross_tonnage ?? '') }}"
                                    name="gross_tonnage" placeholder="GRT" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                @error('grt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="vessel_previous_name">Vessel Previous Name (If Any) </label>
                                <input type="text"
                                    class="form-control  @error('vessel_previous_name') is-invalid @enderror"
                                    id="vessel_previous_name"
                                    value="{{ old('vessel_previous_name', $project->vessel_previous_name ?? '') }}"
                                    name="vessel_previous_name" placeholder="Vessel Previous Name" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                @error('vessel_previous_names')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <a href="{{ route('projects') }}" class="btn pl-0" type="button"><i
                                        class="fas fa-arrow-left"></i> <b>Back</b></a>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                @can('projects.edit')
                                    <button class="btn btn-primary float-right btn-rounded formSubmitBtn"
                                        type="submit">Save</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="main-content container-fluid p-0" id="create_vscp">
            @include('projects.addVscp')
        </div>

        <div class="main-content container-fluid p-0" id="assign_project">
            <div class="email-head">
                <div class="email-head-subject">
                    <div class="title"><a class="active" href="#"><span class="icon"><i
                                    class="fas fa-star"></i></span></a> <span>Assign Project</span>
                    </div>
                </div>
            </div>
            <div class="email-body">
                <div class="row">
                    <div class="col-8 offset-2">
                        <div class="alert alert-success sucessteamMsg" role="alert" style="display: none;">
                            Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </a>
                        </div>
                        <form method="post" action="#" class="needs-validation" novalidate id="teamsForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}">
                            <div class="form-group">
                                <label for="project_no">User</label>
                                <select
                                    class="selectpicker show-tick form-control form-control-lg @error('user_id') is-invalid @enderror"
                                    name="user_id[]" id="user_id" multiple data-live-search="true"
                                    data-actions-box="true" {{ $readonly }}>
                                    @if ($users->count() > 0)
                                        @foreach ($users as $user)
                                            @if (in_array($user->id, $project->user_id))
                                                <option value="{{ $user->id }}" selected>
                                                    {{ $user->name }}
                                                </option>
                                            @else
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assign_date">Assign Date</label>
                                <input type="date"
                                    class="form-control form-control-lg  @error('assign_date') is-invalid @enderror"
                                    id="assign_date" value="{{ old('assign_date', $project->assign_date[0] ?? '') }}"
                                    name="assign_date" autocomplete="off" onchange="removeInvalidClass(this)"
                                    {{ $readonly }}>
                            </div>
                            <div class="form-group">
                                <label for="assign_date">End Date</label>
                                <input type="date"
                                    class="form-control form-control-lg @error('end_date') is-invalid @enderror"
                                    id="end_date" value="{{ old('end_date', $project->end_date[0] ?? '') }}"
                                    name="end_date" autocomplete="off" onchange="removeInvalidClass(this)"
                                    {{ $readonly }}>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn pl-0" type="button"><i
                                                class="fas fa-arrow-left"></i> <b>Back</b></a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        @can('projects.edit')
                                            <button class="btn btn-primary float-right formteamButton"
                                                type="button">Save</button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content container-fluid p-0" id="onboard_survey">
            <div class="email-head">
                <div class="email-head-subject">
                    <div class="title"><a class="active" href="#"><span class="icon"><i
                                    class="fas fa-star"></i></span></a> <span>Onboard Survey</span>
                    </div>
                </div>
            </div>
            <div class="email-body">
                <div class="row">
                    <div class="col-8 offset-2">
                        <div class="alert alert-success sucessSurveylMsg" role="alert" style="display: none" ;>
                            Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </a>
                        </div>
                        <form method="post" action="#" class="needs-validation" novalidate id="SurveyForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                            <div class="form-group">
                                <label for="project_no">Survey Location Name</label>
                                <input type="text"
                                    class="form-control  form-control-lg @error('survey_location_name') is-invalid @enderror"
                                    id="survey_location_name"
                                    value="{{ old('survey_location_name', $project->survey_location_name ?? '') }}"
                                    name="survey_location_name" placeholder="Survey Location Name" autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                            <div class="form-group">
                                <label for="additional_hazmats">Survey Location Address</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('survey_location_address') is-invalid @enderror"
                                    id="survey_location_address"
                                    value="{{ old('survey_location_address', $project->survey_location_address ?? '') }}"
                                    name="survey_location_address" placeholder="Survey Location Address..."
                                    autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                            <div class="form-group">
                                <label for="client_name">Survey Type</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('survey_type') is-invalid @enderror"
                                    id="survey_type" name="survey_type"
                                    value="{{ old('survey_type', $project->survey_type ?? '') }}"
                                    placeholder="Survey Type..." autocomplete="off" onchange="removeInvalidClass(this)"
                                    {{ $readonly }}>
                            </div>
                            <div class="form-group">
                                <label for="survey_date">Survey Date</label>
                                <input type="date"
                                    class="form-control form-control-lg @error('survey_date') is-invalid @enderror"
                                    id="survey_date" value="{{ old('survey_date', $project->survey_date ?? '') }}"
                                    name="survey_date" placeholder="Survey Date.." autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn pl-0" type="button"><i
                                                class="fas fa-arrow-left"></i> <b>Back</b></a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        @can('projects.edit')
                                            <button class="btn btn-primary float-right SurveyFormButton"
                                                type="button">Save</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script src="{{ asset('assets/vendor/jquery.areaSelect.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script>
        function triggerFileInput(inputId) {
            $(`#${inputId}`).val('');
            document.getElementById(inputId).click();
        }

        async function convertToImage() {
            const pdfFile = document.getElementById('pdfFile').files[0];
            if (!pdfFile) {
                alert('Please select a PDF file.');
                return;
            }

            const fileReader = new FileReader();
            fileReader.onload = async function() {
                const pdfData = new Uint8Array(this.result);
                const pdf = await pdfjsLib.getDocument({
                    data: pdfData
                }).promise;

                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);
                    const viewport = page.getViewport({
                        scale: 1
                    });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    await page.render({
                        canvasContext: context,
                        viewport
                    }).promise;
                    $("#img-container").empty();
                    const imageData = canvas.toDataURL('image/png');
                    const img = document.createElement('img');
                    img.src = imageData;
                    img.classList.add('pdf-image'); // Add a class to the image
                    const container = document.getElementById('img-container');
                    container.appendChild(img);
                }

                // Bind event listeners after images are loaded
                $('.pdf-image').on('load', function() {
                    var options = {
                        deleteMethod: 'doubleClick',
                        handles: true,
                        onSelectEnd: function(image, selection) {
                            console.log("Selection End:", selection);
                        },
                        initAreas: []
                    };
                    $(this).areaSelect(options);
                });
            };
            fileReader.readAsArrayBuffer(pdfFile);
            $('#pdfModal').modal('show');

        }

        function previewFile(input) {
            let file = $("input[type=file]").get(0).files[0];

            if (file) {
                let reader = new FileReader();

                reader.onload = function() {
                    $("#previewImg").attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }

        function removeInvalidClass(input) {

            const isValid = input.value.trim() !== '';

            input.classList.toggle('is-invalid', !isValid);

            const errorMessageElement = input.parentElement.querySelector('.invalid-feedback');

            if (errorMessageElement) {
                errorMessageElement.style.display = isValid ? 'none' : 'block';
            }
        }

        $(document).ready(function() {
            // Hide all sections initially
            $('.main-content').hide();

            // Show the default section
            $('#ship_particulars').show();

            $('#pdfFile').change(function() {
                convertToImage();
            });

            // Handle click event on sidebar menu items
            $('.aside-nav .nav li a').click(function() {
                // Remove active class from all <li> tags
                $('.aside-nav .nav li').removeClass('active');
                // Add active class to the parent <li> tag
                $(this).parent('li').addClass('active');

                // Hide all sections
                $('.main-content').hide();
                // Get the ID of the section to show
                var targetId = $(this).attr('href');
                // Show the corresponding section
                $(targetId).show();
                // Prevent default anchor behavior
                return false;
            });

            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 15000);

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
                    },
                    complete: function() {
                        $(".formSubmitBtn").hide();
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

            $('#projectForm').submit(function(e) {
                e.preventDefault();

                // Clear previous error messages and invalid classes
                $('.error').empty().hide();
                $('input').removeClass('is-invalid');
                $('select').removeClass('is-invalid');

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ url('detail/save') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response
                        $(".sucessMsg").show();
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
                });
            });

            $('#getDeckCropImg').click(function() {
                let textareas = [];
                let areas = $('.pdf-image').areaSelect('get');
                let projectId = {{ $project->id }} || '';

                areas.forEach(area => {
                    var input = document.getElementById(area.id);
                    if (input) {
                        textareas.push({
                            ...area, // Copy existing area properties
                            'text': input.value // Add 'text' key with input value
                        });
                    }
                });

                let areasJSON = JSON.stringify(textareas);
                let images = document.querySelectorAll('.pdf-image');

                let imageFiles = [];
                images.forEach(function(image, index) {
                    // Convert the image data URL to a blob
                    fetch(image.src).then(res => res.blob())
                        .then(blob => {
                            // Create a new FormData object
                            var formData = new FormData();
                            formData.append('image', blob, 'page_' + (index + 1) + '.png');
                            formData.append('_token', '{{ csrf_token() }}');
                            formData.append('project_id', projectId);
                            formData.append('areas', areasJSON);

                            $.ajax({
                                type: 'POST',
                                url: "{{ url('project/save-image') }}",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    $('.deckView').html();
                                    $('.deckView').html(response.html);
                                    $("#img-container").empty();
                                    $('#pdfModal').modal('hide');
                                    $('body').removeClass('modal-open');
                                    $('.modal-backdrop').remove();
                                    console.log('Image saved successfully:',
                                        response);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Failed to save image:', error);
                                }
                            });
                        });
                });
            });

            $('#pdfModal').on('hidden.bs.modal', function(e) {
                $(this).find('.modal-body').empty(); // Clear modal body content
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });

        });
    </script>
@endsection
