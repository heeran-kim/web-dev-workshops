<x-master title="| Users">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="m-0">All Owners</h3>
            
            <form method="GET" action={{url("users")}}>
                <select class="form-select-sm" name="sort">
                    <option value="date-desc" selected>Newest First</option>
                    <option value="date-asc">Oldest First</option>
                    <option value="rating-desc">Highest Rated</option>
                    <option value="rating-asc">Lowest Rated</option>
                    <option value="reviews-desc">Most Reviews</option>
                    <option value="reviews-asc">Fewest Reviews</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Sort</button>
            </form>
        </div>

        @if ($users)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($users as $user)
                    <div class="col">
                        <div class="card">
                            <a href={{url("users/$user->userId")}} class="text-decoration-none text-reset">
                                {{-- RENT / BRIEF ADDRESS --}}
                                <div class="card-body">
                                    <h5 class="card-title">{{$user->userName}}</h5>
                                    <p class="card-text">{{"Has ".$user->listingCount." listing(s)"}}</p>
                                </div>
                                
                                {{-- RATING --}}
                                <div class="card-footer">
                                    @if ($user->reviewStat)
                                        <x-rating :Rating="$user->reviewStat->averageRating" />
                                        <small class="text-body-secondary">
                                            {{$user->reviewStat->averageRating . " (" . $user->reviewStat->reviewCount . ")"}}
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