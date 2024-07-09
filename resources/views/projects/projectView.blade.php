@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/cropper/dist/cropper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-select/css/bootstrap-select.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/select.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/fixedHeader.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/fancybox/fancybox.min.css') }}">
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

        .output {
            padding: 10px 0;
            color: #fff;
            background: #525252;
            width: 100%;
            max-width: 420px;
            padding-left: 5px;
        }

        .outfit {
            line-height: 0;
            position: relative;
            width: auto;
            height: auto;
            background: gray;
            display: inline-block;
            max-width: 420px;

            img {
                width: 100%;
                height: auto;
                cursor: pointer;
            }
        }

        .dot {
            position: absolute;
            width: 24px;
            height: 24px;
            /* background: rgba(white, 1); */
            background: #fff;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 0 3px 0 rgba(0, 0, 0, .2);
            line-height: 24px;
            font-size: 12px;
            font-weight: bold;
            transition: box-shadow .214s ease-in-out, transform .214s ease-in-out, background .214s ease-in-out;
            text-align: center;

            &.ui-draggable-dragging {
                box-shadow: 0 0 25px 0 rgba(0, 0, 0, .5);
                transform: scale3d(1.2, 1.2, 1.2);
                background: rgba(white, .7);
            }
        }

        .fancybox-button--rotate {
            position: absolute;
            bottom: 10px;
            right: 60px;
            background: rgba(30, 30, 30, 0.8);
            border: none;
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            z-index: 9999;
        }

        .fancybox-button--update {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(30, 30, 30, 0.8);
            border: none;
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            z-index: 9999;
        }

        .checkImagePreview {
            display: inline-block;
            margin: 10px;
            position: relative;
        }

        .checkImagePreview img {
            width: 160px;
        }

        .checkImagePreview a {
            position: absolute;
            top: 2px;
            right: 2px;
            text-decoration: none;
            color: red;
            cursor: pointer;
            /* background: white; */
            /* border-radius: 50%; */
            padding: 2px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid dashboard-content" id="projectViewContent">
        <aside class="page-aside" id="page-aside">
            <div class="aside-content">
                <div class="aside-header">
                    <span class="title" style="font-size: 20px;">Project Information</span>
                    <p class="description">{{ $project->ship_name ?? '' }}</p>
                    {{-- <button class="navbar-toggle" type="button"><span class="icon" style="cursor: pointer; font-size: 16px !important;"><i class="fas fa-bars" id="pageNavbarToggleBtn"></i></span></button> --}}
                </div>
                <div class="aside-nav collapse">
                    <ul class="nav">
                        <li>
                            <a href="{{ route('projects') }}"><span class="icon"><i
                                        class="fas fa-arrow-left"></i></span>Back</a>
                        </li>
                        <li class="{{ $isBack == 0 ? 'active' : '' }}">
                            <a href="#ship_particulars">
                                <span class="icon"><i class="fas fa-ship"></i></span>Ship Particulars
                            </a>
                        </li>
                        <li class="{{ $isBack == 1 ? 'active' : '' }}">
                            <a href="#create_vscp">
                                <span class="icon"><i class="fas fa-fw fa-briefcase"></i></span>Create VSCP
                            </a>
                        </li>
                        <li>
                            <a href="#check_list"><span class="icon"><i class="fas fa-check-circle"></i></span>Check
                                List</a>
                        </li>
                        <li>
                            <a href="#assign_project"><span class="icon"><i
                                        class="fas fa-fw fa-briefcase"></i></span>OnBoard Survey Plan</a>
                        </li>
                        <li>
                            <a href="#attachment_list"><span class="icon"><i
                                        class="fas fa-fw fa-briefcase"></i></span>Attachment</a>
                        </li>
                        <li>
                            <a href="#report_list"><span class="icon"><i
                                        class="fas fa-fw fa-briefcase"></i></span>Report</a>
                        </li>

                    </ul>
                </div>
            </div>
        </aside>

        <div class="main-content container-fluid p-0" id="ship_particulars"
            {{ $isBack == 0 ? 'style=display:block' : 'style=display:none' }}>
            <div class="email-head">
                <div class="email-head-subject">
                    <div class="title">
                        <span>Ship Particulars</span>
                    </div>
                </div>
            </div>
            <div class="email-body">
                <div class="alert alert-success sucessMsg" role="alert" style="display: none;">
                    Save Successfully!!<a href="#" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </a>
                </div>
                <form method="post" class="needs-validation" novalidate id="projectForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
                    <div class="row mb-5">
                        <div class="col-sm-6 col-md-8 col-lg-3">
                            <div class="preview-image-container">

                                <img id="previewImg" src="{{ $project->imagePath }}"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/logo.png') }}';"
                                    style="max-width: 300px" alt="Upload Image">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-1 pt-10">
                            <div class="form-group mb-3">

                                <button class="addfiles btn btn-primary"><i class="fas fa-upload"></i></button>

                                <input type="file" class="form-control  @error('image') is-invalid @enderror"
                                    id="image" name="image" autocomplete="off"
                                    onchange="previewFile(this); removeInvalidClass(this)" {{ $readonly }}
                                    accept="image/*" style="display:none">

                                <div class="invalid-feedback" id="imageError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label for="ship_name">Ship Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control  @error('ship_name') is-invalid @enderror"
                                    id="ship_name" value="{{ old('ship_name', $project->ship_name ?? '') }}"
                                    name="ship_name" placeholder="Ship Name..." autocomplete="off"
                                    onchange="removeInvalidClass(this)" {{ $readonly }}>
                                <div class="invalid-feedback error" id="ship_nameError"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label for="imo_number">Ship IMO Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control  @error('imo_number') is-invalid @enderror"
                                    id="imo_number" name="imo_number" onchange="removeInvalidClass(this)"
                                    value="{{ old('imo_number', $project->imo_number ?? '') }}" {{ $readonly }}>
                                <div class="invalid-feedback error" id="imo_numberError"></div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label for="call_sign">Call Sign</label>
                                <input type="text" class="form-control  @error('call_sign') is-invalid @enderror"
                                    id="call_sign" name="call_sign" placeholder="Call Sign..."
                                    value="{{ old('call_sign', $project->call_sign ?? '') }}" {{ $readonly }}>
                                @error('call_sign')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label for="manager_name">Manager Name</label>
                                <input type="text" class="form-control  @error('manager_name') is-invalid @enderror"
                                    id="manager_name"
                                    value="{{ old('manager_name', $project->client->manager_name ?? '') }}"
                                    placeholder="Manager Name..." autocomplete="off" onchange="removeInvalidClass(this)"
                                    readonly>
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
                                <input type="text" class="form-control" id="owner_name"
                                    value="{{ old('owner_name', $project->client->owner_name ?? '') }}" readonly>
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

        <div class="main-content container-fluid p-0" id="create_vscp"
            {{ $isBack == 1 ? 'style=display:block' : 'style=display:none' }}>
            @include('projects.addVscp')
        </div>

        <div class="main-content container-fluid p-0" id="check_list">
            <div id="showCheckImgMsg"></div>
            <div class="email-head">
                <div class="email-head-subject">
                    <div class="row">
                        <div class="col-6">
                            <div class="title"><span>Check List</span></div>
                        </div>
                        <div class="col-6">
                            @can('projects.edit')
                                <a href="{{ route('excelReport', ['project_id' => $project->id]) }}"
                                    class="btn btn-primary float-right">Export</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="email-body">
                <div class="table-responsive">
                    <h4 style="background-color: #f7ff005e; color: #000000" class="mb-3">Change Checks:- <b>{{ $project->markAsChangeCount  }}</b></h4>
                    <table class="table table-striped table-bordered first" width="100%">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Check</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Equip. & Comp.</th>
                                <th>Hazmat</th>
                                <th>Document analyisis result</th>
                                @can('projects.edit')
                                    <th width="10%">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody id="checkListTable">
                            @include('projects.allcheckList')
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="main-content container-fluid p-0" id="assign_project">

            <div class="email-head-subject">
                <div class="title"><span>OnBoard Survey Plan</span>

                </div>
            </div>

            <div class="row">
                @include('projects.surveyPlan')
            </div>

        </div>

        <div class="main-content container-fluid p-0" id="attachment_list">
            @include('projects.attachment')
        </div>

        <div class="main-content container-fluid p-0" id="report_list">
            <div class="email-head-subject">
                <div class="title"><span>Report</span></div>
            </div>
            <div class="row">
                @include('projects.repoert')
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <form method="post" action="{{ url('genratePdf') }}" id="genratePdf">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}" />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <input type="number" name="version" placeholder="Version Number..." class="form-control form-control-lg">
                                </div>
                                <div class="col-4">
                                    <input type="date" name="date" class="form-control form-control-lg">
                                </div>
                                <div class="col-4">
                                    <button type="button" name="action" class="btn btn-primary fullreport" value="submit">Full Report</button>
                                        @if($project->ihm_table == 'IHM Part 1')
                                    <button type="button" name="action" class="btn btn-primary" value="summery" >Summery</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="checkImageAddModal" tabindex="-1" role="dialog"
            aria-labelledby="checkImageAddModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="width: 70% !important; max-width: none !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add new check image</h5>
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close"
                            id="checkDataImageCloseBtn">
                            <span aria-hidden="true">×</span>
                        </a>
                    </div>

                    <form method="post" action="{{ route('addCheckImage') }}" id="checkImageAddForm"
                        enctype="multipart/form-data">
                        <div class="modal-body"
                            style="overflow-x: auto; overflow-y: auto; max-height: calc(81vh - 1rem);">
                            @csrf
                            <input type="hidden" id="project_id" name="project_id"
                                value="{{ $deck->project_id ?? '' }}">
                            <input type="hidden" id="checkImageAddFromCheck_id" name="check_id">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="images">Check Image</label>
                                        <input type="file" name="images[]" id="checkImages" class="form-control"
                                            multiple accept="image/*">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row" id="checkimagePreviewContainer"></div>
                                </div>
                                <div class="col-12 mt-5">
                                    <h3>Check Image List</h3>
                                    <div class="row" id="checkImageListPreview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="checkImageAddSubmitBtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="checkDataAddModal" tabindex="-1" role="dialog"
            aria-labelledby="checkDataAddModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="width: 70% !important; max-width: none !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Title</h5>
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close"
                            id="checkDataAddCloseBtn">
                            <span aria-hidden="true">×</span>
                        </a>
                    </div>

                    <form method="post" action="{{ route('addImageHotspots') }}" id="checkDataAddForm"
                        enctype="multipart/form-data">
                        <div class="modal-body"
                            style="overflow-x: auto; overflow-y: auto; max-height: calc(81vh - 1rem);">
                            @csrf
                            <input type="hidden" id="id" name="id">
                            <input type="hidden" id="project_id" name="project_id"
                                value="{{ $deck->project_id ?? '' }}">
                            <input type="hidden" id="deck_id" name="deck_id" value="{{ $deck->id ?? '' }}">
                            <input type="hidden" id="position_left" name="position_left">
                            <input type="hidden" id="position_top" name="position_top">
                            <div class="row">
                                <div class="col-12 col-md-6" id="chkName">
                                    <div class="form-group mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" value=""
                                            class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="type">Type <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-control">
                                            <option value>Select Type</option>
                                            <option value="sample">Sample</option>
                                            <option value="visual">Visual</option>
                                        </select>
                                        <div class="invalid-feedback error" id="typeError"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="location">Location</label>
                                        <input type="text" id="location" name="location" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sub_location">Sub Location</label>
                                        <input type="text" id="sub_location" name="sub_location"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="equipment">Equipment</label>
                                        <input type="text" id="equipment" name="equipment" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="component">Component</label>
                                        <input type="text" id="component" name="component" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="suspected_hazmat">Suspected Hazmat</label>
                                        <select class="form-control selectpicker" id="suspected_hazmat"
                                            name="suspected_hazmat[]" multiple="multiple">
                                            <option value="">Select Hazmat</option>
                                            @if (isset($hazmats))
                                                @foreach ($hazmats as $key => $value)
                                                    <optgroup label="{{ strtoupper($key) }}">
                                                        @foreach ($value as $hazmat)
                                                            <option value="{{ $hazmat->id }}">
                                                                {{ $hazmat->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="col-12 col-md-12 mb-4"
                                        style="background: #efeff6;border: 1px solid #efeff6;">
                                        <div class="pt-4">
                                            <h5 class="text-center mb-4" style="color:#757691">Document Analysis Results
                                            </h5>
                                            <div class="row col-12 mb-4" id="showTableTypeDiv">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 mb-3"
                                        style="background: #efeff6;border: 1px solid #efeff6;">
                                        <div class="pt-4">
                                            <h5 class="text-center"
                                                style="background: #efeff6;border: 1px solid #efeff6;">Find Results</h5>
                                            <div class="mb-4 col-12" id="showLabResult">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="remarks">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="recommendation">Recommendation</label>
                                        <textarea name="recommendation" id="recommendation" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="checkDataAddSubmitBtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12" style="display: none;">
            <div class="col-12 col-md-12 col-lg-12 cloneTableTypeDiv card" id="cloneTableTypeDiv">
                <label for="table_type" id="tableTypeLable" class="mr-5 tableTypeLable mt-3 card-header"></label>
                <div class="row card-body">
                    <div class="col-12 table_typecol">
                        <div class="form-group">
                            <select class="form-control table_type" id="table_type" name="table_type">
                                <option value="Contained">Contained</option>
                                <option value="Not Contained">Not Contained</option>
                                <option value="PCHM">PCHM</option>
                                <option value="Unknown" selected>Unknown</option>
                            </select>
                        </div>
                        <div class="documentLoadCheckboxDiv">
                            <input type="checkbox" id="myCheckbox" class="documentLoadCheckbox">
                            <label for="myCheckbox">Load Document From Master Data</label>
                        </div>
                    </div>
                    <div class="col-4 imagehazmat">
                        <div class="form-group mb-3">
                            <input type="file" class="form-control hazmatImg" accept="image/*">
                        </div>
                        <div class="imageNameShow mb-3" style="font-size: 13px;"></div>
                    </div>
                    <div class="col-4 dochazmat">
                        <div class="form-group mb-3">
                            <input type="file" class="form-control hazmatDoc">
                        </div>
                        <div class="docNameShow mb-3" style="font-size: 13px;"></div>
                    </div>
                    <div class="col-4 equipment">
                        <div class="form-group">
                            <select class="form-control equipmentSelectTag">
                                <option value="">Select Equipment</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-4 manufacturer">
                        <div class="form-group">
                            <select class="form-control manufacturerSelectTag">
                                <option value="">First Select Equipment</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4 modelMakePart">
                        <div class="form-group mb-3">
                            <select class="form-control modelMakePartTag">
                                <option value="">First Select Manufacturer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 remarks">
                        <div class="form-group mb-3">
                            <textarea class="form-control remarksTextarea" rows="2" placeholder="Remark..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: none;">
            <div class="col-12 col-md-12 col-lg-12 cloneIHMTableDiv card" id="cloneIHMTableDiv">
                <label for="ihm_table" id="ihmTableLable" class="mr-5 ihmTableLable card-header"></label>
                <div class="row card-body">
                    <div class="col-12 IHMTypeDiv">
                        <div class="form-group mb-3">
                            <label>Type</label>
                            <select class="form-control IHM_type">
                                <option value="">Select Type</option>
                                <option value="Contained">Contained</option>
                                <option value="PCHM">PCHM</option>
                                <option value="Not Contained">Not Contained</option>
                                <option value="Below Threshold">Below Threshold</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 IHMPartDiv">
                        <div class="form-group">
                            <label for="IHM Part">IHM Part</label>
                            <select class="form-control IHM_part">
                                <option value="">Select IHM Part</option>
                                <option value="IHMPart1-1">IHM Part 1-1</option>
                                <option value="IHMPart1-2">IHM Part 1-2</option>
                                <option value="IHMPart1-3">IHM Part 1-3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group mb-3">
                            <label for="unit">Unit</label>
                            <input type="text" class="form-control unit" placeholder="Unit...">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="number">Number</label>
                            <input type="number" class="form-control number" placeholder="Number...">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Total (KG.)</label>
                            <input type="text" class="form-control total" placeholder="Total (KG.)">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label>Sample Weight</label>
                            <input type="text" class="form-control weight" placeholder="Sample Weight">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="total">Sampling Area</label>
                            <input type="text" class="form-control sarea" placeholder="Sampling Area">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="total">Density</label>
                            <input type="text" class="form-control density" placeholder="Density">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="total">Affected Area</label>
                            <input type="text" class="form-control affected" placeholder="Affected Area">
                        </div>
                    </div>
                    <div class="col-12 lab_remarks">
                        <div class="form-group mb-3">
                            <label for="">Remarks</label>
                            <textarea class="form-control labRemarksTextarea" rows="2" placeholder="Remark..."></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/vendor/jquery.areaSelect.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/libs/js/bootstrap4-toggle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/fancybox/fancybox.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>

    <script>
        let rotationState = 0;
        $('.fullreport').click(function(){
            let formData = new FormData($("#genratePdf")[0]);

                $.ajax({
                    url: "{{ url('genratePdf') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        let filePath = response.filePath; // Adjust according to your server response
                        let downloadLink = document.createElement('a');
            downloadLink.href = filePath;
            downloadLink.target = '_blank'; // Open in new tab
            downloadLink.textContent = 'Click here to download or open the PDF';

            // Append the link to the document body (optional)
            document.body.appendChild(downloadLink);

            // Simulate click on the link to trigger download or open in new tab
            downloadLink.click();
            
            // Triggering the download
            window.location.href = filePath; // This initiates the download of the PDF
                    }
                });
        });
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

        function handleTableTypeChange(selectedValue, cloneTableTypeDiv) {
            if (!selectedValue || !cloneTableTypeDiv) {
                console.error("Missing parameters for handleTableTypeChange function");
                return;
            }

            const targetElements = cloneTableTypeDiv.find(".table_typecol, .dochazmat, .imagehazmat");

            const newClass = (selectedValue === "Unknown") ? "col-12" : "col-4";

            targetElements.removeClass("col-12 col-4").addClass(newClass);
            cloneTableTypeDiv.find(".imagehazmat").toggle(selectedValue !== "Unknown");
            cloneTableTypeDiv.find(".dochazmat").toggle(selectedValue !== "Unknown");
            cloneTableTypeDiv.find(".remarks").toggle(selectedValue == "PCHM");
            if (selectedValue == "Unknown") {
                cloneTableTypeDiv.find(".documentLoadCheckbox").prop('checked', false);
                cloneTableTypeDiv.find(".equipment, .manufacturer, .modelMakePart").hide();
            }
            cloneTableTypeDiv.find(`.documentLoadCheckboxDiv`).toggle(selectedValue !== "Unknown");
        }

        function handleIHMTypeChange(selectedValue, cloneTableTypeDiv) {
            if (!selectedValue || !cloneTableTypeDiv) {
                console.error("Missing parameters for handleIHMTypeChange function");
                return;
            }

            const targetElements = cloneTableTypeDiv.find(".IHMPartDiv, .IHMTypeDiv");
            const isContainedOrPCHM = selectedValue === "Contained" || selectedValue === "PCHM";
            const newClass = isContainedOrPCHM ? "col-6" : "col-12";

            targetElements.removeClass("col-12 col-6").addClass(newClass);
            cloneTableTypeDiv.find(".IHMPartDiv").toggle(isContainedOrPCHM);
        }

        function labResult(selectedValue, selectedText) {

            let clonedElement = $('#cloneIHMTableDiv').clone();
            clonedElement.removeAttr("id");
            clonedElement.attr("id", "cloneIHMTableDiv" + selectedValue);

            clonedElement.find('label.ihmTableLable').text(selectedText);

            clonedElement.find('select.IHM_type').attr('id', `IHM_type_${selectedValue}`).attr('name',
                `IHM_type[${selectedValue}]`);

            clonedElement.find('select.IHM_part').attr('id', `IHM_part_${selectedValue}`).attr('name',
                `IHM_part[${selectedValue}]`);

            clonedElement.find('input[type="text"].unit').prop({
                id: `unit_${selectedValue}`,
                name: `unit[${selectedValue}]`
            });

            clonedElement.find('input[type="number"].number').prop({
                id: `number_${selectedValue}`,
                name: `number[${selectedValue}]`
            });

            clonedElement.find('input[type="text"].weight').prop({
                id: `sample_weight_${selectedValue}`,
                name: `sample_weight[${selectedValue}]`
            });

            clonedElement.find('input[type="text"].sarea').prop({
                id: `sample_area_${selectedValue}`,
                name: `sample_area[${selectedValue}]`
            });

            clonedElement.find('input[type="text"].density').prop({
                id: `density_${selectedValue}`,
                name: `density[${selectedValue}]`
            });

            clonedElement.find('input[type="text"].affected').prop({
                id: `affected_${selectedValue}`,
                name: `affected_area[${selectedValue}]`
            });

            clonedElement.find('input[type="text"].total').prop({
                id: `total_${selectedValue}`,
                name: `total[${selectedValue}]`
            });

            clonedElement.find('textarea.labRemarksTextarea').prop({
                id: `lab_remarks_${selectedValue}`,
                name: `lab_remarks[${selectedValue}]`
            });

            clonedElement.find(`.IHMPartDiv`).hide();
            // // Append cloned element to showTableTypeDiv
            $('#showLabResult').append(clonedElement);
        }

        function triggerFileInput(inputId) {
            $(`#${inputId}`).val('');
            document.getElementById(inputId).click();
            $(".dashboard-spinner").show();
        }

        async function convertToImage() {
            $(".dashboard-spinner").show();

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
                    $(".dashboard-spinner").show();

                    await page.render({
                        canvasContext: context,
                        viewport
                    }).promise;
                    const imageData = canvas.toDataURL('image/png');
                    const img = document.createElement('img');
                    img.src = imageData;
                    img.classList.add('pdf-image'); // Add a class to the image

                    const container = document.getElementById('img-container');
                    var pdfContainer = document.createElement('div');
                    pdfContainer.id = 'pdfContainer' + i; // Set the ID for the new div
                    pdfContainer.className = 'pdfContainer'; // Set the class for the new div
                    container.appendChild(pdfContainer);
                    // if(i == 1) {
                    // } else {
                    //     container.appendChild(pdfContainer).style="display:none";
                    // }
                    pdfContainer.appendChild(img);
                    img.onload = function() {
                        var options = {
                            currentPage: i,
                            deleteMethod: 'doubleClick',
                            handles: true,
                            area: {
                                strokeStyle: 'green',
                                lineWidth: 2
                            },
                            onSelectEnd: function(image, selection) {
                                console.log("Selection End:", selection);
                            },
                            initAreas: []
                        };
                        $(this).areaSelect(options);
                    };
                    $(".dashboard-spinner").hide();
                }

                // Bind event listeners after images are loaded
                // $('.pdf-image').on('load', function() {
                //     var options = {
                //         deleteMethod: 'doubleClick',
                //         handles: true,
                //         area: {
                //             strokeStyle: 'green',
                //             lineWidth: 2
                //         },

                //         onSelectEnd: function(image, selection) {
                //             console.log("Selection End:", selection);
                //         },
                //         initAreas: []
                //     };
                //     $(this).areaSelect(options);
                // });
            };
            fileReader.readAsArrayBuffer(pdfFile);
            $('#pdfModal').modal('show');
        }

        function detailOfHazmats(checkId) {
            $.ajax({
                type: 'GET',
                url: "{{ url('check') }}" + "/" + checkId + "/hazmat",
                success: function(response) {
                    $('#showTableTypeDiv').html(response.html);
                    $('#showLabResult').html(response.labResult);

                    let jsonObject = response.check;
                    for (var key in jsonObject) {
                        if (jsonObject.hasOwnProperty(key)) {
                            $(`#checkDataAddForm #${key}`).val(jsonObject[key]);
                        }
                    }

                    $('#checkDataAddForm #suspected_hazmat').selectpicker('val', response.hazmatIds);

                    $.each(response.check.hazmats, function(index, hazmatData) {
                        if (hazmatData.type === 'Unknown') {
                            $(`#imagehazmat${hazmatData.hazmat_id}`).hide();
                            $(`#dochazmat${hazmatData.hazmat_id}`).hide();
                        }
                    });

                    const cloneTableTypeDiv = $(".cloneTableTypeDiv select.table_type").closest(
                        ".cloneTableTypeDiv");

                    cloneTableTypeDiv.find(".equipment").hide();
                    cloneTableTypeDiv.find(".manufacturer").hide();
                    cloneTableTypeDiv.find(".modelMakePart").hide();

                    $("#checkDataAddModal").modal('show');
                },
            });
        }

        function getHazmatEquipment(hazmat_id) {
            $.ajax({
                type: 'GET',
                url: "{{ url('getHazmatEquipment') }}" + "/" + hazmat_id,
                success: function(response) {
                    if (response.isStatus) {
                        $(`#equipmentSelectTag_${hazmat_id}`).attr('data-id', hazmat_id);
                        $.each(response.equipments, function(index, value) {
                            $(`#equipmentSelectTag_${hazmat_id}`).append($('<option>', {
                                value: index,
                                text: index
                            }));
                        });

                        const cloneTableTypeDiv = $(".cloneTableTypeDiv select.table_type").closest(
                            ".cloneTableTypeDiv");

                        cloneTableTypeDiv.find(`#equipmentDiv_${hazmat_id}`).closest('.equipment').show();
                        cloneTableTypeDiv.find(`#manufacturerDiv_${hazmat_id}`).closest('.manufacturer').show();
                        cloneTableTypeDiv.find(`#modelMakePartDiv_${hazmat_id}`).closest('.modelMakePart')
                            .show();
                    }
                },
            });
        }

        function rotateImage() {
            rotationState = (rotationState + 1) % 4;
            let degree;
            switch (rotationState) {
                case 0:
                    degree = 0; // Original position
                    break;
                case 1:
                    degree = 90; // Left
                    break;
                case 2:
                    degree = 180; // Bottom
                    break;
                case 3:
                    degree = 270; // Right
                    break;
            }
            $('#demo-image').css({
                '-webkit-transform': 'rotate(' + degree + 'deg)',
                '-moz-transform': 'rotate(' + degree + 'deg)',
                'transform': 'rotate(' + degree + 'deg)'
            });
        }

        $(document).ready(function() {
            const url = window.location.href;
            const segments = url.split('/');
            const projectId = segments[segments.length - 1];
            let sidebar = $("#mainSidebar");
            let isSidebarVisible = true;

            if ($(window).width() >= 768) {
                $("#sidebarCollapse").show();
                $("#sidebarCollapse").find('span').css({
                    "height": "1em",
                    "width": "1em"
                });
            } else {
                $("#sidebarCollapse").hide();
            }

            $(document).on("click", "#sidebarCollapse", function() {
                if ($(window).width() >= 768) {
                    if (isSidebarVisible) {
                        sidebar.css("left", "-188px"); //250
                        $('#page-aside').css("left", "78px"); //8
                        $('.dashboard-wrapper').css("margin-left", "78px"); //8
                    } else {
                        sidebar.css("left", "0");
                        $('#page-aside').css("left", "265px");
                        $('.dashboard-wrapper').css("margin-left", "264px");
                    }
                    isSidebarVisible = !isSidebarVisible;
                }
            });

            $('#pdfModal').on('hidden.bs.modal', function() {
                $("#img-container").empty();
                $(".pdf-image").empty();
            });

            $('#pdfFile').change(function() {
                convertToImage();
            });

            // $('.main-content').hide();

            let back = "{{ $isBack }}";
            if (back == 1) {
                $('#ship_particulars').hide();
                $('#check_list').hide();
                $('#assign_project').hide();
                $("#attachment_list").hide();
                $("#report_list").hide();
                $('#create_vscp').show();
                $('#laboratory_list').hide();
            } else {
                $('#ship_particulars').show();
                $('#check_list').hide();
                $('#assign_project').hide();
                $('#create_vscp').hide();
                $('#laboratory_list').hide();
                $("#report_list").hide();
                $("#attachment_list").hide();
            }

            $('.aside-nav .nav li a').click(function() {
                $('.aside-nav .nav li').removeClass('active');
                $(this).parent('li').addClass('active');
                $('.main-content').hide();

                let targetId = $(this).attr('href');

                $(targetId).show();

                return false;
            });

            $('.aside-nav .nav li a[href="#assign_project"]').click(function() {
                $('.main-content').hide();
                $('#assign_project').show();
                return false;
            });

            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 15000);

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

                $('.error').empty().hide();
                $('input').removeClass('is-invalid');
                $('select').removeClass('is-invalid');

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ url('detail/save') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.isStatus) {
                            successMsg(response.message);
                        } else {
                            errorMsg(response.message);
                        }
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
                });
            });

            $(".outfit img").click(function(e) {

                var dot_count = $(".dot").length;

                var top_offset = $(this).offset().top - $(window).scrollTop();
                var left_offset = $(this).offset().left - $(window).scrollLeft();

                var top_px = Math.round((e.clientY - top_offset - 12));
                var left_px = Math.round((e.clientX - left_offset - 12));

                var top_perc = top_px / $(this).height() * 100;
                var left_perc = left_px / $(this).width() * 100;

                // alert('Top: ' + top_px + 'px = ' + top_perc + '%');
                // alert('Left: ' + left_px + 'px = ' + left_perc + '%');

                var dot = '<div class="dot" style="top: ' + top_perc + '%; left: ' + left_perc + '%;">' + (
                    dot_count +
                    1) + '</div>';

                $(dot).hide().appendTo($(this).parent()).fadeIn(350);
                var position = {
                    left: left_perc,
                    top: top_perc
                };

                $(".dot").draggable({
                    containment: ".outfit",
                    stop: function(event, ui) {
                        var new_left_perc = parseInt($(this).css("left")) / ($(".outfit")
                                .width() / 100) +
                            "%";
                        var new_top_perc = parseInt($(this).css("top")) / ($(".outfit")
                                .height() / 100) +
                            "%";
                        var output = 'Top: ' + parseInt(new_top_perc) + '%, Left: ' + parseInt(
                            new_left_perc) + '%';
                        var position = {
                            left: new_left_perc,
                            top: new_top_perc
                        };

                        $(this).css("left", parseInt($(this).css("left")) / ($(".outfit")
                                .width() / 100) +
                            "%");
                        $(this).css("top", parseInt($(this).css("top")) / ($(".outfit")
                                .height() / 100) +
                            "%");

                        $('.output').html('CSS Position: ' + output);
                    }
                });

                // console.log("Left: " + left_perc + "%; Top: " + top_perc + '%;');
                $('.output').html("CSS Position: Left: " + parseInt(left_perc) + "%; Top: " + parseInt(
                        top_perc) +
                    '%;');
            });

            $("#imageForm").submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                let dots = [];
                $(".dot").each(function(index) {
                    let containerWidth = $(this).parent().width();
                    let containerHeight = $(this).parent().height();

                    let left = parseFloat($(this).css('left')) / containerWidth * 100;
                    let top = parseFloat($(this).css('top')) / containerHeight * 100;

                    dots.push({
                        left: left,
                        top: top
                    });
                });

                formData.append('dots', JSON.stringify(dots));

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.isStatus) {
                            $("#imageId").val(response.id);
                        }
                        // Handle success response
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error response
                    }
                });
            });

            $("#hotsportNameTypeDiv").hide();

            $(document).on("click", ".dot", function() {
                let dotCounText = $(this).text();
                let cloneHtml = $("#hotsportNameTypeDiv .form-group").clone();

                cloneHtml.find('input').each(function() {
                    let currentId = $(this).attr('id');
                    let currentName = $(this).attr('name');
                    let newId = currentId + '_' + dotCounText.trim();
                    let newName = currentName + '_' + dotCounText.trim();
                    $(this).attr('id', newId);
                    $(this).attr('name', newName);
                });

                $("#hotsportNameType").append(cloneHtml);
            });


            // $('#getDeckCropImg').click(function() {
            //     let $submitButton = $(this);
            //     let originalText = $submitButton.html();
            //     $submitButton.text('Wait...');
            //     $submitButton.prop('disabled', true);
            //     $(".pdfModalCloseBtn").prop('disabled', true);


            //     let textareas = [];
            //     let areas = $('.pdf-image').areaSelect('get');
            //     let projectId = {{ $project->id }} || '';

            //     areas.forEach(area => {
            //         var input = document.getElementById(area.id);
            //         if (input) {
            //             textareas.push({
            //                 ...area, // Copy existing area properties
            //                 'text': input.value // Add 'text' key with input value
            //             });

            //         }
            //     });
            //     console.log(textareas);
            //     let areasJSON = JSON.stringify(textareas);
            //     let images = document.querySelectorAll('.pdf-image');
            //     const pdfFile = document.getElementById('pdfFile').files[0];

            //     let imageFiles = [];
            //     images.forEach(function(image, index) {
            //         // Convert the image data URL to a blob
            //         fetch(image.src).then(res => res.blob())
            //             .then(blob => {
            //                 // Create a new FormData object
            //                 var formData = new FormData();
            //                 formData.append('image', blob, 'page_' + (index + 1) + '.png');
            //                 formData.append('_token', '{{ csrf_token() }}');
            //                 formData.append('project_id', projectId);
            //                 formData.append('ga_plan', pdfFile);
            //                 formData.append('areas', areasJSON);

            //                 $.ajax({
            //                     type: 'POST',
            //                     url: "{{ url('project/save-image') }}",
            //                     data: formData,
            //                     processData: false,
            //                     contentType: false,
            //                     success: function(response) {
            //                         $('.pdf-image').empty();
            //                         $("#pdfFile").val();
            //                         $("#pdfModal").removeClass('show');
            //                         $("body").removeClass('modal-open');
            //                         $("#img-container").empty();
            //                         $(".modal-backdrop").remove();
            //                         $('.deckView').html();
            //                         $('.deckView').html(response.html);
            //                         $submitButton.html(originalText);
            //                         $submitButton.prop('disabled', false);
            //                         $('#pdfModal').modal('hide');
            //                     },
            //                     error: function(xhr, status, error) {
            //                         $submitButton.html(originalText);
            //                         $submitButton.prop('disabled', false);
            //                         console.error('Failed to save image:', error);
            //                     }
            //                 });
            //             });
            //     });
            // });

            $('#getDeckCropImg').click(async function() {
                let $submitButton = $(this);
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);
                $(".pdfModalCloseBtn").prop('disabled', true);

                let images = document.querySelectorAll('.pdf-image');
                let projectId = "{{ $project->id }}" || '';
                const pdfFile = document.getElementById('pdfFile').files[0];

                let allResponses = [];

                for (let index = 0; index < images.length; index++) {
                    let image = images[index];
                    let areas = $(image).areaSelect('get');
                    let textareas = [];

                    areas.forEach(area => {
                        let input = document.getElementById(area.id);
                        if (input) {
                            textareas.push({
                                ...area,
                                'text': input.value
                            });
                        }
                    });

                    try {
                        if (textareas.length > 0) {
                            allResponses.length = 0;
                            let response = await saveImageWithAreas(image.src, index, projectId,
                                pdfFile, textareas);
                            allResponses.push(response);
                        }
                    } catch (error) {
                        console.error('Error saving image:', error);
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                        $(".pdfModalCloseBtn").prop('disabled', false);
                        return;
                    }
                }

                // All images saved successfully
                handleSaveSuccess(allResponses);
                cleanupModal();
                $submitButton.html(originalText);
                $submitButton.prop('disabled', false);
                $(".pdfModalCloseBtn").prop('disabled', false);
            });

            function saveImageWithAreas(imageSrc, index, projectId, pdfFile, textareas) {
                return new Promise((resolve, reject) => {
                    let areasJSON = JSON.stringify(textareas);

                    fetch(imageSrc)
                        .then(res => res.blob())
                        .then(blob => {
                            var formData = new FormData();
                            formData.append('image', blob, 'page_' + (index + 1) + '.png');
                            formData.append('_token', '{{ csrf_token() }}');
                            formData.append('project_id', projectId);
                            formData.append('ga_plan', pdfFile);
                            formData.append('areas', areasJSON);

                            $.ajax({
                                type: 'POST',
                                url: "{{ url('project/save-image') }}",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    resolve(response);
                                },
                                error: function(xhr, status, error) {
                                    reject(new Error(
                                        `Error ${xhr.status}: ${xhr.statusText}`));
                                }
                            });
                        })
                        .catch(error => {
                            reject(new Error(`Failed to fetch image: ${error.message}`));
                        });
                });
            }

            function handleSaveSuccess(responses) {
                $('.deckView').html('');
                responses.forEach(response => {
                    $('.deckView').append(response.html);
                });
            }

            function cleanupModal() {
                $('.pdf-image').empty();
                $("#pdfFile").val('');
                $("#pdfModal").removeClass('show');
                $("body").removeClass('modal-open');
                $("#img-container").empty();
                $(".modal-backdrop").remove();
                $('#pdfModal').modal('hide');
            }


            $(document).on('click', '.deckImgEditBtn', function() {
                let deckId = $(this).data('id');
                let deckName = $(`#deckTitle_${deckId}`).text();
                $("#deckEditFormId").val(deckId);
                $("#name").val(deckName);
                $("#deckEditFormModal").modal('show');
            });

            $(document).on('click', '.deckImgDeleteBtn', function(event) {
                event.preventDefault();
                let deckId = $(this).data('id');
                let deleteUrl = "{{ route('deleteDeckImg', ['id' => ':id']) }}".replace(':id', deckId);
                let confirmMsg = "Are you sure you want to delete this deck?";

                confirmDelete(deleteUrl, confirmMsg, function(response) {
                    // Success callback
                    $('.deckView').html();
                    $('.deckView').html(response.html);
                });
            });

            $('#deckEditForm').submit(function(event) {
                event.preventDefault();

                let formData = $(this).serialize();

                let form = $(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ url('project/updateDeckTitle') }}",
                    data: formData,
                    success: function(response) {
                        let deckData = response.deck;
                        // if (response.status) {
                        //     $(`#deckTitle_${deckData.id}`).text(deckData.name);
                        //     form.trigger('reset');
                        //     $("#deckEditFormModal").modal('hide');
                        // }
                        if (response.isStatus) {
                            successMsg(response.message);
                            $(`#deckTitle_${deckData.id}`).text(deckData.name);
                            form.trigger('reset');
                            $("#deckEditFormModal").modal('hide');
                        } else {
                            errorMsg(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $(document).on("click", ".editCheckbtn", function() {
                let checkDataId = $(this).attr('data-dotId');
                let checkId = $(this).attr('data-id');
                let dotElement = $(`#${checkDataId}`)[0];
                detailOfHazmats(checkId);
            });

            //Check Image javascript code
            let selectedFiles = [];
            $(document).on("click", ".modalAddCheckImage", function() {
                let checkId = $(this).attr('data-id');
                let projectId = $(this).attr('data-projectId');
                $("#checkImageAddFromCheck_id").val(checkId);
                $("#checkImageAddForm").find("#project_id").val(projectId);
                $('#checkImageListPreview').empty(); // Clear previous previews

                $.ajax({
                    type: 'GET',
                    url: "{{ url('check') }}" + "/" + checkId + "/image",
                    success: function(response) {
                        $.each(response.checkImagesList, function(index, value) {
                            $("<div class='checkImagePreview' id='checkImagePreview_" +
                                value.id + "'>" +
                                "<img src='" + value.image + "' width='160px'>" +
                                "<a href='javascript:;' class='ml-2 deleteCheckImage' data-id='" +
                                value.id + "'>" +
                                "<i class='fas fa-trash-alt text-danger' style='font-size: 1rem;'></i>" +
                                "</a>" +
                                "</div>").appendTo("#checkImageListPreview");
                        });
                    },
                });
                $("#checkImageAddModal").modal('show');
            });

            $('#checkImages').on('change', function(event) {
                const files = Array.from(event.target.files);
                $('#checkimagePreviewContainer').empty(); // Clear previous previews
                selectedFiles = files; // Update selectedFiles array

                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $("<div class='checkImagePreview' data-index='" + index + "'>" +
                            "<img src='" + e.target.result + "'>" +
                            "<a href='javascript:;' class='ml-2 delete'>" +
                            "<i class='fas fa-trash-alt text-danger' style='font-size: 1rem;'></i>" +
                            "</a>" +
                            "</div>").appendTo("#checkimagePreviewContainer");
                    }
                    reader.readAsDataURL(file);
                });
            });

            $('#checkimagePreviewContainer').on('click', '.delete', function() {
                const index = $(this).closest('.checkImagePreview').data('index');
                selectedFiles.splice(index, 1); // Remove the file from selectedFiles array
                $(this).closest('.checkImagePreview').remove();

                // Update data-index attribute for the remaining previews
                $('#checkimagePreviewContainer .checkImagePreview').each((i, el) => {
                    $(el).attr('data-index', i);
                });

                if ($('#checkimagePreviewContainer').children().length === 0) {
                    $('#checkImages').val(""); // Clear the input value
                }
            });

            $('#checkImageAddForm').on('submit', function(event) {
                event.preventDefault();

                let checkId = $(this).find("#checkImageAddFromCheck_id").val();
                let projectId = $(this).find("#project_id").val();

                const formData = new FormData();
                formData.append('check_id', checkId);
                formData.append('project_id', projectId);

                selectedFiles.forEach(file => {
                    formData.append('images[]', file);
                });

                let formAction = $(this).attr('action');

                let $submitButton = $(this);
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        swal({
                            title: "Success",
                            text: response.message,
                            timer: 4000
                        });

                        $('#checkimagePreviewContainer').empty();
                        $('#checkImageAddForm')[0].reset();
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                        $("#checkImageAddModal").modal('hide');
                    },
                    error: function(error) {
                        swal({
                            title: "Error",
                            text: error.error,
                            timer: 4000
                        });
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    }
                });
            });

            $("#checkDataImageCloseBtn").click(function() {
                $('#checkimagePreviewContainer').empty();
                $('#checkImageAddForm')[0].reset();
            });

            $(document).on('click', '#checkImageListPreview .deleteCheckImage', function() {
                let imageId = $(this).attr('data-id');
                let deleteUrl = "{{ route('deleteCheckImage', ':id') }}".replace(':id', imageId);
                let confirmMsg = "Are you sure you want to delete this check image?";

                confirmDeleteWithElseIf(deleteUrl, confirmMsg, function(response) {
                    // Success callback
                    if (response.isStatus) {
                        $(`#checkImagePreview_${imageId}`).remove();
                    }
                });
            });

            $(document).on("click", ".modalCheckbtn", function() {
                let checkId = $(this).attr('data-id');
                $("#showCheckImgMsg").text('');
                $.ajax({
                    type: 'GET',
                    url: "{{ url('check') }}" + "/" + checkId + "/image",
                    success: function(response) {
                        if (response.isStatus && response.checkImagesList.length != 0) {
                            let imagesArray = [];
                            $.each(response.checkImagesList, function(index, imageData) {
                                let imageObject = {
                                    src: imageData.image,
                                    opts: {
                                        caption: imageData.caption,
                                        thumb: imageData.image,
                                        id: `fancybox_image_${index}`,
                                        imageId: imageData
                                            .id, // Assuming this is the image ID
                                    }
                                };
                                imagesArray.push(imageObject);
                            });

                            $.fancybox.open(imagesArray, {
                                loop: true,
                                thumbs: {
                                    autoStart: true
                                },
                                afterLoad: function(instance, current) {
                                    // Add rotate button
                                    if (!current.$content.find(
                                            '.fancybox-button--rotate').length) {
                                        current.$content.append(
                                            '<button class="fancybox-button fancybox-button--rotate" title="Rotate">' +
                                            '<i class="fas fa-sync-alt"></i>' +
                                            '</button>'
                                        );
                                    }

                                    if (!current.$content.find(
                                            '.fancybox-button--update').length) {
                                        current.$content.append(
                                            '<button class="fancybox-button fancybox-button--update" title="Save">' +
                                            '<i class="fas fa-save"></i>' +
                                            '</button>'
                                        );
                                    }

                                    // Handle rotate
                                    current.$content.on('click',
                                        '.fancybox-button--rotate',
                                        function() {
                                            var $img = current.$image;
                                            var rotation = $img.data(
                                                'rotation') || 0;
                                            rotation = (rotation + 90) % 360;
                                            $img.css('transform', 'rotate(' +
                                                rotation + 'deg)');
                                            $img.data('rotation', rotation);
                                        });

                                    current.$content.on('click',
                                        '.fancybox-button--update',
                                        function() {
                                            let imageId = current.opts.imageId;
                                            let src = current.src;
                                            let rotation = current.$image.data(
                                                'rotation') || 0;

                                            let csrfToken = $(
                                                    'meta[name="csrf-token"]')
                                                .attr('content');
                                            if (rotation !== 0) {
                                                $.ajax({
                                                    url: "{{ route('changeCheckImgRotation') }}",
                                                    type: 'POST',
                                                    data: {
                                                        imageId: imageId,
                                                        src: src,
                                                        rotation: rotation,
                                                        _token: csrfToken
                                                    },
                                                    success: function(
                                                        response) {
                                                        successMsg(
                                                            response
                                                            .success
                                                        );
                                                    },
                                                    error: function(
                                                        error) {
                                                        errorMsg(
                                                            error
                                                            .error
                                                        );
                                                    }
                                                });
                                            }
                                        });
                                }
                            });
                        } else {
                            errorMsg(
                                "The check image is not available at the moment. Please try again later.",
                                "Image Not Found");
                        }
                    },
                });
            });

            $("#showTableTypeDiv").on("change", ".cloneTableTypeDiv select.table_type", function() {
                const selectedValue = $(this).val();
                const cloneTableTypeDiv = $(this).closest(".cloneTableTypeDiv");
                handleTableTypeChange(selectedValue, cloneTableTypeDiv);
            });

            $("#showLabResult").on("change", ".cloneIHMTableDiv  select.IHM_type", function() {
                const selectedValue = $(this).val();
                const cloneTableTypeDiv = $(this).closest(".cloneIHMTableDiv");
                handleIHMTypeChange(selectedValue, cloneTableTypeDiv);
            });

            let selectedHazmatsIds = [];
            $('#suspected_hazmat').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                let selectedValue = $(this).find('option').eq(clickedIndex).val();
                let selectedText = $(this).find('option').eq(clickedIndex).text();

                if (!isSelected) {
                    selectedHazmatsIds.push(selectedValue);
                    $(`#cloneTableTypeDiv${selectedValue}`).remove();
                    $(`#cloneIHMTableDiv${selectedValue}`).remove();
                } else {
                    let clonedElement = $('#cloneTableTypeDiv').clone();
                    clonedElement.removeAttr("id");
                    clonedElement.attr("id", "cloneTableTypeDiv" + selectedValue);

                    clonedElement.find('label.tableTypeLable').text(selectedText);

                    clonedElement.find('input[type="checkbox"].documentLoadCheckbox').attr('data-id',
                        selectedValue);

                    clonedElement.find('input[type="checkbox"].documentLoadCheckbox').closest('div').prop({
                        id: `documentLoadCheckboxDiv_${selectedValue}`,
                    });

                    clonedElement.find('select').attr('id', `table_type_${selectedValue}`).attr('name',
                        `table_type[${selectedValue}]`);

                    clonedElement.find('input[type="file"].hazmatImg').prop({
                        id: `image_${selectedValue}`,
                        name: `image[${selectedValue}]`
                    });

                    clonedElement.find('div.imageNameShow').prop({
                        id: `imageNameShow_${selectedValue}`,
                    });

                    clonedElement.find('input[type="file"].hazmatDoc').prop({
                        id: `doc_${selectedValue}`,
                        name: `doc[${selectedValue}]`
                    });

                    clonedElement.find('div.docNameShow').prop({
                        id: `docNameShow_${selectedValue}`,
                    });

                    clonedElement.find('select.equipmentSelectTag').prop({
                        id: `equipmentSelectTag_${selectedValue}`,
                        name: `equipmenttt[${selectedValue}]`
                    }).closest('div').prop({
                        id: `equipmentDiv_${selectedValue}`,
                    });

                    clonedElement.find('select.manufacturerSelectTag').prop({
                        id: `manufacturerSelectTag_${selectedValue}`,
                        name: `manufacturer[${selectedValue}]`
                    }).closest('div').prop({
                        id: `manufacturerDiv_${selectedValue}`,
                    });

                    clonedElement.find('select.modelMakePartTag').prop({
                        id: `modelMakePartTag_${selectedValue}`,
                        name: `modelmakepart[${selectedValue}]`
                    }).closest('div').prop({
                        id: `modelMakePartDiv_${selectedValue}`,
                    });

                    clonedElement.find('textarea.remarksTextarea').prop({
                        id: `remarks_${selectedValue}`,
                        name: `remark[${selectedValue}]`
                    });

                    clonedElement.find(`.imagehazmat`).hide();
                    clonedElement.find(`.dochazmat`).hide();
                    clonedElement.find(`.equipment`).hide();
                    clonedElement.find(`.manufacturer`).hide();
                    clonedElement.find(`.modelMakePart`).hide();
                    clonedElement.find(`.remarks`).hide();
                    clonedElement.find(`#documentLoadCheckboxDiv_${selectedValue}`).hide();

                    labResult(selectedValue, selectedText);
                    // Append cloned element to showTableTypeDiv
                    $('#showTableTypeDiv').append(clonedElement);
                }
            });

            $(document).on('change', '.documentLoadCheckbox', function() {
                let id = $(this).attr('data-id');

                if ($(this).is(':checked')) {
                    getHazmatEquipment(id);
                } else {
                    const cloneTableTypeDiv = $(".cloneTableTypeDiv select.table_type").closest(
                        ".cloneTableTypeDiv");

                    cloneTableTypeDiv.find(`#equipmentSelectTag_${id}`).empty().append($('<option>', {
                        value: "",
                        text: "Select Equipment"
                    }));
                    cloneTableTypeDiv.find(`#manufacturerSelectTag_${id}`).empty().append($('<option>', {
                        value: "",
                        text: "First Select Equipment"
                    }));
                    cloneTableTypeDiv.find(`#modelMakePartTag_${id}`).empty().append($('<option>', {
                        value: "",
                        text: "First Select Manufacturer"
                    }));

                    cloneTableTypeDiv.find(`#equipmentDiv_${id}`).closest('.equipment').hide();
                    cloneTableTypeDiv.find(`#manufacturerDiv_${id}`).closest('.manufacturer').hide();
                    cloneTableTypeDiv.find(`#modelMakePartDiv_${id}`).closest('.modelMakePart').hide();
                }
            });

            $(document).on('change', '.equipmentSelectTag', function() {
                let optionValue = $(this).val();
                let id = $(this).attr('data-id');

                if (optionValue != "") {
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('getManufacturer') }}" + "/" + id + "/" + optionValue,
                        success: function(response) {
                            if (response.isStatus) {
                                $(`#manufacturerSelectTag_${id}`).attr('data-id', id);
                                $(`#manufacturerSelectTag_${id}`).attr('data-equipment',
                                    optionValue);
                                $(`#manufacturerSelectTag_${id}`).empty();
                                $(`#manufacturerSelectTag_${id}`).append($(
                                    '<option>', {
                                        value: "",
                                        text: "Select Manufacturer"
                                    }));
                                $(`#modelMakePartTag_${id}`).empty().append($('<option>', {
                                    value: "",
                                    text: "First Select Manufacturer"
                                }));

                                $.each(response.manufacturers, function(index, value) {
                                    $(`#manufacturerSelectTag_${id}`).append($(
                                        '<option>', {
                                            value: value.manufacturer,
                                            text: value.manufacturer
                                        }));
                                });
                            }
                        },
                    });
                } else {
                    $(`#manufacturerSelectTag_${id}`).empty().append($('<option>', {
                        value: "",
                        text: "First Select Equipment"
                    }));
                    $(`#modelMakePartTag_${id}`).empty().append($('<option>', {
                        value: "",
                        text: "First Select Manufacturer"
                    }));
                    $(`#docNameShow_${id}`).empty();
                    $(`#imageNameShow_${id}`).empty();
                }
            });

            $(document).on('change', '.manufacturerSelectTag', function() {
                let optionValue = $(this).val();
                let id = $(this).attr('data-id');
                let equipment = $(this).attr('data-equipment');

                if (optionValue != "") {
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('getManufacturerBasedDocumentData') }}" + "/" + id + "/" +
                            equipment + "/" + optionValue,
                        success: function(response) {
                            if (response.isStatus) {
                                $(`#modelMakePartTag_${id}`).attr('data-id', id);
                                $(`#modelMakePartTag_${id}`).empty();
                                $(`#modelMakePartTag_${id}`).append($(
                                    '<option>', {
                                        value: "",
                                        text: "Select Model Make and Part"
                                    }));

                                $.each(response.documentData, function(index, value) {
                                    $(`#modelMakePartTag_${id}`).append($(
                                        '<option>', {
                                            value: value.id,
                                            text: value.modelmakepart
                                        }));
                                });
                            }
                        },
                    });
                } else {
                    $(`#modelMakePartTag_${id}`).empty().append($('<option>', {
                        value: "",
                        text: "First Select Manufacturer"
                    }));
                    $(`#docNameShow_${id}`).empty();
                    $(`#imageNameShow_${id}`).empty();
                }
            });

            $(document).on('change', '.modelMakePartTag', function() {
                let optionValue = $(this).val();
                let id = $(this).attr('data-id');

                if (optionValue != "") {
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('getPartBasedDocumentFile') }}" + "/" + optionValue,
                        success: function(response) {
                            // console.log(response.documentFile.document1['name']);
                            if (response.isStatus) {
                                let data = response.documentFile;

                                if (data.document1['name'] != null) {
                                    $(`#imageNameShow_${id}`).empty();
                                    let html =
                                        `<a href="${data.document1['path']}" target="_black" > ${data.document1['name']} </a>`;
                                    $(`#imageNameShow_${id}`).append(html);
                                }

                                if (data.document2['name'] != null) {
                                    $(`#docNameShow_${id}`).empty();
                                    let html =
                                        `<a href="${data.document2['path']}" target="_black"> ${data.document2['name']} </a>`;
                                    $(`#docNameShow_${id}`).append(html);
                                }
                            }
                        },
                    });
                } else {
                    $(`#docNameShow_${id}`).empty();
                    $(`#imageNameShow_${id}`).empty();
                }
            });

            $(document).on("click", ".deleteCheckbtn", function(e) {
                e.preventDefault();
                let recordId = $(this).data('id');
                let deleteUrl = $(this).attr("href");
                let $liToDelete = $(this).closest('li');
                let confirmMsg = "Are you sure you want to delete this check?";

                confirmDeleteWithElseIf(deleteUrl, confirmMsg, function(response) {
                    // Success callback
                    if (response.isStatus) {
                        $liToDelete.remove();
                        $(".dot").remove();
                        $('#showDeckCheck').html();
                        $('#checkListUl').html();
                        $('#showDeckCheck').html(response.htmldot);
                        $('#checkListUl').html(response.htmllist);
                        makeDotsDraggable();
                    }
                });
            });

            // Remove Hazmat Document Analysis Results Document
            $(document).on('click', '.removeHazmatDocument', function(e) {
                e.preventDefault();
                let deleteUrl = $(this).attr('href');
                let parentDiv = $(this).closest('div');
                let confirmMsg = "Are you sure you want to delete this document?";

                confirmDeleteWithElseIf(deleteUrl, confirmMsg, function(response) {
                    // Success callback
                    if (response.isStatus) {
                        parentDiv.empty();
                    }
                });
            });

            // Add event listener for Save button click
            $(document).on("click", "#checkDataAddSubmitBtn", function() {
                let checkFormData = new FormData($("#checkDataAddForm")[0]);
                checkFormData.append('deselectId', selectedHazmatsIds);
                let $submitButton = $(this);
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: $("#checkDataAddForm").attr('action'),
                    data: checkFormData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.isStatus) {
                            $("#checkListTable").html(response.trtd);
                            successMsg(response.message);
                            $("#checkDataAddForm")[0].reset();
                            $("#id").val("");
                            $submitButton.html(originalText);
                            $submitButton.prop('disabled', false);
                            $("#checkDataAddModal").modal('hide');
                        } else {
                            $.each(response.message, function(field, messages) {
                                $('#' + field + 'Error').text(messages[0]).show();
                                $('[name="' + field + '"]').addClass('is-invalid');
                            });

                            $submitButton.html(originalText);
                            $submitButton.prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    }
                });
            });
        });
        $('.addfiles').on('click', function() {
            $('#image').click();
            return false;
        });
    </script>
@endpush
