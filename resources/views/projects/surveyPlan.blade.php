<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

    <form method="post" action="#" class="needs-validation" novalidate id="SurveyForm">
        @csrf
       
        <div class="row">
            <div class="form-group col-12 mt-3">
                @can('projects.edit')
                <button class="btn btn-primary float-right formteamButton" type="button">Save</button>
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
                            <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                <span class="fas fa-angle-down mr-3"></span>Assign Project
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeven" class="collapse show" aria-labelledby="headingSeven" data-parent="#accordion3">
                        <div class="card-body">


                            <div class="row">
                                <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}">
                                <div class="form-group col-12">
                                    <label for="project_no">User</label>
                                    <select class="selectpicker show-tick form-control form-control-lg @error('user_id') is-invalid @enderror" name="user_id[]" id="user_id" multiple data-live-search="true" data-actions-box="true" {{ $readonly }} onchange="removeInvalidClass(this)">
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
                                    <label for="assign_date">Assign Date</label>
                                    <input type="date" class="form-control form-control-lg  @error('assign_date') is-invalid @enderror" id="assign_date" value="{{ old('assign_date', $project->assign_date[0] ?? '') }}" name="assign_date" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                    <div class="invalid-feedback error" id="assign_dateError"></div>
                                    
                                </div>
                                <div class="form-group col-6">
                                    <label for="assign_date">End Date</label>
                                    <input type="date" class="form-control form-control-lg @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date', $project->end_date[0] ?? '') }}" name="end_date" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }} >
                                    <div class="invalid-feedback error" id="end_dateError"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingEight">
                        <h5 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                <span class="fas fa-angle-down mr-3"></span>Laboratry Assign
                            </button>
                        </h5>
                    </div>
                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion3">
                        <div class="card-body row">
                            <div class="form-group col-6">
                                <label for="assign_date">Laboratory 1</label>
                                <input type="text" class="form-control form-control-lg  @error('laboratorie1') is-invalid @enderror" value="{{ old('laboratorie1', $project->laboratorie1 ?? '') }}" name="project[laboratorie1]" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                            <div class="form-group col-6">
                                <label for="assign_date">Laboratory 2</label>
                                <input type="text" class="form-control form-control-lg @error('laboratorie2') is-invalid @enderror" id="laboratorie2" value="{{ old('laboratorie2', $project->laboratorie2 ?? '') }}" name="project[laboratorie2]" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingNine">
                        <h5 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
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
                                    <input type="text" class="form-control  form-control-lg @error('survey_location_name') is-invalid @enderror" id="survey_location_name" value="{{ old('survey_location_name', $project->survey_location_name ?? '') }}" name="project[survey_location_name]" placeholder="Survey Location Name" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="additional_hazmats">Survey Location Address</label>
                                    <input type="text" class="form-control form-control-lg @error('survey_location_address') is-invalid @enderror" id="survey_location_address" value="{{ old('survey_location_address', $project->survey_location_address ?? '') }}" name="project[survey_location_address]" placeholder="Survey Location Address..." autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="client_name">Survey Type</label>
                                    <input type="text" class="form-control form-control-lg @error('survey_type') is-invalid @enderror" id="survey_type" name="project[survey_type]" value="{{ old('survey_type', $project->survey_type ?? '') }}" placeholder="Survey Type..." autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="survey_date">Survey Date</label>
                                    <input type="date" class="form-control form-control-lg @error('survey_date') is-invalid @enderror" id="survey_date" value="{{ old('survey_date', $project->survey_date ?? '') }}" name="project[survey_date]" placeholder="Survey Date.." autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
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
    // $(".SurveyFormButton").click(function() {
    //     $('span').html("");

    //     $.ajax({
    //         type: "POST",
    //         url: "{{ url('detail/save') }}",
    //         data: $("#SurveyForm").serialize(),
    //         success: function(msg) {
    //             $(".sucessSurveylMsg").show();
    //         }
    //     });
    // });

    $(".formteamButton").click(function() {
        $.ajax({
            type: "POST",
            url: "{{ url('detail/assignProject') }}",
            data: $("#SurveyForm").serialize(),
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
</script>
@endpush('js')