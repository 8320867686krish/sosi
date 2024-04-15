@extends('layouts.app')

@section('css')

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
        <div class="row equal-height">
        @if (isset($projects) && $projects->count() > 0)
                @foreach ($projects as $project)
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <a href="{{ route('projects.view', ['project_id' => $project->id]) }}">
                            <div class="card campaign-card text-center pt-0 pb-0">
                                <div class="card-body">
                                    <div class="campaign-img">
                                        <img src="{{ asset("images/ship/{$project->image}") }}" onerror="this.onerror=null;this.src='{{ asset('assets/images/dribbble.png') }}';" class="user-avatar-xl rounded-circle">
                                    </div>
                                    <div class="campaign-info">
                                    <h3 class="mb-1">{{ ucfirst($project->ship_name) }}</h3>
                                    <p class="mb-1 line-clamp">IMO Number:<span class="text-dark font-medium ml-2">{{ $project->imo_number }}</span></p>
                                    <p class="mb-1 line-clamp">Manager: <span class="text-dark font-medium ml-2">{{ ucwords($project->client->manager_name ?? '') }}</span>
                                    </p>
                                    <p class="line-clamp">Project No.:<span class="text-dark font-medium ml-2">{{ $project->project_no }}</span></p>
                                  
                                    <div class="progress mt-2 mb-3">
  <div class="progress-bar" role="progressbar" aria-valuenow="{{$project->projectPercentage}}"
  aria-valuemin="0" aria-valuemax="100" style="width:{{$project->projectPercentage}}%">
    {{$project->projectPercentage}}
  </div>
</div>
                                    @can('projects.edit')
                                        <a href="{{ route('projects.edit', ['id' => $project->id]) }}" rel="noopener noreferrer" title="Edit">
                                            <i class="fas fa-edit text-primary" style="font-size: 1rem"></i>
                                        </a>
                                    @endcan
                                    @can('projects.remove')
                                        <a href="{{ route('projects.delete', ['id' => $project->id]) }}" class="ml-2" onclick="return confirm('Are you sure you want to delete this project?');" title="Delete">
                                            <i class="fas fa-trash-alt text-danger" style="font-size: 1rem"></i>
                                        </a>
                                    @endcan
                                    @can('projects.read')
                                        <a href="{{ route('projects.view', ['project_id' => $project->id]) }}" rel="noopener noreferrer" title="View" class="ml-2">
                                            <i class="fas fa-eye text-info" style="font-size: 1rem"></i>
                                        </a>
                                    @endcan
                                </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                @else
        <div class="col-12">
            <div class="alert alert-danger fade show text-center" role="alert">
                Data not found.
            </div>
        </div>
        @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/js/data-table.js') }}"></script>
<!-- chart js -->

<script>
    $(document).ready(matchHeight);
    $(window).resize(matchHeight);
</script>
@endsection