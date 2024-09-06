<x-master title="| Show">
    <div class="container">
        @if(session('alteredName'))
            <div class="alert alert-info">
                The name you entered has been changed to "{{session('alteredName')}}".
            </div>
        @endif

        @if(session('fakeReviewError'))
            <div class="alert alert-danger">
                {{session('fakeReviewError')}}
            </div>
        @endif

        <div class="bg-light p-3 border rounded shadow-sm">
            
            <div class="d-flex align-items-center justify-content-sm-between flex-column flex-sm-row">
                <h3 class="w-100 d-flex justify-content-start mb-3">{{$listing->title}}</h3>
                <div class="w-100 d-flex justify-content-end mb-3">
                    @if ($listing->averageRating)
                        <x-rating :rating="$listing->averageRating" />
                        <small class="text-body-secondary">
                            {{number_format($listing->averageRating,1) . " (" . $listing->reviewCount . ")"}}
                        </small>
                    @else
                        <small>No Reviews Found</small>
                    @endif
                    
                    <a href="{{url("listings/$listing->listingId/edit")}}" class="text-reset"><i class="bi bi-pencil"></i></a>
                    
                    <form
                        method="POST"
                        action="{{url("listings/$listing->listingId")}}"
                        class="d-inline"
                    >
                        @csrf
                        @method('DELETE')
                        @if (isset($ownerId))
                            <input type="hidden" name="ownerId" value="{{$ownerId}}">
                        @endif
                        <button type="submit" class="border-0 bg-transparent p-0"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>
            </div>

            <div class="d-flex flex-column gap-3 mb-3 flex-md-row justify-content-between">
                <div>
                    <div class="fw-bold">Owner</div>
                    <a href="{{url("owners/$listing->ownerId")}}" class="text-reset text-decoration-none">
                        {{$listing->ownerName}}
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
            
            <hr>
            @if (count($reviews))
                @foreach ($reviews as $review)
                    <div class="d-flex flex-column flex-lg-row mt-3">
                        <div class="col-lg-1 fw-bold me-3">
                            {{$review->userName}}
                        </div>
                        <div class="col-lg-1 me-3">
                            @if (isset($reviewToEdit) && $review->reviewId == $reviewToEdit->reviewId)
                                <form method="POST" action="{{url("listings/$listing->listingId/reviews/$reviewToEdit->reviewId")}}">
                                @csrf
                                @method('PUT')
                                    <select
                                        class="form-select rounded-pill"
                                        style="width: 100px; height: 35px;"
                                        name="rating"
                                    >
                                        @for ($i=1; $i<=5; $i++)
                                            <option
                                                value={{$i}}
                                                {{session('editFields.rating') == $i ? 'selected' : ($i == $reviewToEdit->rating ? 'selected' : '')}}
                                            >
                                                {{$i}}
                                            </option>
                                        @endfor
                                    </select>
                            @else
                                <x-rating :rating="$review->rating" />
                            @endif
                        </div>
                        <div class="col-lg-1 me-3">{{date('Y-m-d', strtotime($review->date))}}</div>
                        <div class="col-lg-8 me-3">
                            @if (isset($reviewToEdit) && $review->reviewId == $reviewToEdit->reviewId)
                                <input
                                    class="rounded-pill w-100 border px-2" style="height: 30px;"
                                    type="text" name="reviewText"
                                    value="{{session('editFields.reviewText', $reviewToEdit->reviewText)}}"
                                >
                            @else
                                {{$review->reviewText}}
                            @endif
                        </div>

                        <div class="col-lg-1">
                            @if (isset($reviewToEdit) && $review->reviewId == $reviewToEdit->reviewId)
                                <button type="submit" class="border-0 bg-transparent"><i class="bi bi-send"></i></button>
                                </form>
                            @else
                                <a href="{{url("listings/$listing->listingId/reviews/$review->reviewId/edit")}}" class="text-reset"><i class="bi bi-pencil"></i></a>
                            
                                <form method="POST" action="{{url("listings/$listing->listingId/reviews/$review->reviewId")}}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent p-0"><i class="bi bi-trash3"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @if (session('editError') && $review->reviewId == $reviewToEdit->reviewId)
                    <div>
                        <small class="text-danger">{{session('editError')}}</small>
                    </div>
                    @endif
                @endforeach
            @else
                <div class="text-center p-1 mb-3">No Reviews Found</div>
            @endif
            
            {{-- REVIEW INPUT --}}
            <form
                method="POST"
                action="{{url("listings/$listing->listingId/reviews")}}"
                class="d-flex align-items-center mt-3"
            >
                @csrf

                <div class="flex-grow-1 d-flex flex-column flex-sm-row">
                    <div class="d-flex align-items-center">
                        <img
                            src="{{asset('images/no-user-img.png')}}"
                            class="rounded-circle border me-1"
                            width="30"
                            height="30"
                            alt="user"
                            style="object-fit: cover;"
                        >

                        <input
                            class="form-control rounded-pill m-1" 
                            style="width: 100px; height: 35px;" 
                            type="text" 
                            name="userName" 
                            placeholder="Name"
                            value="{{session('createFields.userName', session('userName', ''))}}"
                        >

                        <select
                            class="form-select rounded-pill m-1"
                            style="width: 100px; height: 35px;"
                            name="rating"
                        >
                            <option disabled selected>Rating</option>
                            @for ($i=1; $i<=5; $i++)
                                <option value={{$i}} {{session('createFields.rating') == $i ? 'selected' : ''}}>
                                    {{$i}}
                                </option>
                            @endfor
                        </select>
                    </div>
                        
                    <div class="flex-grow-1 d-flex align-items-center">
                        <input
                            class="form-control rounded-pill w-100 m-1"
                            style="height: 35px;"
                            type="text"
                            name="reviewText"
                            placeholder="Add a review for {{$listing->ownerName}}'s listing..."
                            value="{{session('createFields.reviewText', '')}}"
                        >
                        
                        <div class="col">
                            <button type="submit" class="border-0 bg-transparent">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @if (session('createError'))
            <div>
                <small class="text-danger">{{session('createError')}}</small>
            </div>
            @endif
        </div>
    </div>
</x-master>