<x-master title="| Show">
    <div class="container">
        <div class="bg-light p-3 border rounded shadow-sm">
            {{-- TITLE/RATING/EDIT/DELETE --}}
            <div class="d-flex justify-content-between">
                <h3>{{$listing->Title}}</h3>
                <div>
                    @if ($listing->ReviewStat->AverageRating)
                        <x-rating :Rating="$listing->ReviewStat->AverageRating" />
                        <small class="text-body-secondary">
                            {{$listing->ReviewStat->AverageRating . " (" . $listing->ReviewStat->ReviewCount . ")"}}
                        </small>
                    @else
                        <small>No Reviews Found</small>
                    @endif
                    
                    <a href={{url("listings/$listing->ListingId/edit")}} class="text-reset"><i class="bi bi-pencil"></i></a>
                    
                    <form method="POST" action={{url("listings/$listing->ListingId")}} class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="border-0 bg-transparent p-0"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>
            </div>

            {{-- DETAILS --}}
            <div class="d-flex flex-column gap-3 mb-3 flex-md-row justify-content-between">
                <div>
                    <div class="fw-bold">Owner</div>
                    <a href="{{url('users/'.$listing->UserId)}}" class="text-reset text-decoration-none">
                        {{$listing->UserName}}
                    </a>
                </div>
                <div>
                    <div class="fw-bold">Rent</div>
                    <div>{{"$" . $listing->Rent . "/pw"}}</div>
                </div>
                <div>
                    <div class="fw-bold">Address</div>
                    <div>{{$listing->Street . ", " . $listing->City . ", " . $listing->State}}</div>
                </div>
                <div>
                    <div class="fw-bold">Available Date</div>
                    <div>{{$listing->AvailableDate}}</div>
                </div>
                <div>
                    <div class="fw-bold">Conditions</div>
                    <div>
                        @if ($listing->IsBillIncluded)
                            <i class="bi bi-clipboard-check"></i> Bill Included
                        @endif
                        @if ($listing->IsFurnished)
                            <i class="bi bi-clipboard-check"></i> Furnished
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <div class="fw-bold">Description</div>
                <div class="bg-white p-3 border rounded">{{$listing->Description}}</div>
            </div>
            
            {{-- DISPLAY REVIEWS --}}
            <hr>
            @if ($reviews)
                <div class="d-flex flex-column gap-3 mb-3">
                    @foreach ($reviews as $review)
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="fw-bold me-3">
                                <a href="{{url('users/'.$review->UserId)}}" class="text-reset text-decoration-none">
                                    {{$review->UserName}}
                                </a>
                            </div>
                            <div class="d-flex align-items-center me-3">
                                <x-rating :Rating="$review->Rating" />
                                <small class="text-body-secondary ms-2">{{ $review->Rating }}</small>
                            </div>
                            <div class="me-3">{{ $review->Date }}</div>
                            <div class="flex-grow-1">{{ $review->Review }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center p-1 mb-3">No Reviews Found</div>
            @endif
            
            {{-- REVIEW INPUT --}}
            <form method="POST" action={{url("listings/$listing->ListingId")}} class="d-flex flex-column flex-md-row">
                @csrf
                <div class="d-flex align-items-center pe-0">
                    <img
                        src="{{asset('images/no-user-img.png')}}" class="rounded-circle border me-1"
                        width="30" height="30" alt="user" style="object-fit: cover;"
                    >
                    <input class="rounded-pill border px-2" style="width: 100px; height: 30px;" type="text" name="name" placeholder="Name">
                    <select class="rounded-pill border px-2" style="width: 100px; height: 30px;">
                        <option selected>Rating</option>
                        <option value="0.0">0.0</option>
                        <option value="0.5">0.5</option>
                        <option value="1.0">1.0</option>
                        <option value="1.5">1.5</option>
                        <option value="2.0">2.0</option>
                        <option value="2.5">2.5</option>
                        <option value="3.0">3.0</option>
                        <option value="3.5">3.5</option>
                        <option value="4.0">4.0</option>
                        <option value="4.5">4.5</option>
                        <option value="5.0">5.0</option>
                    </select>
                </div>
                <div class="flex-grow-1 d-flex aligh-items-center ps-0">
                    <input
                        class="rounded-pill w-100 border px-2" style="height: 30px;"
                        type="text" name="message"
                        placeholder="Add a review for {{$listing->UserName}}'s listing..."
                    >
                    <div class="col"><button type="submit" class="border-0 bg-transparent"><i class="bi bi-send"></i></button></div>
                </div>
            </form>
        </div>
    </div>
</x-master>