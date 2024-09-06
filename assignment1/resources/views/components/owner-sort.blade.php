@props(['action', 'sort'])

<!-- Component for displaying a sort dropdown menu -->
<form id="sortForm" method="GET" action="{{url($action)}}">
    <select id="sortSelect" class="form-select-sm" name="sort">
        <option value="date-desc" {{ $sort == "date-desc" ? "selected" : "" }}>Newest First</option>
        <option value="date-asc" {{ $sort == "date-asc" ? "selected" : "" }}>Oldest First</option>
        <option value="rating-desc" {{ $sort == "rating-desc" ? "selected" : "" }}>Highest Rated</option>
        <option value="rating-asc" {{ $sort == "rating-asc" ? "selected" : "" }}>Lowest Rated</option>
        <option value="reviews-desc" {{ $sort == "reviews-desc" ? "selected" : "" }}>Most Reviews</option>
        <option value="reviews-asc" {{ $sort == "reviews-asc" ? "selected" : "" }}>Fewest Reviews</option>
        <option value="listing-desc" {{ $sort == "listing-desc" ? "selected" : "" }}>Most Listings</option>
        <option value="listing-asc" {{ $sort == "listing-asc" ? "selected" : "" }}>Fewest Listings</option>
    </select>
</form>

<script>
// Listen for changes in the select dropdown
document.getElementById('sortSelect').addEventListener('change', function() {
    // Submit the form automatically when the sort option is changed
    document.getElementById('sortForm').submit();
});
</script>
