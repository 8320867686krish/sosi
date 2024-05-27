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
                    <h2 class="pageheader-title">Document Declaration Management</h2>
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
                                <h3>Document Declaration</h3>
                            </div>
                            <div class="col-6">
                                @can('documentdeclaration.add')
                                    <a href="{{ route('documentdeclaration.add') }}"
                                        class="btn btn-primary float-right btn-rounded addNewBtn">Add New Document</a>
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
                                        <th>Document 1</th>
                                        <th>Document 2</th>
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
                                                <td>
                                                    <a href="{{ $model->document1['path'] }}" target="_black">{{ $model->document1['name'] }}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ $model->document2['path'] }}" target="_black" >{{ $model->document2['name'] }}</a>
                                                </td>
                                                <td class="text-center">
                                                    @can('documentdeclaration.edit')
                                                        <a href="{{ route('documentdeclaration.edit', ['id' => $model->id]) }}"
                                                            rel="noopener noreferrer" title="Edit" class="text-center">
                                                            <i class="fas fa-edit text-primary" style="font-size: 1rem"></i>
                                                        </a>
                                                    @endcan
                                                    @can('documentdeclaration.remove')
                                                        <a href="javascript:;" data-id="{{ $model->id }}"
                                                            class="ml-2 delete-btn" title="Delete">
                                                            <i class="fas fa-trash-alt text-danger"
                                                                style="font-size: 1rem !important"></i>
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
            $('.delete-btn').on('click', function() {
                let recordId = $(this).data('id');
                let deleteUrl = "{{ route('documentdeclaration.delete', ':id') }}".replace(':id', recordId);
                let $deleteButton = $(this);
                let confirmMsg = "Are you sure you want to delete this document?";

                confirmDelete(deleteUrl, confirmMsg, function(response) {
                    // Success callback
                    $deleteButton.closest('.makeModelTrTag').remove();
                }, function(response) {
                    // Error callback (optional)
                    console.log("Failed to delete: " + response.message);
                });
            });
        });
    </script>
@endpush
