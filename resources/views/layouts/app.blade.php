@include('layouts.head')

<body>
<div class="bg-overlay" style="display: none;"></div>

	<div class="dashboard-main-wrapper">

		@include('layouts.navbar')

		@include('layouts.sidebar')

		<div class="dashboard-wrapper">
			@yield('content')

			@include('layouts.footer')

		</div>

	</div>

	@include('layouts.script')
</body>

</html>
