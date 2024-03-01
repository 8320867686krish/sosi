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
                        @if ($permission['group_type'] === 'main')
                            @can($permission['name'])
                                @php
                                    $isActive = false;
                                @endphp
                                @foreach ($allPermissions as $submenu)
                                    @if ($submenu['group_type'] === $permission['name'])
                                        @if (Route::is($submenu['name']))
                                            @php
                                                $isActive = true;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                                <li class="nav-item">
                                    @php
                                        $newPermissionName = str_replace('_', ' ', $permission['name']);
                                    @endphp
                                    <a class="nav-link {{ $isActive ? 'active' : '' }}" href="#"
                                        data-toggle="collapse" aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                                        data-target="#submenu-{{ $loop->iteration }}"
                                        aria-controls="submenu-{{ $loop->iteration }}"><i
                                            class="fa fa-fw fa-user-circle"></i>{{ ucfirst($newPermissionName) }} <span
                                            class="badge badge-success">6</span></a>
                                    <div id="submenu-{{ $loop->iteration }}"
                                        class="collapse submenu {{ $isActive ? 'show' : '' }}" style="">
                                        <ul class="nav flex-column">
                                            @foreach ($allPermissions as $submenu)
                                                @if ($submenu['group_type'] === $permission['name'])
                                                    @php
                                                        $displayValue = substr(strrchr($submenu['name'], '.'), 1);
                                                    @endphp
                                                    @can($submenu['name'])
                                                        @if ($displayValue == 'list' || $displayValue == 'add')
                                                            <li class="nav-link">
                                                                <a href="{{ route($submenu['name']) }}">{{ ucFirst($displayValue) }}</a>
                                                            </li>
                                                        @endif
                                                    @endcan
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endcan
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</div>
