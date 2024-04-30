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
                    <h2 class="pageheader-title">Role Management</h2>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div id="showSucessMsg" style="display: none;"></div>
                <div class="card">
                    <h5 class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Roles</h3>
                            </div>
                            @can('roles.add')
                                <div class="col-6">
                                    <a href="{{ route('roles.add') }}"
                                        class="btn btn-primary float-right btn-rounded addNewBtn">Add New Role</a>
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
                                    @if (isset($roles) && $roles->count() > 0)
                                        @foreach ($roles as $role)
                                            <tr class="rolesRowTr">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($role->name) }}</td>
                                                <td>{{ ucfirst($role->guard_name) }}</td>
                                                <td>{{ $role->level }}</td>
                                                <td>
                                                    @can('roles.edit')
                                                        <a href="{{ route('roles.edit', ['id' => $role->id]) }}"
                                                            rel="noopener noreferrer" title="Edit">
                                                            <i class="fas fa-edit text-primary" style="font-size: 1rem;"></i>
                                                        </a>
                                                    @endcan
                                                    @can('roles.remove')
                                                        <a href="{{ route('roles.delete', ['id' => $role->id]) }}" class="ml-2 delete-btn">
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

@push('js')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/data-table.js') }}"></script>

    <script>
        $(document).ready(function() {
            let message = localStorage.getItem('message');
            if (message) {
                // Display the message on the page
                let successMessage = `
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`;

                $('#showSucessMsg').html(successMessage);
                // Display the .showSuccessMsg element
                $('#showSucessMsg').fadeIn().delay(20000).fadeOut();
                // Clear the message from localStorage
                localStorage.removeItem('message');
            }

            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                let deleteUrl = $(this).attr('href');
                var $deleteButton = $(this);

                if (confirm("Are you sure you want to delete this role?")) {
                    // User confirmed, send AJAX request
                    $.ajax({
                        url: deleteUrl,
                        method: 'GET',
                        success: function(response) {
                            if (response.isStatus) {
                                $deleteButton.closest('.rolesRowTr').remove();
                            }

                            let successMessage = `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${response.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`;

                            $('#showSucessMsg').html(successMessage);

                            // Display the .showSuccessMsg element
                            $('#showSucessMsg').fadeIn().delay(20000).fadeOut();
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX errors
                            alert("Error deleting record: " + error);
                        }
                    });
                }
            });
        });
    </script>
@endpush
