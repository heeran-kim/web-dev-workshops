@props(['listing'])

<div class="col">
    <div class="card">
        <a href={{url("listings/$listing->ListingId")}} class="text-decoration-none text-reset">
            {{-- RENT / BRIEF ADDRESS --}}
            <div class="card-body">
                <h5 class="card-title">{{$listing->Title}}</h5>
                <h6 class="card-title">{{"$".$listing->Rent."/pw"}}</h6>
                <p class="card-text">{{$listing->City.", ".$listing->State}}</p>
            </div>
            
            {{-- RATING --}}
            <div class="card-footer">
                @if ($listing->ReviewStat->AverageRating)
                    <x-rating :Rating="$listing->ReviewStat->AverageRating" />
                    <small class="text-body-secondary">
                        {{$listing->ReviewStat->AverageRating . " (" . $listing->ReviewStat->ReviewCount . ")"}}
                    </small>
                @else
                    <small>No Reviews Found</small>
                @endif
            </div>
        </a>
    </div>
</div>
