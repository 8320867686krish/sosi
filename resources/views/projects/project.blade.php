@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/select.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/fixedHeader.bootstrap4.css') }}">
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
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="#" class="breadcrumb-link">Project</a>
                                </li>
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
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Projects</h3>
                            </div>
                            <div class="col-6">
                                @can('projects.add')
                                    <a href="{{ route('projects.add') }}" class="btn btn-primary float-right btn-rounded addNewBtn">Add New
                                        Project</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered first">
                                <thead>
                                    <tr>
                                        <th width="8%">Sr.No</th>
                                        <th>Owner Name</th>
                                        <th>Ship Name</th>
                                        <th>Ship Type</th>
                                        <th>IHM Table</th>
                                        <th>Project No</th>
                                        <th width="15%">IMO No</th>
                                        <th width="6%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($projects) && $projects->count() > 0)
                                        @foreach ($projects as $project)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($project->ship_owner->name) }}</td>
                                                <td>{{ ucfirst($project->ship_name) }}</td>
                                                <td>{{ $project->ship_type }}</td>
                                                <td>{{ $project->ihm_table }}</td>
                                                <td>{{ $project->project_no }}</td>
                                                <td>{{ $project->imo_number }}</td>
                                                <td>
                                                    @can('projects.edit')
                                                        <a href="{{ route('projects.edit', ['id' => $project->id]) }}"
                                                            rel="noopener noreferrer" title="Edit">
                                                            <i class="fas fa-edit  text-primary" style="font-size: 1rem"></i>
                                                        </a>
                                                    @endcan
                                                    @can('projects.remove')
                                                        <a href="{{ route('projects.delete', ['id' => $project->id]) }}"
                                                            class="ml-2"
                                                            onclick="return confirm('Are you sure you want to delete this project?');"
                                                            title="Delete">
                                                            <i class="fas fa-trash-alt  text-danger" style="font-size: 1rem"></i>
                                                        </a>
                                                    @endcan
                                                    @can('projects.read')
                                                        <a href="{{ route('projects.view', ['project_id' => $project->id]) }}"
                                                            rel="noopener noreferrer" title="Edit">
                                                            <i class="fas fa-eye  text-primary" style="font-size: 1rem"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @stop

    @section('js')
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables/js/data-table.js') }}"></script>
    @endsection
