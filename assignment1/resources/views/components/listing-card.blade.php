@props(['listing'])

<!-- Component to display an individual listing card with title, rent, brief address(city, state), and average rating -->
<div class="col">
    <div class="card">
        <!-- Link to the specific listing's page -->
        <a
            href="{{url("listings/$listing->listingId")}}"
            class="text-decoration-none text-reset"
        >
            <div class="card-body">
                <!-- Listing Title, Rent, and Brief address -->
                <h5 class="card-title">{{$listing->title}}</h5>
                <h6 class="card-title">{{"$".$listing->rent."/pw"}}</h6>
                <p class="card-text">{{$listing->city.", ".$listing->state}}</p>
            </div>
            
            <div class="card-footer">
                @if ($listing->averageRating)
                    <!-- Show listing rating if available -->
                    <x-rating :rating="$listing->averageRating" />
                    <small class="text-body-secondary">
                        {{number_format($listing->averageRating,1) . " (" . $listing->reviewCount . ")"}}
                    </small>
                @else
                    <!-- Show listing rating if available -->
                    <small>No Reviews Found</small>
                @endif
            </div>
        </a>
    </div>
</div>
