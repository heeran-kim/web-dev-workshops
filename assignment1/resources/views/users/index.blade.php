<x-master title="| Users">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="m-0">All Users</h3>
            
            <select class="form-select-sm">
                <option selected>Newest</option>
                <option value="1">Highest Rating</option>
                <option value="2">Lowest Rating</option>
                <option value="3">Highest Number of Reviews</option>
                <option value="4">Lowest Number of Reviews</option>
            </select>
        </div>

        @if ($users)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($users as $user)
                    <div class="col">
                        <div class="card">
                            <a href={{url("users/$user->UserId")}} class="text-decoration-none text-reset">
                                {{-- RENT / BRIEF ADDRESS --}}
                                <div class="card-body">
                                    <h5 class="card-title">{{$user->UserName}}</h5>
                                    <p class="card-text">{{"Has ".$user->ListingCount." listing(s)"}}</p>
                                </div>
                                
                                {{-- RATING --}}
                                <div class="card-footer">
                                    @if ($user->ReviewStat)
                                        <x-rating :Rating="$user->ReviewStat->AverageRating" />
                                        <small class="text-body-secondary">
                                            {{$user->ReviewStat->AverageRating . " (" . $user->ReviewStat->ReviewCount . ")"}}
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
            <div class="text-center">No Users Found</div>
        @endunless
    </div>
</x-master>