<x-master title="| Show">
    <div class="container">
        <div class="bg-light p-3 border rounded shadow-sm">
            {{-- TITLE/RATING/EDIT/DELETE --}}
            <div class="d-flex justify-content-between">
                <h3>{{$listing->title}}</h3>
                <div>
                    @if ($listing->reviewStat->averageRating)
                        <x-rating :Rating="$listing->reviewStat->averageRating" />
                        <small class="text-body-secondary">
                            {{$listing->reviewStat->averageRating . " (" . $listing->reviewStat->reviewCount . ")"}}
                        </small>
                    @else
                        <small>No Reviews Found</small>
                    @endif
                    
                    <a href={{url("listings/$listing->listingId/edit")}} class="text-reset"><i class="bi bi-pencil"></i></a>
                    
                    <form method="POST" action={{url("listings/$listing->listingId")}} class="d-inline">
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
                    <a href="{{url('users/'.$listing->userId)}}" class="text-reset text-decoration-none">
                        {{$listing->userName}}
                    </a>
                </div>
                <div>
                    <div class="fw-bold">Rent</div>
                    <div>{{"$" . $listing->rent . "/pw"}}</div>
                </div>
                <div>
                    <div class="fw-bold">Address</div>
                    <div>{{$listing->street . ", " . $listing->city . ", " . $listing->state}}</div>
                </div>
                <div>
                    <div class="fw-bold">Available Date</div>
                    <div>{{$listing->availableDate}}</div>
                </div>
                <div>
                    <div class="fw-bold">Conditions</div>
                    <div>
                        @if ($listing->isBillIncluded)
                            <i class="bi bi-clipboard-check"></i> Bill Included
                        @endif
                        @if ($listing->isFurnished)
                            <i class="bi bi-clipboard-check"></i> Furnished
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <div class="fw-bold">Description</div>
                <div class="bg-white p-3 border rounded">{{$listing->description}}</div>
            </div>
            
            {{-- DISPLAY REVIEWS --}}
            <hr>
            @if ($reviews)
                @foreach ($reviews as $review)
                    <div class="d-flex flex-column flex-lg-row mb-3">
                        {{-- REVIEW DETAILS --}}
                        <div class="col-lg-1 fw-bold me-3">
                            <a href="{{url('users/'.$review->userId)}}" class="text-reset text-decoration-none">
                                {{$review->userName}}
                            </a>
                        </div>
                        <div class="col-lg-1 me-3">
                            @if (isset($reviewToEdit) && $review->reviewId == $reviewToEdit->reviewId)
                                <form method="POST" action={{url("listings/$listing->listingId/reviews/$reviewToEdit->reviewId")}}>
                                @csrf
                                @method('PUT')
                                <select class="rounded-pill border px-2" style="width: 100px; height: 30px;" name="rating">
                                    @for ($i=0; $i<=5.0; $i+=0.5)
                                        @if ($i == $reviewToEdit->rating)
                                            <option value={{$i}} selected>{{number_format($i,1)}}</option>
                                        @else
                                            <option value={{$i}}>{{number_format($i,1)}}</option>
                                        @endif
                                    @endfor
                                </select>
                            @else
                                <x-rating :Rating="$review->rating" />
                            @endif
                        </div>
                        <div class="col-lg-1 me-3">{{$review->date}}</div>
                        <div class="col-lg-8 me-3">
                            @if (isset($reviewToEdit) && $review->reviewId == $reviewToEdit->reviewId)
                                <input
                                    class="rounded-pill w-100 border px-2" style="height: 30px;"
                                    type="text" name="review"
                                    value="{{$reviewToEdit->review}}"
                                >
                            @else
                                {{$review->review}}
                            @endif
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-lg-1">
                            @if (isset($reviewToEdit) && $review->reviewId == $reviewToEdit->reviewId)
                                <button type="submit" class="border-0 bg-transparent"><i class="bi bi-send"></i></button>
                                </form>
                            @else
                                <a href={{url("listings/$listing->listingId/reviews/$review->reviewId/edit")}} class="text-reset"><i class="bi bi-pencil"></i></a>
                            
                                <form method="POST" action={{url("listings/$listing->listingId/reviews/$review->reviewId")}} class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent p-0"><i class="bi bi-trash3"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-1 mb-3">No Reviews Found</div>
            @endif
            
            {{-- REVIEW INPUT --}}
            <form method="POST" action={{url("listings/$listing->listingId/reviews")}} class="d-flex flex-column flex-md-row">
                @csrf
                <div class="d-flex align-items-center pe-0">
                    <img
                        src="{{asset('images/no-user-img.png')}}" class="rounded-circle border me-1"
                        width="30" height="30" alt="user" style="object-fit: cover;"
                    >
                    <input class="rounded-pill border px-2" style="width: 100px; height: 30px;" type="text" name="userName" placeholder="Name"}}">
                    <select class="rounded-pill border px-2" style="width: 100px; height: 30px;" name="rating">
                        <option disabled selected>Rating</option>
                        @for ($i=1; $i<=5; $i++)
                            <option value={{$i}}>{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-grow-1 d-flex aligh-items-center ps-0">
                    <input
                        class="rounded-pill w-100 border px-2" style="height: 30px;"
                        type="text" name="review"
                        placeholder="Add a review for {{$listing->userName}}'s listing..."
                    >
                    <div class="col"><button type="submit" class="border-0 bg-transparent"><i class="bi bi-send"></i></button></div>
                </div>
            </form>
        </div>
    </div>
</x-master>