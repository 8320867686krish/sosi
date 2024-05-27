@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/select.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/fixedHeader.bootstrap4.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/css/bootstrap4-toggle.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/css/switchButton.css') }}">
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
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div class="showSucessMsg" style="display: none;"></div>
                <div class="card">
                    <h4 class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>App User</h3>
                            </div>
                            <div class="col-6">
                                @can('users.add')
                                    <a href="{{ route('users.add') }}"
                                        class="btn btn-primary float-right btn-rounded addNewBtn">Add New User</a>
                                @endcan
                            </div>
                        </div>
                    </h4>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered first">
                                <thead>
                                    <tr>
                                        <th width="5%">Sr.No</th>
                                        <th>Name</th>
                                        <th width="15%">Roles</th>
                                        <th width="18%">Email</th>
                                        <th width="10%">Phone</th>
                                        <th width="10%">Location</th>
                                        <th width="8%">Status</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($users) && $users->count() > 0)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($user->name ?: '') . ($user->last_name ? ' ' . ucfirst($user->last_name) : '') }}
                                                </td>
                                                <td>{{ ucfirst($user->roles[0]->name ?? '') }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->location ?? '' }}</td>
                                                <td>
                                                    <label class="switch">
                                                        <input class="switch-input" type="checkbox"
                                                            {{ $user->isVerified ? 'checked' : '' }}
                                                            data-id="{{ $user->id }}">
                                                        <span class="switch-label" data-on="" data-off=""></span>
                                                        <span class="switch-handle"></span>
                                                    </label>
                                                </td>
                                                <td class="text-center">
                                                    @can('users.edit')
                                                        <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                                                            rel="noopener noreferrer" title="Edit" class="text-center">
                                                            <i class="fas fa-edit text-primary" style="font-size: 1rem"></i>
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
    <script src="{{ asset('assets/libs/js/bootstrap4-toggle.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('.switch-input').change(function() {
                let isChecked = $(this).is(':checked');
                let userId = $(this).data('id');
                let initialState = !isChecked; // Capture the initial state
                let $checkbox = $(this);
                let message = isChecked ? "Are you sure you want to enable this user?" :
                    "Are you sure you want to disable this user?";

                confirmChangeStatus(message, function() {
                    $.ajax({
                        url: "{{ route('change.isVerified') }}",
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": userId,
                            "isVerified": isChecked ? 1 : 0
                        },
                        success: function(response) {
                            swal("Success", response.message, "success");
                        },
                        error: function(xhr, status, error) {
                            swal("Error", "An error occurred: " + error, "error");
                            $checkbox.prop('checked',
                            initialState); // Revert checkbox state
                        }
                    });
                }, function() {
                    $checkbox.prop('checked', initialState); // Revert checkbox state if cancelled
                });
            });

            function confirmChangeStatus(message, confirmCallback, cancelCallback) {
                swal({
                    title: "Are you sure?",
                    text: message,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, do it!",
                    cancelButtonText: "No, cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        confirmCallback();
                    } else {
                        cancelCallback();
                        swal("Cancelled", "Your action has been cancelled.", "error");
                    }
                });
            }
        });
    </script>
@endpush
