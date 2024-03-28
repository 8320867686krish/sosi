@foreach ($decks as $deck)
    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center" id="deckTitle_{{ $deck->id }}">{{ $deck->name }}</h3>

                <div class="deck-img">
                    <img class="img-fluid px-3" src="{{ $deck->imagePath }}" alt="Card image cap">
                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary deckImgEditBtn" data-id="{{ $deck->id }}">Edit</button>
                <button class="btn btn-danger float-right deckImgDeleteBtn"
                    data-id="{{ $deck->id }}">Delete</button>
            </div>
        </div>
    </div>
@endforeach
