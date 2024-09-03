@props(['listing'])

<div class="col">
    <div class="card">
        <a href={{url("listings/$listing->listingId")}} class="text-decoration-none text-reset">
            {{-- RENT / BRIEF ADDRESS --}}
            <div class="card-body">
                <h5 class="card-title">{{$listing->title}}</h5>
                <h6 class="card-title">{{"$".$listing->rent."/pw"}}</h6>
                <p class="card-text">{{$listing->city.", ".$listing->state}}</p>
            </div>
            
            {{-- RATING --}}
            <div class="card-footer">
                @if ($listing->reviewStat->averageRating)
                    <x-rating :Rating="$listing->reviewStat->averageRating" />
                    <small class="text-body-secondary">
                        {{$listing->reviewStat->averageRating . " (" . $listing->reviewStat->reviewCount . ")"}}
                    </small>
                @else
                    <small>No Reviews Found</small>
                @endif
            </div>
        </a>
    </div>
</div>
