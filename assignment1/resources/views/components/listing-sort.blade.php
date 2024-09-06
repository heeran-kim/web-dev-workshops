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
        <option value="rent-desc" {{ $sort == "rent-desc" ? "selected" : "" }}>Highest Rent</option>
        <option value="rent-asc" {{ $sort == "rent-asc" ? "selected" : "" }}>Lowest Rent</option>
    </select>
</form>

<script>
    const select = document.getElementById("sortSelect");
    const form = document.getElementById("sortForm");
    select.addEventListener("change", submitForm);
    function submitForm(e) {
        form.submit();
    }
</script>
