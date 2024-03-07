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
                    <h2 class="pageheader-title">Client Management</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="#" class="breadcrumb-link">Client</a></li>
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
                                <h3>Client List</h3>
                            </div>
                            <div class="col-6">
                                @can('clients.add')
                                    <a href="{{ route('clients.add') }}" class="btn btn-primary float-right btn-rounded addNewBtn">Add New Client</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered first">
                                <thead>
                                    <tr>
                                        <th width="6%">Sr.No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Identification</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($clients) && $clients->count() > 0)
                                        @foreach ($clients as $client)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucwords($client->name) }}</td>
                                                <td>{{ $client->email }}</td>
                                                <td>{{ $client->phone }}</td>
                                                <td>{{ $client->identification }}</td>
                                                <td>
                                                    @can('clients.edit')
                                                        <a href="{{ route('clients.edit', ['id' => $client->id]) }}"
                                                            rel="noopener noreferrer" title="Edit">
                                                            <i class="fas fa-edit text-primary"
                                                                style="font-size: 1rem !important;"></i>
                                                        </a>
                                                    @endcan
                                                    @can('clients.remove')
                                                        <a href="{{ route('clients.delete', ['id' => $client->id]) }}"
                                                            class="ml-2"
                                                            onclick="return confirm('Are you sure you want to delete this client ?');"
                                                            title="Delete">
                                                            <i class="fas fa-trash-alt text-danger"
                                                                style="font-size: 1rem !important;"></i>
                                                        </a>
                                                    @endcan
                                                    @can('projects.read')
                                                        <a href="{{ route('projects.client', ['client_id' => $client->id]) }}"
                                                            class="ml-2">
                                                            <i class="fas fas fa-list" style="font-size: 1rem !important;"></i>
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
    </div>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/data-table.js') }}"></script>
@endsection
