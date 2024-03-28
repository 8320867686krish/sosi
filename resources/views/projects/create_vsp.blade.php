
@foreach ($decks as $deck)
<div class="col-lg-4 col-md-6 col-sm-12 col-12">
    <div class="card">
        <img class="img-fluid px-3" src="{{ $deck->imagePath }}" alt="Card image cap">
        <div class="card-body">
            <h3 class="card-title">{{ ucwords($deck->name) }}</h3>
        </div>
        <div class="card-footer">
            <button href="#" class="btn btn-primary deckImgEditBtn">Edit</button>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" id="pdfModal" tabindex="-1" role="dialog"
        aria-labelledby="pdfModalLabel" aria-hidden="true" style="padding-right: 0px !important;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
            role="document" style="width: 98% !important; max-width: none !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Deck Title</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body" style="overflow-x: auto; overflow-y: auto; height: calc(81vh - 1rem);">
                    <div id="img-container"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-secondary" data-dismiss="modal">Close</a>
                    <button class="btn btn-primary" id="get">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
