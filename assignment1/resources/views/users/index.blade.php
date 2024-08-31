<x-master title="| Owners">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <h3>
                All Users
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
            @foreach ($owners as $owner)
            <div class="col">
                <a href="owners/{{$owner->Id}}" class="text-reset text-decoration-none">
                    <div class="card">
                        {{-- <img src="{{asset($listing->Image)}}" class="card-img-top" alt="{{$listing->Image}}" style="height: 200px; object-fit: cover;"> --}}
                        <div class="card-body">
                            <h5 class="card-title">{{$owner->Name}}</h5>
                            {{-- <p class="card-text">{{$listing->City}}, {{$listing->State}}</p> --}}
                        </div>
                        
                        <div class="card-footer">
                            <small class="text-body-secondary">
                                @for ($i=0; $i<5; $i++)
                                    @if ($i<floor($owner->AverageRating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($i<$owner->AverageRating)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                                {{number_format($owner->AverageRating, 1)." (".$owner->ReviewCount.")"}}
                            </small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</x-master>