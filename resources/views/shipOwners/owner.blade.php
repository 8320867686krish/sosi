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
                    <h2 class="pageheader-title">Owner Management</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="#" class="breadcrumb-link">Owner</a></li>
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
                                <h3>Ship Owner</h3>
                            </div>
                            <div class="col-6">
                                @can('ship_owners.add')
                                    <a href="{{ route('ship_owners.add') }}" class="btn btn-primary float-right btn-rounded addNewBtn">Add New Owner</a>
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
                                        <th width="15%">Email</th>
                                        <th width="12%">Phone</th>
                                        <th width="8%">Image</th>
                                        <th width="12%">Identification</th>
                                        <th width="5%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($owners) && $owners->count() > 0)
                                        @foreach ($owners as $owner)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($owner->name) }}</td>
                                                <td>{{ $owner->email }}</td>
                                                <td>{{ ucfirst($owner->phone) }}</td>
                                                <td><img src="{{$owner->imagePath}}" alt="Image Not Found" class="img-thumbnail"></td>
                                                <td>{{ $owner->identification }}</td>
                                                <td>
                                                    @can('ship_owners.edit')
                                                        <a href="{{ route('ship_owners.edit', ['id' => $owner->id]) }}"
                                                            rel="noopener noreferrer" title="Edit">
                                                            <i class="fas fa-edit text-primary" style="font-size: 1rem !important;"></i>
                                                        </a>
                                                    @endcan
                                                    @can('ship_owners.remove')
                                                        <a href="{{ route('ship_owners.delete', ['id' => $owner->id]) }}"
                                                            class="ml-2"
                                                            onclick="return confirm('Are you sure you want to delete this owner ?');" title="Delete">
                                                            <i class="fas fa-trash-alt text-danger" style="font-size: 1rem !important;"></i>
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
