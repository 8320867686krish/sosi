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
            <div class="col-12">
                @include('layouts.message')
            </div>
            <div class="showSuccessMsg col-12" style="display: none;"></div>
            <div class="col-12 mb-4">
                @can('clients.add')
                    <a href="{{ route('clients.add') }}" class="btn btn-primary float-right btn-rounded addNewBtn">Add New
                        Client</a>
                @endcan
            </div>
            @if (isset($clients) && $clients->count() > 0)
                @foreach ($clients as $client)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 clientShowCard">
                        <div class="card campaign-card text-center">
                            <div class="card-header">
                                <h4 class="d-flex justify-content-between align-items-center mb-0">
                                    <span class="text-muted">Projects</span>
                                    <span class="badge badge-secondary badge-pill">{{ $client->total_projects ?? 0 }}</span>
                                </h4>
                            </div>
                            <div class="card-body">

                                <div class="campaign-img">
                                    <img src="{{ $client->managerLogoPath }}" alt="user"
                                        class="user-avatar-xl rounded-circle">
                                </div>
                                <div class="campaign-info">
                                    <h3 class="mb-1">{{ ucfirst($client->manager_name) ?? '' }}</h3>
                                    <p class="mb-3">Total Project:<span
                                            class="text-dark font-medium ml-2">{{ $client->total_projects ?? 0 }}</span>
                                    </p>
                                    <p class="mb-1">Phone: <span
                                            class="text-dark font-medium ml-2">{{ $client->manager_phone ?? '' }}</span></p>
                                    <p>Ship owner.:<span
                                            class="text-dark font-medium ml-2">{{ ucwords($client->owner_name) ?? '' }}</span>
                                    </p>
                                    @can('clients.edit')
                                        <a href="{{ route('clients.edit', ['id' => $client->id]) }}" rel="noopener noreferrer"
                                            title="Edit">
                                            <i class="fas fa-edit fa-sm text-primary"></i>
                                        </a>
                                    @endcan
                                    @can('clients.remove')
                                        <a href="javascript:;" data-id="{{ $client->id }}" class="ml-2 delete-btn"
                                            title="Delete">
                                            <i class="fas fa-trash-alt fa-sm text-danger"></i>
                                        </a>
                                    @endcan
                                    {{-- <a href="#"><i class="fab fa-twitter-square fa-sm twitter-color"></i> </a><a href="#"><i class="fab fa-snapchat-square fa-sm snapchat-color"></i></a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Data not found
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('js')
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

                $('.showSuccessMsg').html(successMessage);
                // Display the .showSuccessMsg element
                $('.showSuccessMsg').fadeIn().delay(20000).fadeOut();
                // Clear the message from localStorage
                localStorage.removeItem('message');
            }

            $('.delete-btn').on('click', function() {
                let recordId = $(this).data('id');
                let deleteUrl = "{{ route('clients.delete', ':id') }}".replace(':id', recordId);
                var $deleteButton = $(this);

                // Show confirmation dialog
                if (confirm("Are you sure you want to delete this client?")) {
                    // User confirmed, send AJAX request
                    $.ajax({
                        url: deleteUrl,
                        method: 'GET',
                        success: function(response) {

                            if (response.isStatus) {
                                // $deleteButton.parents('.clientShowCard').first().remove();
                                $deleteButton.closest('.clientShowCard').remove();
                            }

                            let successMessage = `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${response.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`;

                            $('.showSuccessMsg').html(successMessage);

                            // Display the .showSuccessMsg element
                            $('.showSuccessMsg').fadeIn().delay(20000).fadeOut();
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
@endsection
