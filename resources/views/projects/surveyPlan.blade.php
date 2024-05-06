<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                            <span class="fas fa-angle-down mr-3"></span>Assign Project
                        </button>
                    </h5>
                </div>
                <div id="collapseSeven" class="collapse show" aria-labelledby="headingSeven" data-parent="#accordion3">
                    <div class="card-body">
                        <p class="lead"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.</p>
                        <p> Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                        <a href="#" class="btn btn-secondary">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header" id="headingEight">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <span class="fas fa-angle-down mr-3"></span>Laboratry Assign
                        </button>
                    </h5>
                </div>
                <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion3">
                    <div class="card-body">
                        Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header" id="headingNine">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <span class="fas fa-angle-down mr-3"></span>OnBoard Survey
                        </button>
                    </h5>
                </div>
                <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion3">
                    <div class="card-body">
                        <form method="post" action="#" class="needs-validation" novalidate id="SurveyForm">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                                <div class="form-group col-6">
                                    <label for="project_no">Survey Location Name</label>
                                    <input type="text" class="form-control  form-control-lg @error('survey_location_name') is-invalid @enderror" id="survey_location_name" value="{{ old('survey_location_name', $project->survey_location_name ?? '') }}" name="survey_location_name" placeholder="Survey Location Name" autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="additional_hazmats">Survey Location Address</label>
                                    <input type="text" class="form-control form-control-lg @error('survey_location_address') is-invalid @enderror" id="survey_location_address" value="{{ old('survey_location_address', $project->survey_location_address ?? '') }}" name="survey_location_address" placeholder="Survey Location Address..." autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="client_name">Survey Type</label>
                                    <input type="text" class="form-control form-control-lg @error('survey_type') is-invalid @enderror" id="survey_type" name="survey_type" value="{{ old('survey_type', $project->survey_type ?? '') }}" placeholder="Survey Type..." autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="survey_date">Survey Date</label>
                                    <input type="date" class="form-control form-control-lg @error('survey_date') is-invalid @enderror" id="survey_date" value="{{ old('survey_date', $project->survey_date ?? '') }}" name="survey_date" placeholder="Survey Date.." autocomplete="off" onchange="removeInvalidClass(this)" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn pl-0" type="button"><i class="fas fa-arrow-left"></i> <b>Back</b></a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        @can('projects.edit')
                                        <button class="btn btn-primary float-right SurveyFormButton" type="button">Save</button>
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
</div>
@push('js')
<script>
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
</script>
@endpush('js')