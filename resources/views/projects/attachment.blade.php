<div class="email-head">
    <div class="email-head-subject">
        <div class="title"><span>Attachment</span>
            <div style="float:right">
                <button class="btn btn-primary" id="AddLaboratory">Add</button>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4 loadView">
   @include('projects.laboratoryAjax',compact('laboratory'))
</div>

<div class="modal fade" data-backdrop="static" id="laboratoryModal" tabindex="-1" role="dialog" aria-labelledby="laboratoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Laboratory Add</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
            </div>
            <form method="post" id="laboratoryForm">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" id="laboretryid">
                    <input type="hidden" name="project_id" id="project_id" value="{{$project_id}}">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Details</label>
                        <textarea class="form-control" name="details" id="details" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="deckEditSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<!-- Include your JavaScript code here -->
<script>
    $("#AddLaboratory").click(function() {
        $("#laboratoryModal").modal('show');
    });
    $('#laboratoryForm').submit(function(event) {
        let formData = $(this).serialize();

        let form = $(this);

        $.ajax({
            type: 'POST',
            url: "{{ url('laboratory/save') }}",
            data: formData,
            success: function(response) {
                let deckData = response.deck;
                if (response.status) {
                    form.trigger('reset');
                    $(".loadView").html(response.html)
                    $("#laboratoryModal").modal('hide');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        return false;
    });
   
  
</script>
@endpush