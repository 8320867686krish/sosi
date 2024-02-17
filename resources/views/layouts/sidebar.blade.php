<div class="nav-left-sidebar sidebar-dark">
	<div class="menu-list">
		<nav class="navbar navbar-expand-lg navbar-light">
			<a class="d-xl-none d-lg-none" href="#">Dashboard</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav flex-column">
					<li class="nav-divider">
						Menu
					</li>
					@foreach($allPermissions as $permission)
						@if($permission['group_type'] === 'main')
						<li class="nav-item ">

								<a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-{{$loop->iteration }}" aria-controls="submenu-{{ $loop->iteration }}"><i class="fa fa-fw fa-user-circle"></i>{{ ucfirst($permission['name'])}} <span class="badge badge-success">6</span></a>
								<div id="submenu-{{ $loop->iteration }}" class="collapse submenu" style="">
								<ul class="nav flex-column">
									@foreach($allPermissions as $submenu)
										@if($submenu['group_type'] === $permission['name'])
										@php
                                $displayValue = substr(strrchr($submenu['name'], '.'), 1);
                            @endphp
											<li class="nav-link"><a href="../dashboard-finance.html">{{ ucFirst($displayValue) }}</a></li>
										@endif
									@endforeach
								</ul>
								</div>
							</li>
						@endif
					@endforeach
				</ul>
			</div>
		</nav>
	</div>
</div>
