<x-master title="| Home">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="m-0">All Listings</h3>
            <x-listing-sort
                action="/"
                :sort="$sort"
            />
        </div>

        @if (count($listings))
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($listings as $listing)
                    <x-listing-card
                        action="listings/{{$listing->listingId}}"
                        :listing="$listing"
                    />
                @endforeach
            </div>
        @else
            <div class="text-center">No Listings Found</div>
        @endif
    </div>
</x-master>