@props(['action', 'sort'])

<!-- Component for displaying a sort dropdown menu -->
<form method="GET" action="{{url($action)}}">
    <select class="form-select-sm" name="sort">
        <option value="date-desc" {{ $sort == "date-desc" ? "selected" : "" }}>Newest First</option>
        <option value="date-asc" {{ $sort == "date-asc" ? "selected" : "" }}>Oldest First</option>
        <option value="rating-desc" {{ $sort == "rating-desc" ? "selected" : "" }}>Highest Rated</option>
        <option value="rating-asc" {{ $sort == "rating-asc" ? "selected" : "" }}>Lowest Rated</option>
        <option value="reviews-desc" {{ $sort == "reviews-desc" ? "selected" : "" }}>Most Reviews</option>
        <option value="reviews-asc" {{ $sort == "reviews-asc" ? "selected" : "" }}>Fewest Reviews</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Sort</button>
</form>