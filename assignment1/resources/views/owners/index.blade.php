<x-master title="| Users">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="m-0">All Owners</h3>
            
            <form method="GET" action={{url("owners")}}>
                <select class="form-select-sm" name="sort">
                    <option value="date-desc" {{$sort == "date-desc" ? "selected" : ""}}>Newest First</option>
                    <option value="date-asc" {{$sort == "date-asc" ? "selected" : ""}}>Oldest First</option>
                    <option value="rating-desc" {{$sort == "rating-desc" ? "selected" : ""}}>Highest Rated</option>
                    <option value="rating-asc" {{$sort == "rating-asc" ? "selected" : ""}}>Lowest Rated</option>
                    <option value="reviews-desc" {{$sort == "reviews-desc" ? "selected" : ""}}>Most Reviews</option>
                    <option value="reviews-asc" {{$sort == "reviews-asc" ? "selected" : ""}}>Fewest Reviews</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Sort</button>
            </form>
        </div>

        @if ($owners)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($owners as $owner)
                    <div class="col">
                        <div class="card">
                            <a href={{url("owners/$owner->ownerId")}} class="text-decoration-none text-reset">
                                {{-- RENT / BRIEF ADDRESS --}}
                                <div class="card-body">
                                    <h5 class="card-title">{{$owner->ownerName}}</h5>
                                    <p class="card-text">{{"Has ".$owner->listingCount." listing(s)"}}</p>
                                </div>
                                
                                {{-- RATING --}}
                                <div class="card-footer">
                                    @if ($owner->reviewCount)
                                        <x-rating :Rating="$owner->averageRating" />
                                        <small class="text-body-secondary">
                                            {{$owner->averageRating . " (" . $owner->reviewCount . ")"}}
                                        </small>
                                    @else
                                        <small>No Reviews Found</small>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>                
                @endforeach
            </div>
        @else
            <div class="text-center">No Owners Found</div>
        @endunless
    </div>
</x-master>