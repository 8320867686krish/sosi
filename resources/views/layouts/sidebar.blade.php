<div class="nav-left-sidebar sidebar-dark" id="mainSidebar">
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
                        {{-- <div class="row">
                            <div class="col-6">
                            </div>
                            <div class="col-6">
                                <button class="navbar-toggler" style="display: block !important;" type="button" id="sidebarCollapse">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                            </div>
                        </div> --}}
                    </li>
                    @foreach ($allPermissions as $permission)
                        @if ($permission['group_type'] === 'main' && $permission['is_show'] == 1)
                            @can($permission['name'])
                                <li class="nav-item">
                                    @php
                                        $isActive = false;

                                        $newPermissionName = str_replace('_', ' ', $permission['name']);

                                        // $endsWithS = Str::endsWith(Request::segment(1), 's');
                                        $string = Request::segment(1);
                                        $endsWithS = substr($string, -1) === 's';

                                        if (!$endsWithS) {
                                            if ($permission['name'] == $string) {
                                                $string = $string;
                                            } else {
                                                $string = $string . "s";
                                            }
                                        }

                                        if (request()->routeIs($permission['name'])) {
                                            $isActive = true;
                                        }

                                        if ($string == $permission['name']) {
                                            $isActive = true;
                                        }

                                    @endphp
                                    <a class="nav-link {{ $isActive ? 'active' : '' }} "
                                        href="{{ route($permission['name']) }}"><i
                                            class="fa fa-fw fa-user-circle"></i>{{ ucwords($newPermissionName) }}<span
                                            class="badge badge-success">6</span></a>
                                </li>
                            @endcan
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</div>
