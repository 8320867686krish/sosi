@foreach ($decks as $deck)
    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center" id="deckTitle_{{ $deck->id }}">{{ $deck->name }}</h3>

                <div class="deck-img">
                    <a href="{{route('deck.detail', ['id' => $deck->id])}}"><img class="img-fluid px-3" src="{{ asset("images/pdf/{$deck->project_id}/{$deck->image}") }}" alt="Card image cap"></a>
                </div>

            </div>
            <div class="card-footer">
                <div class="float-right">
                    <a class="deckImgEditBtn" data-id="{{ $deck->id }}" rel="noopener noreferrer" title="Edit">
                        <i class="fas fa-edit text-primary" style="font-size: 1rem;"></i>
                    </a>
                    <a class="ml-2 deckImgDeleteBtn" data-id="{{ $deck->id }}">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 1rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach
