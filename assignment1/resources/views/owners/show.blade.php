<x-master title="| {{$owner->ownerName}}">
    <div class="container">
        <div class="d-flex align-items-center justify-content-md-between mb-3 flex-column flex-md-row">
            <h3 class="m-0">{{$owner->ownerName}}'s All Listings</h3>

            <form method="GET" action={{url("owners/$owner->ownerId")}}>
                <select class="form-select-sm" name="sort">
                    <option value="date-desc" {{$sort == "date-desc" ? "selected" : ""}}>Newest First</option>
                    <option value="date-asc" {{$sort == "date-asc" ? "selected" : ""}}>Oldest First</option>
                    <option value="rating-desc" {{$sort == "rating-desc" ? "selected" : ""}}>Highest Rated</option>
                    <option value="rating-asc" {{$sort == "rating-asc" ? "selected" : ""}}>Lowest Rated</option>
                    <option value="reviews-desc" {{$sort == "reviews-desc" ? "selected" : ""}}>Most Reviews</option>
                    <option value="reviews-asc" {{$sort == "reviews-asc" ? "selected" : ""}}>Fewest Reviews</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Sort</button>
            </form>
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