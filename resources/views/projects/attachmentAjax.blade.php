 <div class="col-12">
 	<div class="card">
 		<div class="card-body">
 			<div class="table-responsive">
 				<table class="table table-striped table-bordered first">
 					<thead>
 						<tr>
 							<th>Heading</th>
 							<th>Heading</th>
 							<th>Documents</th>
 							<th>Action</th>

 						</tr>
 					</thead>
 					<tbody>
 						@foreach ($attachment as $value)
 						<tr>
 							<td>{{$value['id']}}</td>
 							<td>{{$value['heading']}}</td>
 							<td>{{$value['documents']}}</td>
 							<td><a href="#" rel="noopener noreferrer" data-attachment ="{{json_encode($value)}}" class="editAttachment" title="Edit">
 									<i class="fas fa-edit text-primary" style="font-size: 1rem"></i>
 								</a>
 								<a href="#" class="ml-2" onclick="removeLab('{{ $value['id'] }}')" title="Delete">
 									<i class="fas fa-trash-alt text-danger" style="font-size: 1rem"></i>
 								</a>
 							</td>

 						</tr>
 						@endforeach
 					</tbody>

 				</table>
 			</div>

 		</div>
 	</div>
 </div>
 @push('js')
 <script>
$(document).on('click', '.editAttachment', function() {
	var attachment = $(this).data('attachment');

	$("#id").val(attachment.id);
	$("#heading").val(attachment.heading);
$(".documentsValue").show();
$(".documentsValue").text(attachment.documents);

	$("#attachmentModel").modal('show');

	console.log(attachment.id);
	
});

 	function removeLab(id) {
 		$.ajax({
 			type: 'DELETE',
 			url: "{{ url('attachment/remove/') }}" + "/" + id,
 			headers: {
 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 			},
 			success: function(response) {
 				if (response.status) {
 					$(".loadView").html(response.html)
 					$("#attachmentModel").modal('hide');
 				}
 			},
 			error: function(xhr, status, error) {
 				console.error(xhr.responseText);
 			}
 		});
 	}
 </script>
 @endpush