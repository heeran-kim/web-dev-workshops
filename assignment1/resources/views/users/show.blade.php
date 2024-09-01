<x-master title="| {{$user->UserName}}">
    <div class="container">
        <div class="d-flex align-items-center justify-content-md-between mb-3 flex-column flex-md-row">
            <h3 class="m-0">{{$user->UserName}}'s All Listings</h3>

            <select class="form-select-sm">
                <option selected>Newest</option>
                <option value="1">Highest Rating</option>
                <option value="2">Lowest Rating</option>
                <option value="3">Highest Number of Reviews</option>
                <option value="4">Lowest Number of Reviews</option>
            </select>
        </div>

        @if ($listings)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($listings as $listing)
                    <x-listing-card :listing="$listing" />
                @endforeach
            </div>
        @else
            <div class="text-center">No Listings Yet</div>
        @endunless
    </div>
</x-master>