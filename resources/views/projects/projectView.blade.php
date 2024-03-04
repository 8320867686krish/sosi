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
                                        class="breadcrumb-link">{{ $head_title ?? 'Ship Particulars' }}</a></li>
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
                    <div class="alert alert-success sucessMsg" role="alert" style="display: none";>
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
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="ship_name" value="{{ old('ship_name', $project->ship_name ?? '') }}"
                                                name="ship_name" placeholder="Ship Name..." autocomplete="off"
                                                onchange="removeInvalidClass(this)" {{$readonly}}>
                                            @error('ship_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="manager_name">Manager Name</label>
                                        <input type="text" class="form-control @error('manager_name') is-invalid @enderror"
                                            id="manager_name" value="{{ old('manager_name', $project->manager_name ?? '') }}"
                                            name="manager_name" placeholder="Manager Name..." autocomplete="off"
                                            onchange="removeInvalidClass(this)"  {{$readonly}}>
                                        @error('manager_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="ship_owners_id">Ship Owner</label>
                                        <select name="ship_owners_id" id="ship_owners_id" class="form-control" {{$readonly}}>
                                            <option value="" {{ empty($readonly) ? 'disabled' : '' }}>Select Ship Owner</option>
                                            @if (isset($owners) && $owners->count() > 0)
                                                @foreach ($owners as $owner)
                                                    <option value="{{ $owner->id }}"
                                                        data-identi="{{ $owner->identification }}"
                                                        {{ old('ship_owners_id') == $owner->id || (isset($project) && $project->ship_owners_id == $owner->id) ? 'selected' : '' }}
                                                        {{ empty($readonly) ? '' : 'disabled' }}>
                                                        {{ $owner->name }}
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
                                            onchange="removeInvalidClass(this)"  {{$readonly}}>
                                        @error('ship_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="manager_name">Manager Details</label>
                                        <input type="text" class="form-control @error('manager_details') is-invalid @enderror"
                                            id="manager_name" value="{{ old('manager_details', $project->manager_details ?? '') }}"
                                            name="manager_details" placeholder="Manager Details.." autocomplete="off"
                                            onchange="removeInvalidClass(this)"  {{$readonly}}>
                                        @error('manager_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                        <div class="form-group">
                                            <label for="dimensions">Dimensions</label>
                                            <input type="text" class="form-control @error('dimensions') is-invalid @enderror"
                                                id="dimensions" value="{{ old('dimensions', $project->dimensions ?? '') }}"
                                                name="dimensions" placeholder="Dimensions" autocomplete="off"
                                                onchange="removeInvalidClass(this)"  {{$readonly}}>
                                            @error('manager_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                </div>

                              
                             
                               
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="builder_name">Builder Name</label>
                                                <input type="text" class="form-control @error('builder_name') is-invalid @enderror"
                                                    id="builder_name" value="{{ old('builder_name', $project->builder_name ?? '') }}"
                                                    name="builder_name" placeholder="Builder Name" autocomplete="off"
                                                    onchange="removeInvalidClass(this)"  {{$readonly}}>
                                                @error('builder_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    </div>

                                <div class="col-sm-12 col-md-4">
                                        <div class="form-group">
                                            <label for="builder_name">Builder Details</label>
                                            <input type="text" class="form-control @error('builder_details') is-invalid @enderror"
                                                id="builder_details" value="{{ old('builder_details', $project->builder_details ?? '') }}"
                                                name="builder_details" placeholder="Builder Details" autocomplete="off"
                                                onchange="removeInvalidClass(this)"  {{$readonly}}>
                                            @error('builder_details')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="build_date">Builder Date</label>
                                                <input type="text" class="form-control @error('build_date	') is-invalid @enderror"
                                                    id="build_date	" value="{{ old('build_date', $project->build_date?? '') }}"
                                                    name="build_date" placeholder="Build Date" autocomplete="off"
                                                    onchange="removeInvalidClass(this)"  {{$readonly}}>
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
                                                    onchange="removeInvalidClass(this)"  {{$readonly}}>
                                                @error('flag')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="gross_tonnage">Gross Tonnage</label>
                                        <input type="text" class="form-control @error('gross_tonnage') is-invalid @enderror"
                                            id="gross_tonnage" value="{{ old('gross_tonnage', $project->gross_tonnage ?? '') }}"
                                            name="gross_tonnage" placeholder="Gross Tonnage" autocomplete="off"
                                            onchange="removeInvalidClass(this)"  {{$readonly}}>
                                        @error('builder_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="build_date">Delivery Date</label>
                                        <input type="text" class="form-control @error('delivery_date') is-invalid @enderror"
                                            id="delivery_date" value="{{ old('delivery_date', $project->delivery_date?? '') }}"
                                            name="delivery_date" placeholder="Delivery Date" autocomplete="off"
                                            onchange="removeInvalidClass(this)"  {{$readonly}}>
                                        
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
                                                    onchange="removeInvalidClass(this)"  {{$readonly}}>
                                                @error('flag')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="gross_tonnage">Gross Tonnage</label>
                                        <input type="text" class="form-control @error('gross_tonnage') is-invalid @enderror"
                                            id="gross_tonnage" value="{{ old('gross_tonnage', $project->gross_tonnage ?? '') }}"
                                            name="gross_tonnage" placeholder="Gross Tonnage" autocomplete="off"
                                            onchange="removeInvalidClass(this)"  {{$readonly}}>
                                        @error('builder_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="build_date">Delivery Date</label>
                                        <input type="text" class="form-control @error('delivery_date') is-invalid @enderror"
                                            id="delivery_date" value="{{ old('delivery_date', $project->delivery_date?? '') }}"
                                            name="delivery_date" placeholder="Delivery Date" autocomplete="off"
                                            onchange="removeInvalidClass(this)"  {{$readonly}}>
                                        
                                    </div>
                                </div>

                            </div>

                          
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('projects') }}" class="btn btn-info btn-rounded formBackBtn" type="button">Back</a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        @can('projects.edit')
                                        <button class="btn btn-primary float-right btn-rounded formSubmitBtn" type="button"
                                            style="display:none">Edit</button>
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

            $("#projectForm").on("input", function() {
                $(".formSubmitBtn").show();
            });
            $(".formSubmitBtn").click(function(){
                $.ajax({
                    type: "POST",
                    url:"{{url('detail/save')}}",
                    data: $("#projectForm").serialize(),
                    success: function( msg ) {
                       $(".sucessMsg").show();
                    }
                });
            });
        });
    </script>
@endsection
