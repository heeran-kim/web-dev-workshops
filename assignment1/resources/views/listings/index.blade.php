<x-master title="| Home">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <h3>
                All Listings
            </h3>

            <select class="form-select mb-3" style="width: 250px;">
                <option selected>Newest</option>
                <option value="1">Highest Rating</option>
                <option value="2">Lowest Rating</option>
                <option value="3">Highest Number of Reviews</option>
                <option value="4">Lowest Number of Reviews</option>
            </select>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($listings as $listing)
                <div class="col">
                    <div class="card">
                        <a href="/listings/{{$listing->Id}}" class="text-decoration-none text-reset">
                            <img src="{{asset($listing->Image)}}" class="card-img-top" alt="{{$listing->Image}}" style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body">
                                <h5 class="card-title">{{"$".$listing->Rent."/pw"}}</h5>
                                <p class="card-text">{{$listing->City}}, {{$listing->State}}</p>
                            </div>
                            
                            <div class="card-footer">
                                @unless ($listing->ReviewCount == 0)
                                    <x-rating :Rating="$listing->AverageRating" />
                                    <small class="text-body-secondary">
                                        {{number_format($listing->AverageRating, 1)." (".$listing->ReviewCount.")"}}
                                    </small>
                                @else
                                    <small>No Reviews Yet</small>
                                @endunless
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-master>