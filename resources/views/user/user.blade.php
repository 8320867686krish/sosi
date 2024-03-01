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
                    <h2 class="pageheader-title">User Management</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">User</a></li>
                                <!-- <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Pages</a></li> -->
                                <!-- <li class="breadcrumb-item active" aria-current="page">Blank Pageheader</li> -->
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
                    <h4 class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>App User</h3>
                            </div>
                            <div class="col-6">
                                @can('users.add')
                                    <a href="{{ route('users.add') }}" class="btn btn-primary float-right">Add New User</a>
                                @endcan
                            </div>
                        </div>
                    </h4>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered first">
                                <thead>
                                    <tr>
                                        <th width="8%">Sr.No</th>
                                        <th>Name</th>
                                        <th width="15%">Roles</th>
                                        <th width="18%">Email</th>
                                        {{-- <th width="10%">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($users) && $users->count() > 0)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($user->name) ?? '' . ' ' . ucfirst($user->last_name) ?? '' }}</td>
                                                <td>{{ ucfirst($user->roles[0]->name ?? '') }}</td>
                                                <td>{{ $user->email }}</td>
                                                {{-- <td>
                                                    @can('users.edit')
                                                        <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                                                            rel="noopener noreferrer" title="Edit">
                                                            <i class="fas fa-edit fa-2x text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('users.delete')
                                                        <a href="{{ route('users.delete', ['id' => $user->id]) }}"
                                                            class="ml-2"
                                                            onclick="return confirm('Are you sure you want to delete this user ?');">
                                                            <i class="fas fa-trash fa-2x text-danger"></i>
                                                        </a>
                                                    @endcan
                                                </td> --}}
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
