<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

    <form method="post" action="#" class="needs-validation" novalidate id="SurveyForm">
        @csrf

        <div class="row">
            <div class="form-group col-12 mt-3">
                @can('projects.edit')
                    <button class="btn btn-primary float-right" type="submit">Save</button>
                @endcan
            </div>
        </div>
        <div class="alert alert-success sucessSurveylMsg" role="alert" style="display: none" ;>
            Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </a>
        </div>
        <div class="accrodion-regular">
            <div id="accordion3">
                <div class="card">
                    <div class="card-header" id="headingSeven">
                        <h5 class="mb-0">
                            <button type="button" class="btn btn-link" data-toggle="collapse"
                                data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                <span class="fas fa-angle-down mr-3"></span>Assign Project
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeven" class="collapse show" aria-labelledby="headingSeven"
                        data-parent="#accordion3">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}"
                                    id="project_id">
                                <div class="form-group col-12 mb-3">
                                    <label for="project_no">User <span class="text-danger">*</span></label>
                                    <select
                                        class="selectpicker show-tick form-control form-control-lg @error('user_id') is-invalid @enderror"
                                        name="user_id[]" id="user_id" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }} onchange="removeInvalidClass(this)">
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
                                    <div class="invalid-feedback error" id="user_idError"></div>

                                </div>
                                <div class="form-group col-6">
                                    <label for="assign_date">Assign Date <span class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control form-control-lg  @error('assign_date') is-invalid @enderror"
                                        id="assign_date"
                                        value="{{ old('assign_date', $project->assign_date[0] ?? '') }}"
                                        name="assign_date" autocomplete="off" onchange="removeInvalidClass(this)"
                                        {{ $readonly }}>
                                    <div class="invalid-feedback error" id="assign_dateError"></div>

                                </div>
                                <div class="form-group col-6">
                                    <label for="assign_date">End Date <span class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control form-control-lg @error('end_date') is-invalid @enderror"
                                        id="end_date" value="{{ old('end_date', $project->end_date[0] ?? '') }}"
                                        name="end_date" autocomplete="off" onchange="removeInvalidClass(this)"
                                        {{ $readonly }}>
                                    <div class="invalid-feedback error" id="end_dateError"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingEight">
                        <h5 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                <span class="fas fa-angle-down mr-3"></span>Laboratory Assign
                            </button>
                        </h5>
                    </div>
                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion3">
                        <div class="card-body">
                            <h4>Laboratory1</h4>

                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label for="assign_date">Name</label>
                                    <input type="text"
                                        class="form-control form-control-lg  @error('laboratorie1') is-invalid @enderror"
                                        value="{{ old('laboratorie1', $project->laboratorie1 ?? '') }}"
                                        name="project[laboratorie1]" autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}
                                        placeholder="Lab Name">
                                </div>
                                <div class="form-group col-6 mb-3">
                                    <label for="excelReport"></label>
                                    <a href="{{ route('excelReport', ['project_id' => $project->id, 'isSample' => true]) }}"
                                        class="form-control btn btn-primary p-3" type="button">Genrate Lab List</a>
                                </div>

                                <div class="form-group col-6 mb-4">
                                    <label for="assign_date">Result1</label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('leb1LaboratoryResult1') is-invalid @enderror"
                                        id="leb1LaboratoryResult1"
                                        value="{{ old('leb1LaboratoryResult1', $project->leb1LaboratoryResult1 ?? '') }}"
                                        name="project[leb1LaboratoryResult1]" autocomplete="off" {{ $readonly }}
                                        onchange="removeInvalidClass(this)">
                                    @if ($project->leb1LaboratoryResult1)
                                        <label class="mt-2 docleb1LaboratoryResult1">
                                            <a href="{{ asset('images/labResult/' . $project->id . '/' . $project->leb1LaboratoryResult1) }}"
                                                target="_blank">
                                                {{ $project->leb1LaboratoryResult1 }}
                                            </a>
                                            <a href="javascript:;" class="ml-2 removeDoc"
                                                data-filed="leb1LaboratoryResult1">
                                                <i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
                                            </a>
                                        </label>
                                    @endif
                                    <div class="invalid-feedback error" id="leb1LaboratoryResult1Error"></div>
                                </div>

                                <div class="form-group col-6 mb-4">
                                    <label for="assign_date">Result 2</label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('leb1LaboratoryResult2') is-invalid @enderror"
                                        id="leb1LaboratoryResult2"
                                        value="{{ old('leb1LaboratoryResult2', $project->leb1LaboratoryResult2 ?? '') }}"
                                        name="project[leb1LaboratoryResult2]" autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}>
                                    @if ($project->leb1LaboratoryResult2)
                                        <label class="mt-2 docleb1LaboratoryResult2">
                                            <a href="{{ asset('images/labResult/' . $project->id . '/' . $project->leb1LaboratoryResult2) }}"
                                                target="_blank">
                                                {{ $project->leb1LaboratoryResult2 }}
                                            </a>
                                            <a href="javascript:;" class="ml-2 removeDoc"
                                                data-filed="leb1LaboratoryResult2">
                                                <i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
                                            </a>
                                        </label>
                                    @endif
                                    <div class="invalid-feedback error" id="leb1LaboratoryResult2Error"></div>
                                </div>

                            </div>
                            <div class=" border-top">
                                <h4 class="mt-3">Laboratory2</h4>
                            </div>
                            <div class="row">

                                <div class="form-group col-6 mb-1">
                                    <label for="assign_date">Name</label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('laboratorie2') is-invalid @enderror"
                                        id="laboratorie2"
                                        value="{{ old('laboratorie2', $project->laboratorie2 ?? '') }}"
                                        name="project[laboratorie2]" autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}
                                        placeholder="Lab Name">
                                </div>

                                <div class="form-group col-6  mb-1">
                                    <label for="assign_date">Upload Lab List</label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('leb2LabList') is-invalid @enderror"
                                        id="leb2LabList"
                                        value="{{ old('leb2LabList', $project->leb2LabList ?? '') }}"
                                        name="project[leb2LabList]" autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}>

                                    @if ($project->leb2LabList)
                                        <label class="mt-2 docleb2LabList">
                                            <a href="{{ asset('images/labResult/' . $project->id . '/' . $project->leb2LabList) }}"
                                                target="_blank">
                                                {{ $project->leb2LabList }}
                                            </a>
                                            <a href="javascript:;" class="ml-2 removeDoc" data-filed="leb2LabList">
                                                <i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
                                            </a>
                                        </label>
                                    @endif
                                </div>

                                <div class="form-group col-6">
                                    <label for="assign_date">Result1</label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('leb2LaboratoryResult1') is-invalid @enderror"
                                        id="leb2LaboratoryResult1"
                                        value="{{ old('leb2LaboratoryResult1', $project->leb2LaboratoryResult1 ?? '') }}"
                                        name="project[leb2LaboratoryResult1]" autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}>
                                    @if ($project->leb2LaboratoryResult1)
                                        <label class="mt-2 docleb2LaboratoryResult1">
                                            <a href="{{ asset('images/labResult/' . $project->id . '/' . $project->leb2LaboratoryResult1) }}"
                                                target="_blank">
                                                {{ $project->leb2LaboratoryResult1 }}
                                            </a>
                                            <a href="javascript:;" class="ml-2 removeDoc"
                                                data-filed="leb2LaboratoryResult1">
                                                <i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
                                            </a>
                                        </label>
                                    @endif
                                    <div class="invalid-feedback error" id="leb2LaboratoryResult1Error"></div>
                                </div>


                                <div class="form-group col-6">
                                    <label for="assign_date">Result 2</label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('leb2LaboratoryResult2') is-invalid @enderror"
                                        id="leb2LaboratoryResult2"
                                        value="{{ old('leb2LaboratoryResult2', $project->leb2LaboratoryResult2 ?? '') }}"
                                        name="project[leb2LaboratoryResult2]" autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}>
                                    @if ($project->leb2LaboratoryResult2)
                                        <label class="mt-2 docleb2LaboratoryResult2">
                                            <a href="{{ asset('images/labResult/' . $project->id . '/' . $project->leb2LaboratoryResult2) }}"
                                                target="_blank">
                                                {{ $project->leb2LaboratoryResult2 }}
                                            </a>
                                            <a href="javascript:;" class="ml-2 removeDoc"
                                                data-filed="leb2LaboratoryResult2">
                                                <i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
                                            </a>
                                        </label>
                                    @endif
                                    <div class="invalid-feedback error" id="leb2LaboratoryResult2Error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingNine">
                        <h5 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                <span class="fas fa-angle-down mr-3"></span>OnBoard Survey
                            </button>
                        </h5>
                    </div>
                    <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion3">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                                <div class="form-group col-6">
                                    <label for="project_no">Survey Location Name</label>
                                    <input type="text"
                                        class="form-control  form-control-lg @error('survey_location_name') is-invalid @enderror"
                                        id="survey_location_name"
                                        value="{{ old('survey_location_name', $project->survey_location_name ?? '') }}"
                                        name="project[survey_location_name]" placeholder="Survey Location Name"
                                        autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-3">
                                    <label for="additional_hazmats">Survey Location Address</label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('survey_location_address') is-invalid @enderror"
                                        id="survey_location_address"
                                        value="{{ old('survey_location_address', $project->survey_location_address ?? '') }}"
                                        name="project[survey_location_address]"
                                        placeholder="Survey Location Address..." autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-3">
                                    <label for="client_name">Survey Type</label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('survey_type') is-invalid @enderror"
                                        id="survey_type" name="project[survey_type]"
                                        value="{{ old('survey_type', $project->survey_type ?? '') }}"
                                        placeholder="Survey Type..." autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="survey_date">Survey Date</label>
                                    <input type="date"
                                        class="form-control form-control-lg @error('survey_date') is-invalid @enderror"
                                        id="survey_date"
                                        value="{{ old('survey_date', $project->survey_date ?? '') }}"
                                        name="project[survey_date]" placeholder="Survey Date.." autocomplete="off"
                                        onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('js')

    <script>
        function uploadFileCheck(fileInputId, errorMsgId) {
            var fileInput = $(`#${fileInputId}`)[0];
            var file = fileInput.files[0];

            if (!file) {
                console.log('Please select a file.');
                return;
            }

            let formData = new FormData();
            formData.append(fileInputId, file);

            $.ajax({
                url: "{{ route('checkLaboratoryFile') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response.isStatus);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = 'An error occurred: ';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage += jqXHR.responseJSON.message;
                    } else {
                        errorMessage += errorThrown;
                    }
                    $(`#${errorMsgId}`).empty().text(errorMessage).show();

                    fileInput.value = '';
                }
            });
        }

        $(".removeDoc").click(function(e) {
            var type = $(this).attr('data-filed');
            var project_id = $("#project_id").val();
            e.preventDefault();
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "post",
                            url: "{{ url('remove/lebDoc') }}",
                            data: {
                                project_id: project_id,
                                type: type
                            }, // Convert data object to JSON string
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(msg) {
                                $(".doc" + type).remove();
                            }
                        });
                        swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    } else {
                        swal("Cancelled", "Your imaginary file is safe :)", "error");
                    }
                });
        });

        $('#SurveyForm').submit(function(e) {
            e.preventDefault();
            var form = $(this); // Get the form element
            var formData = new FormData(form[0]); // Create FormData object from the form
            $.ajax({
                type: "POST",
                url: "{{ url('detail/assignProject') }}",
                data: formData,
                contentType: false,
                processData: false,

                success: function(msg) {
                    $(".sucessSurveylMsg").show();
                },
                error: function(err) {
                    var errors = err.responseJSON.errors;
                    $("#collapseSeven").show();
                    $.each(errors, function(field, messages) {
                        $('#' + field + 'Error').text(messages[0]).show();
                        $('[name="' + field + '"]').addClass('is-invalid');

                    });
                },
                complete: function() {
                    $(".formSubmitBtn").hide();
                }
            });
        });

        $('#leb1LaboratoryResult1').on('change', function() {
            uploadFileCheck("leb1LaboratoryResult1", "leb1LaboratoryResult1Error");
        });

        $('#leb1LaboratoryResult2').on('change', function() {
            uploadFileCheck("leb1LaboratoryResult2", "leb1LaboratoryResult2Error");
        });

        $('#leb2LaboratoryResult1').on('change', function() {
            uploadFileCheck("leb2LaboratoryResult1", "leb2LaboratoryResult1Error");
        });

        $('leb2LaboratoryResult2').on('change', function() {
            uploadFileCheck("leb2LaboratoryResult2", "leb2LaboratoryResult2Error");
        });

        // document.getElementById('leb1LaboratoryResult1').addEventListener('change', function(event) {
        //     handleFileUpload(event);
        // });

        // document.getElementById('leb1LaboratoryResult2').addEventListener('change', function(event) {
        //     handleFileUpload(event);
        // });

        // document.getElementById('leb2LaboratoryResult1').addEventListener('change', function(event) {
        //     handleFileUpload(event);
        // });

        // document.getElementById('leb2LaboratoryResult2').addEventListener('change', function(event) {
        //     handleFileUpload(event);
        // });

        // function handleFileUpload(event) {
        //     const file = event.target.files[0];
        //     if (file) {
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             const arrayBuffer = e.target.result;
        //             const uint8Array = new Uint8Array(arrayBuffer);

        //             if (isEncrypted(uint8Array, file.type)) {
        //                 swal({
        //                     title: "File Upload Error!",
        //                     text: "This file appears to be encrypted and cannot be uploaded.",
        //                     timer: 5000
        //                 });
        //                 event.target.value = ''; // Reset the file input
        //             }
        //         };
        //         reader.readAsArrayBuffer(file);
        //     }
        // }

        // function isEncrypted(uint8Array, fileType) {
        //     if (fileType === 'application/pdf') {
        //         return isPdfEncrypted(uint8Array);
        //     }
        //     // Add checks for other file types here if needed
        //     return false;
        // }

        // function isPdfEncrypted(uint8Array) {
        //     // Check for PDF encryption by looking for /Encrypt keyword
        //     const text = new TextDecoder().decode(uint8Array);
        //     return /\/Encrypt\s/.test(text);
        // }
    </script>
@endpush('js')
