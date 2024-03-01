@extends('layouts.app')

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Project Management</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('projects') }}"
                                        class="breadcrumb-link">Projects</a></li>
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
                    <h5 class="card-header">{{ $head_title ?? '' }} Projects</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('projects.store') }}" class="needs-validation" novalidate
                            id="projectForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="ship_owners_id">Ship Owner</label>
                                        <select name="ship_owners_id" id="ship_owners_id" class="form-control">
                                            <option value="">Select Ship Owner</option>
                                            @if (isset($owners) && $owners->count() > 0)
                                                @foreach ($owners as $owner)
                                                    <option value="{{ $owner->id }}"
                                                        data-identi="{{ $owner->identification }}"
                                                        {{ old('ship_owners_id') == $owner->id || (isset($project) && $project->ship_owners_id == $owner->id) ? 'selected' : '' }}>
                                                        {{ $owner->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="ship_name">Ship Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="ship_name" value="{{ old('ship_name', $project->ship_name ?? '') }}"
                                            name="ship_name" placeholder="Ship Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('ship_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="project_name">Ship Type</label>
                                        <input type="text" class="form-control @error('ship_type') is-invalid @enderror"
                                            id="ship_type" name="ship_type"
                                            value="{{ old('ship_type', $project->ship_type ?? '') }}"
                                            placeholder="Ship Type..." autocomplete="off"
                                            onchange="removeInvalidClass(this)">
                                        @error('ship_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="ihm_table">IHM Table</label>
                                        <select class="form-control @error('ihm_table') is-invalid @enderror" id="ihm_table"
                                            name="ihm_table" onchange="removeInvalidClass(this)">
                                            <option value="">Select IHM</option>
                                            <option value="IHM Part 1"
                                                {{ old('ihm_table') == 'IHM Part 1' || (isset($project) && $project->ihm_table == 'IHM Part 1') ? 'selected' : '' }}>
                                                IHM Part 1</option>
                                            <option value="IHM Part 2&3"
                                                {{ old('ihm_table') == 'IHM Part 2&3' || (isset($project) && $project->ihm_table == 'IHM Part 2&3') ? 'selected' : '' }}>
                                                IHM Part 2&3</option>
                                            <option value="IHM Gap Analysis"
                                                {{ old('ihm_table') == 'IHM Gap Analysis' || (isset($project) && $project->ihm_table == 'IHM Gap Analysis') ? 'selected' : '' }}>
                                                IHM Gap Analysis</option>
                                            <option value="IHM Additional Sampling"
                                                {{ old('ihm_table') == 'IHM Additional Sampling' || (isset($project) && $project->ihm_table == 'IHM Additional Sampling') ? 'selected' : '' }}>
                                                IHM Additional Sampling</option>
                                        </select>
                                        @error('ihm_table')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="imo_number">IMO Number</label>
                                        <input type="text" class="form-control @error('imo_number') is-invalid @enderror"
                                            id="imo_number" name="imo_number" placeholder="IMO Number..."
                                            value="{{ old('imo_number', $project->imo_number ?? '') }}">
                                        @error('imo_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="project_no">Project Number</label>
                                        <input type="text" class="form-control @error('project_no') is-invalid @enderror"
                                            id="project_no" name="project_no" placeholder="Project Number..."
                                            value="{{ old('project_no', $project->project_no ?? '') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn btn-info" type="button">Back</a>
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
    <script>
        function removeInvalidClass(input) {
            // Check if the input value is empty or whitespace only
            const isValid = input.value.trim() !== '';

            // Toggle the 'is-invalid' class based on the validity
            input.classList.toggle('is-invalid', !isValid);
        }

        // function generateProjectNo(){

        // }

        $(document).ready(function() {
            var ship_identi = $("#ship_owners_id").find('option:selected').data('identi');
            var imo = $('#imo_number').val();

            $('#imo_number').blur(function() {
                imo = $(this).val();
                $("#project_no").val("sosi/" + ship_identi + "/" + imo);
            });
            $('#ship_owners_id').change(function() {
                ship_identi = $(this).find('option:selected').data('identi');
                $("#project_no").val("sosi/" + ship_identi + (imo ? "/" + imo : ""));
            })
        });
    </script>
@endsection
