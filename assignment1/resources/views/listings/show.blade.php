<x-master title="| Show">
    <div class="container">
        <div class="bg-light p-3 border rounded shadow-sm">
            {{-- TITLE/RATING/EDIT --}}
            <div class="d-flex justify-content-between">
                <h3>{{$listing[0]->Title}}</h3>
                <div>
                    <x-rating :Rating="$listing[0]->AverageRating" />
                    <small class="text-body-secondary">
                        {{$listing[0]->AverageRating . " (".$listing[0]->ReviewCount.")"}}
                    </small>
                    <a href="/listings/{{$listing[0]->Id}}/edit" class="text-reset"><i class="bi bi-pencil"></i></a>
                </div>
            </div>
            
            {{-- IMAGES --}}
            <div id="carouselExampleIndicators" class="carousel slide" style="height: 50vh;">
                <div class="carousel-inner">
                    @foreach ($images as $image)
                        <div class="carousel-item {{$loop->first ? "active":""}}">
                            <div class="d-flex justify-content-center">
                                <img src="{{asset($image->Path)}}" class="d-block" style="height: 50vh; object-fit: cover;" alt="{{$image->Path}}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev bg-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next bg-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            {{-- DETAILS --}}
            <hr>
            <table>
                <tr>
                    <td class="fw-bold py-2">Owners</td>
                    <td><a href="/owners/{{$listing[0]->OwnerId}}" class="text-reset text-decoration-none">{{$listing[0]->Owner}}</a></td>
                </tr>
                
                <tr>
                    <td class="fw-bold py-2">Rent</td>
                    <td>{{"$" . $listing[0]->Rent . "/pw"}}</td>
                </tr>

                <tr>
                    <td class="fw-bold py-2">Address</td>
                    <td>{{$listing[0]->Street . ", " . $listing[0]->City . ", " . $listing[0]->State}}</td>
                </tr>

                <tr>
                    <td class="fw-bold py-2">Available Date</td>
                    <td>{{$listing[0]->AvailableDate}}</td>
                </tr>

                <tr>
                    <td class="fw-bold py-2">Conditions</td>
                    <td>
                        @if ($listing[0]->IsBillIncluded)
                            <i class="bi bi-clipboard-check"></i> Bill Included
                        @endif
                        @if ($listing[0]->IsFurnished)
                            <i class="bi bi-clipboard-check"></i> Furnished
                        @endif
                    </td>
                </tr>

                <tr>
                    <td colspan=2>
                    <div class="fw-bold py-2">Description</div>
                    <div class="bg-white p-3 border rounded">{{$listing[0]->Description}}</div>
                    </td>
                </tr>
            </table>
            
            {{-- DISPLAY REVIEWS --}}
            <hr>
            @unless (count($reviews) == 0)
                <table class="w-100">
                    @foreach ($reviews as $review)
                        <tr class="d-flex flex-column flex-lg-row mb-3">
                            <td class="fw-bold p-1">
                                <a href="/owners/{{$review->UserId}}" class="text-reset text-decoration-none">
                                    {{$review->Reviewer}}
                                </a>
                            </td>
                            <td class="p-1"><x-rating :Rating="$review->Rating" /></td>
                            <td class="p-1">{{$review->Date}}</td>
                            <td class="p-1">{{$review->Review}}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="text-center">No Reviews Found</div>
            @endunless
            
            {{-- REVIEW INPUT --}}
            <form method="POST" action="/listings/{{$listing[0]->Id}}" class="d-flex flex-column flex-md-row">
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
                        placeholder="Add a review for {{$listing[0]->Owner}}'s listing..."
                    >
                    <div class="col"><button type="submit" class="border-0 bg-light"><i class="bi bi-send"></i></button></div>
                </div>
            </form>
        </div>
    </div>
</x-master>