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
                    <h2 class="pageheader-title">Make Model Management</h2>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @include('layouts.message')
                <div id="showSuccessMsg"></div>
                <div class="card">
                    <h4 class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Make Model</h3>
                            </div>
                            <div class="col-6">
                                @can('makemodel.add')
                                    <a href="{{ route('makemodel.add') }}"
                                        class="btn btn-primary float-right btn-rounded addNewBtn">Add New Model</a>
                                @endcan
                            </div>
                        </div>
                    </h4>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered first" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">Sr.No</th>
                                        <th>Hazmat</th>
                                        <th>Equipment</th>
                                        <th>Model</th>
                                        <th>Make</th>
                                        <th>Manufacturer</th>
                                        <th>Part</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($models) && $models->count() > 0)
                                        @foreach ($models as $model)
                                            <tr class="makeModelTrTag">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $model->hazmat->name }}</td>
                                                <td>{{ $model->equipment }}</td>
                                                <td>{{ $model->model }}</td>
                                                <td>{{ $model->make }}</td>
                                                <td>{{ $model->manufacturer }}</td>
                                                <td>{{ $model->part }}</td>
                                                <td class="text-center">
                                                    @can('makemodel.edit')
                                                        <a href="{{ route('makemodel.edit', ['id' => $model->id]) }}" rel="noopener noreferrer" title="Edit" class="text-center">
                                                            <i class="fas fa-edit text-primary" style="font-size: 1rem"></i>
                                                        </a>
                                                    @endcan
                                                    @can('makemodel.remove')
                                                        <a href="javascript:;" data-id="{{ $model->id }}" class="ml-2 delete-btn" title="Delete">
                                                            <i class="fas fa-trash-alt text-danger" style="font-size: 1rem !important"></i>
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

                $('#showSuccessMsg').html(successMessage);
                // Display the .showSuccessMsg element
                $('#showSuccessMsg').fadeIn().delay(20000).fadeOut();
                // Clear the message from localStorage
                localStorage.removeItem('message');
            }

            $('.delete-btn').on('click', function() {
                let recordId = $(this).data('id');
                let deleteUrl = "{{ route('makemodel.delete', ':id') }}".replace(':id', recordId);
                let $deleteButton = $(this);

                // Show confirmation dialog
                if (confirm("Are you sure you want to delete this make model?")) {
                    // User confirmed, send AJAX request
                    $.ajax({
                        url: deleteUrl,
                        method: 'GET',
                        success: function(response) {

                            if (response.isStatus) {
                                $deleteButton.closest('.makeModelTrTag').remove();
                            }

                            let successMessage = `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${response.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`;

                            $('#showSuccessMsg').html(successMessage);

                            // Display the .showSuccessMsg element
                            $('#showSuccessMsg').fadeIn().delay(20000).fadeOut();
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