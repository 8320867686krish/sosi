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
                    {{-- <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('projects') }}"
                                        class="breadcrumb-link">Projects</a></li>
                                <li class="breadcrumb-item active"><a href="#"
                                        class="breadcrumb-link">{{ $head_title ?? 'Add' }}</a></li>
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
                    <h5 class="card-header">{{ $head_title ?? '' }} Projects</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('projects.store') }}" class="needs-validation" novalidate
                            id="projectForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="client_id">Client <span class="text-danger">*</span></label>
                                        <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" onchange="removeInvalidClass(this)">
                                            <option value="">Select Client</option>
                                            @if (isset($clients) && $clients->count() > 0)
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}"
                                                        data-identi="{{ $client->manager_initials }}"
                                                        {{ old('client_id') == $client->id || (isset($project) && $project->client_id == $client->id) ? 'selected' : '' }}>
                                                        {{ $client->manager_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="ship_name">Ship Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('ship_name') is-invalid @enderror"
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
                                        <label for="imo_number">IMO Number <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('imo_number') is-invalid @enderror"
                                            id="imo_number" name="imo_number" placeholder="IMO Number..."
                                            value="{{ old('imo_number', $project->imo_number ?? '') }}" onchange="removeInvalidClass(this)">
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
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="project_no">Ship Initiate <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('ship_initiate') is-invalid @enderror"
                                            id="ship_initiate" name="ship_initiate" placeholder="Ship Initiate..."
                                            value="{{ old('ship_initiate', $project->ship_initiate ?? '') }}"  onchange="removeInvalidClass(this)">
                                            @error('ship_initiate')
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
                                        <button class="btn btn-primary float-right formSubmitBtn"
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
        });
    </script>
@endpush
