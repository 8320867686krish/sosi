 @foreach ($laboratory as $value)
	<div class="col-6">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="pl-xl-3">
							<div class="m-b-0">
								<div class="user-avatar-name d-inline-block">
									<h2 class="font-24 m-b-10">{{ $value['name'] }}</h2>
								</div>
							</div>
							<div class="user-avatar-address">
								<p class="mb-2">{{ $value['details'] }}</p>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="float-xl-right float-none mt-xl-0 mt-4">
							<a href="#" rel="noopener noreferrer" title="Edit">
								<i class="fas fa-edit text-primary" style="font-size: 1rem"></i>
							</a>
							<a href="#" class="ml-2" onclick="removeLab('{{ $value['id'] }}')" title="Delete">
								<i class="fas fa-trash-alt text-danger" style="font-size: 1rem"></i>
							</a>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
 @endforeach
 @push('js')
    <script>
		function removeLab(id) {
			$.ajax({
				type: 'DELETE',
				url: "{{ url('laboratory/remove/') }}" + "/" + id,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					if (response.status) {
						$(".loadView").html(response.html)
						$("#laboratoryModal").modal('hide');
					}
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
				}
			});
		}
    </script>
 @endpush