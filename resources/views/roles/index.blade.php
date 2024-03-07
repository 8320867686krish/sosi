@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/buttons.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/select.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/fixedHeader.bootstrap4.css')}}">
@endsection

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Role Management</h2>
                    {{-- <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Role</a></li>
                                <!-- <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Pages</a></li> -->
                                <!-- <li class="breadcrumb-item active" aria-current="page">Blank Pageheader</li> -->
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
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div class="card">
                    <h5 class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Roles</h3>
                            </div>
                            @can('roles.add')
                                <div class="col-6">
                                    <a href="{{ route('roles.add') }}" class="btn btn-primary float-right btn-rounded addNewBtn">Add New Role</a>
                                </div>
                            @endcan
                        </div>
                    </h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered first">
                                <thead>
                                    <tr>
                                        <th width="10%">Sr.No</th>
                                        <th>Name</th>
                                        <th width="10%">Guard Name</th>
                                        <th width="8%">Level</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset(($roles)) && $roles->count() > 0)
                                        @foreach($roles as $role)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($role->name) }}</td>
                                                <td>{{ ucfirst($role->guard_name) }}</td>
                                                <td>{{ $role->level }}</td>
                                                <td>
													@can('roles.edit')
														<a href="{{ route('roles.edit', ['id' => $role->id]) }}" rel="noopener noreferrer" title="Edit">
															<i class="fas fa-edit text-primary" style="font-size: 1rem;"></i>
														</a>
													@endcan
													@can('roles.remove')
														<a href="{{ route('roles.delete', ['id' => $role->id]) }}" class="ml-2" onclick="return confirm('Are you sure you want to delete this role ?');">
															<i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
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
