@extends('layouts.app')

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Role Management</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('roles') }}" class="breadcrumb-link">Role</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"
                                        class="breadcrumb-link">{{ $head_title ?? '' }}</a></li>
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
                    <h5 class="card-header">{{ $head_title ?? '' }} Role</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('roles.store') }}" class="needs-validation" novalidate
                            id="rolesForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $role->id ?? null }}">

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Role"
                                    value="{{ $role->name ?? '' }}">
                                @error('name')
                                    <div class="text-danger">{{ $errors->first('name') }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="level">Level</label>
                                <input type="text" class="form-control @error('level') is-invalid @enderror"
                                    id="level" value="{{ old('level', $role->level ?? '') }}" name="level"
                                    placeholder="Role Level..." autocomplete="off" onchange="removeInvalidClass(this)">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group ">
                                <label for="Permissions">Permissions</label></br>
                                @foreach ($allPermissions as $permission)
                                    <div class="row">
                                        @if ($permission['group_type'] === 'main')
                                            <label
                                                class="col-3 col-sm-3 col-form-label">{{ ucfirst($permission['name']) }}</label>
                                            <div class="col-9">
                                                @foreach ($allPermissions as $submenu)
                                                    @if ($submenu['group_type'] === $permission['name'])
                                                        <label class="custom-control custom-checkbox custom-control-inline">
                                                            @php
                                                                $displayValue = substr(strrchr($submenu['name'], '.'), 1);
                                                            @endphp
                                                            @if (@$role_permissions && in_array($submenu['id'], $role_permissions))
                                                                <?php $checked = 'checked'; ?>
                                                            @else
                                                                <?php $checked = ''; ?>
                                                            @endif
                                                            <input type="checkbox" value="{{ $submenu['name'] }}"
                                                                name="permissions[{{ $permission['name'] }}][]"
                                                                {{ $checked }} class="custom-control-input"><span
                                                                class="custom-control-label">
                                                                {{ ucfirst($displayValue) }}
                                                            </span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <a href="{{route('roles')}}" class="btn btn-info btn-rounded formBackBtn"
                                            type="button">Back</a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right btn-rounded formSubmitBtn"
                                            type="submit">{{ $button ?? 'Add' }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@stop