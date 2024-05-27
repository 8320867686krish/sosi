<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered first">
                    <thead>
                        <tr>
                            <th>SR No</th>
                            <th>Attachment Name</th>
                            <th>Attachment File</th>
                            <th>Attachment Type</th>
                            @can('projects.edit')
                            <th>Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attachment as $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value['heading'] }}</td>
                            <td>
                                <a href="{{ asset('images/attachment/' . $value['project_id'] . '/' . $value['documents']) }}" target="_blank">
                                    {{ $value['documents'] }}
                                </a>
                            </td>
                            <td>{{ $value['attachment_type'] }}</td>
                            @can('projects.edit')
                            <td>
                                <a href="#" rel="noopener noreferrer" data-attachment="{{ json_encode($value) }}" class="editAttachment" title="Edit">
                                    <i class="fas fa-edit text-primary" style="font-size: 1rem"></i>
                                </a>
                                <a href="#" class="ml-2" onclick="removeLab('{{ $value['id'] }}')" title="Delete">
                                    <i class="fas fa-trash-alt text-danger" style="font-size: 1rem"></i>
                                </a>
                            </td>
                            @endcan
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
        $("#attachment_type").val(attachment.attachment_type);
        $("#attachmentModel").modal('show');

        console.log(attachment.id);
    });

    function removeLab(id) {
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
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
                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                } else {
                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
    }
</script>
@endpush
