<x-master title="| {{$owner->ownerName}}">
    <div class="container">
        <div class="d-flex align-items-center justify-content-md-between flex-column flex-md-row">
            <h3 class="m-0 w-100 d-flex justify-content-start mb-3">{{$owner->ownerName}}'s All Listings</h3>
            <div class="w-100 d-flex justify-content-end mb-3">
                <x-sort-selector
                    action="owners/{{$owner->ownerId}}"
                    :sort="$sort"
                />
            </div>
        </div>

        @if (count($listings))
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($listings as $listing)
                    <x-listing-card :listing="$listing" />
                @endforeach
            </div>
        @else
            <div class="text-center">No Listings Found</div>
        @endif
    </div>
</x-master>