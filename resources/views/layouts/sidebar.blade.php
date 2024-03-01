<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">
                        Menu
                    </li>
                    @foreach ($allPermissions as $permission)
                        @if ($permission['group_type'] === 'main' && $permission['is_show'] == 1)
                            @can($permission['name'])
                                <li class="nav-item">
                                    @php
                                        $newPermissionName = str_replace('_', ' ', $permission['name']);
                                    @endphp
                                    <a class="nav-link" href="{{ route($permission['name']) }}"><i class="fa fa-fw fa-user-circle"></i>{{ ucwords($newPermissionName) }}<span class="badge badge-success">6</span></a>
                                </li>
                            @endcan
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</div>
