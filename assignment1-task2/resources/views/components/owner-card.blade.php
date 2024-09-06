@props(['owner'])

<!-- Component to display an individual owner's card with name, listing count, and average rating -->
<div class="col">
    <div class="card">
        <!-- Link to the specific owner's page -->
        <a
            href="{{url("owners/$owner->ownerId")}}"
            class="text-decoration-none text-reset"
        >
            <div class="card-body">
                <!-- Owner Name and Listing Count -->
                <h5 class="card-title">{{$owner->ownerName}}</h5>
                <p class="card-text">{{"Has ".$owner->listingCount." listing(s)"}}</p>
            </div>
            
            
            <div class="card-footer">
                @if ($owner->reviewCount)
                    <!-- Show owner rating if available -->
                    <x-rating :rating="$owner->averageRating" />
                    <small class="text-body-secondary">
                        {{number_format($owner->averageRating,1) . " (" . $owner->reviewCount . ")"}}
                    </small>
                @else
                    <!-- Show message if no reviews found -->
                    <small>No Reviews Found</small>
                @endif
            </div>
        </a>
    </div>
</div>   