<x-master title="| Home">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="m-0">All Listings</h3>

            <form method="GET" action={{url("/")}}>
                <select class="form-select-sm" name="sort">
                    <option value="date-desc" selected>Newest First</option>
                    <option value="date-asc">Oldest First</option>
                    <option value="rating-desc">Highest Rated</option>
                    <option value="rating-asc">Lowest Rated</option>
                    <option value="reviews-desc">Most Reviews</option>
                    <option value="reviews-asc">Fewest Reviews</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Sort</button>
            </form>
        </div>

        @if ($listings)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($listings as $listing)
                    <x-listing-card :listing="$listing"/>
                @endforeach
            </div>
        @else
            <div class="text-center">No Listings Found</div>
        @endunless
    </div>
</x-master>