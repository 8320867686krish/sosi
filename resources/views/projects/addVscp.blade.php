<div class="email-head">
    <div class="email-head-subject">
        <div class="title"><span>VSCP</span>
            @can('projects.edit')
            <div style="float:right">
                <button class="btn btn-primary" onclick="triggerFileInput('pdfFile')">Add</button>&nbsp;&nbsp;
                <button class="btn btn-primary reindex" data-id="{{$project_id}}" type="button">Reindex</button>
                <input type="file" id="pdfFile" name="pdfFile" accept=".pdf" style="display: none;">
            </div>


            @endcan
        </div>
    </div>
</div>

<div class="row mt-4 deckView">
    @if (isset($project->decks) && $project->decks->count() > 0)
    @foreach ($project->decks as $deck)
    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center" id="deckTitle_{{ $deck->id }}">{{ $deck->name }}</h5>
                <div class="deck-img">
                    @can('projects.edit')
                    <a href="{{ route('deck.detail', ['id' => $deck->id]) }}"><img class="img-fluid px-3" src="{{ $deck->image }}" alt="{{ $deck->name }}"></a>
                    @else
                    <img class="img-fluid px-3" src="{{ $deck->image }}" alt="{{ $deck->name }}">
                    @endcan
                </div>
            </div>
            @can('projects.edit')
            <div class="card-footer">
                <div class="float-right">
                    <a href="{{ route('generatorQRcode', ['deckId' => $deck->id]) }}" data-id="{{ $deck->id }}">
                        <i class="fas fa-qrcode" style="font-size: 1rem;"></i>
                    </a>
                    <a href="javascript:;" class="ml-2 deckImgEditBtn" data-id="{{ $deck->id }}" rel="noopener noreferrer" title="Edit">
                        <i class="fas fa-edit text-primary" style="font-size: 1rem;"></i>
                    </a>
                    <a href="javascript:;" class="ml-2 deckImgDeleteBtn" data-id="{{ $deck->id }}">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
                    </a>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @endforeach
    @endif
</div>

<div class="modal fade" data-backdrop="static" id="deckEditFormModal" tabindex="-1" role="dialog" aria-labelledby="deckEditFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Title</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
            </div>
            <form method="post" id="deckEditForm">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" id="deckEditFormId">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="deckEditSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" data-backdrop="static" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true" style="padding-right: 0px !important;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document" style="width: 98% !important; max-width: none !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Deck Title</h5>
                <a href="#" class="close pdfModalCloseBtn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body" style="overflow-x: auto; overflow-y: auto; height: calc(81vh - 1rem);">
                <div class="d-flex justify-content-center align-items-center spinnerDiv">
                    {{-- style="height: 100%;" --}}
                    <span class="dashboard-spinner spinner-md text-center"></span>
                </div>
                <div id="img-container" class="text-center"></div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-secondary pdfModalCloseBtn" data-dismiss="modal">Close</a>
                <button class="btn btn-primary" id="getDeckCropImg" data-id="{{ $project->id }}">Save</button>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    $(".reindex").click(function() {
        var projectId = $(this).attr('data-id');
        let $submitButton = $(this);
        console.log($submitButton);
        let originalText = $submitButton.html();
        $submitButton.text('Wait...');
        $submitButton.prop('disabled', true);

        $.ajax({
            type: 'get',
            url: "{{ url('reintialCheckIndex')}}" + "/" + projectId,

            success: function(response) {
                $submitButton.html(originalText);
                $submitButton.prop('disabled', false);


                swal({
                    title: "Success",
                    text: response.success,
                    timer: 1000
                });

            },
            error: function(xhr, status, error) {
                $submitButton.html(originalText);
                $submitButton.prop('disabled', false);

            }
        });
    });
</script>
@endpush