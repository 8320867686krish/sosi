<div class="email-head">
    <div class="email-head-subject">
        <div class="title"><span>Attachment</span>
            @can('projects.edit')
                <div style="float:right">
                    <button class="btn btn-primary" id="AddAttachment">Add</button>
                </div>
            @endcan
        </div>
    </div>
</div>
<div class="row mt-4 loadView">
    @include('projects.attachmentAjax', compact('attachment'))
</div>

<div class="modal fade" data-backdrop="static" id="attachmentModel" tabindex="-1" role="dialog"
    aria-labelledby="attachmentModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Attachment Add</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
            </div>
            <form method="post" class="needs-validation" novalidate id="attachmentForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="project_id" id="project_id" value="{{ $project_id }}">
                    <div class="form-group">
                        <label for="name">Attachment Name</label>
                        <input type="text" id="heading" name="heading" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Attachment Type</label>
                        <select name="attachment_type" id="attachment_type" class="form-control">
                            <option value="">Selet Attachment Type</option>
                            <option value="genral">Genral</option>
                            <option value="shipPlan">Ship Plan</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Attachment File</label>
                        <input type="file" class="form-control" name="details" id="details" required>
                        <label class="documentsValue" style="display: none;"></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="attachSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
    <!-- Include your JavaScript code here -->
    <script>
        $("#AddAttachment").click(function() {
            $("#attachmentModel").modal('show');
        });

        $('#attachmentForm').submit(function(e) {
            e.preventDefault();
            var form = $(this); // Get the form element
            var formData = new FormData(form[0]); // Create FormData object from the form
            $.ajax({
                type: 'POST',
                url: "{{ url('attachment/save') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    let deckData = response.deck;
                    if (response.status) {
                        form.trigger('reset');

                        $(".loadView").html(response.html)
                        $("#attachmentModel").modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endpush
