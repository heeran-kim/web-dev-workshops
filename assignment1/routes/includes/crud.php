<?php

use Illuminate\Support\Facades\DB;

// 2. CRUD
// 2.1. Private functions handle database interactions for ratings and reviews.
/*
 * Recalculates the average rating of a listing after reviews are updated or deleted.
 * @usage   Used in `updateReview` and `deleteReview` to keep the average rating up-to-date.
 * @return  float Returns the calculated average rating for the listing.
 */
function _getAverageRating ($listingId) {
    $sql = "
        SELECT
            AVG(R.rating)   AS averageRating
        FROM
            Reviews         AS R,
            Listings        AS L
        WHERE
            R.listing_id = L.id AND
            L.id = ?
    ";

    $averageRatings = DB::select($sql, array($listingId));

    // If we don't get exactly one result, it means there's an issue with the query.
    if (count($averageRatings) !== 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    // Return the calculated average rating.
    $averageRating = $averageRatings[0]->averageRating;
    return $averageRating;
}

/*
 * Updates the average rating of a listing in the database.
 * @usage   Used after recalculating the average rating with `_getAverageRating` to persist the new rating in the Listings table.
 * @return  void
 */
function _updateAverageRating ($listingId, $averageRating) {
    $sql = "
        UPDATE Listings
        SET
            average_rating = ?
        WHERE
            id = ?
    ";

    DB::update($sql, array($averageRating, $listingId));
}

/*
 * Decreases the review count of a listing by 1.
 * @usage   Used when a review is deleted to update the review count of the listing.
 * @return  void
 */
function _decrementReviewCount ($listingId) {
    $sql = "
        UPDATE Listings
        SET
            review_count = review_count - 1
        WHERE
            id = ?
    ";

    DB::update($sql, array($listingId));
}

// 2.2. Public functions that handle CRUD operations.
// 2.2.1. Public functions that handle listings.
/*
 * Fetches all listings, optionally sorted by a specified criterion.
 * @usage   Used to display all listings on the main listings page, allowing sorting by date, rating, or review count.
 * @return  array Returns an array of listings.
 */
function getListings($sort) {
    $sql = "
        SELECT
            L.id                AS listingId,
            L.title             AS title,
            L.rent              AS rent,
            L.city              AS city,
            L.state             AS state,
            L.average_rating    AS averageRating,
            L.review_count      AS reviewCount
        FROM
            Listings            AS L
        GROUP BY L.id
    ";

    switch ($sort){
        case 'date-desc':
            $orderSql = "ORDER BY L.id DESC";
            break;
        case 'date-asc':
            $orderSql = "ORDER BY L.id";
            break;
        case 'rating-desc':
            $orderSql = "ORDER BY L.average_rating DESC";
            break;
        case 'rating-asc':
            $orderSql = "ORDER BY L.average_rating";
            break;
        case 'reviews-desc':
            $orderSql = "ORDER BY L.review_count DESC";
            break;
        case 'reviews-asc':
            $orderSql = "ORDER BY L.review_count";
            break;
    }

    $sql = $sql . $orderSql;

    $listings = DB::select($sql);

    return $listings;
}

/*
 * Fetches a specific listing by its ID.
 * @usage   Used to display a specific listing or load the listing edit form.
 * @return  object Returns the listing object with detailed information.
 */
function getListing($listingId) {
    $sql = "
        SELECT
            L.id                AS listingId,
            L.title             AS title,
            O.id                AS ownerId,
            O.name              AS ownerName,
            L.rent              AS rent,
            L.street            AS street,
            L.city              AS city,
            L.state             AS state,
            L.available_date    AS availableDate,
            L.description       AS description,
            L.is_furnished      AS isFurnished,
            L.is_bill_included  AS isBillIncluded,
            L.average_rating    AS averageRating,
            L.review_count      AS reviewCount
        FROM
            Owners              AS O,
            Listings            AS L
        WHERE
            L.owner_name = O.name AND
            L.id = ?
    ";

    $listings = DB::select($sql, array($listingId));

    if (count($listings) !== 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $listing = $listings[0];

    return $listing;
}

/*
 * Creates a new listing in the database.
 * @usage   Used when a new listing is submitted via the listing creation form.
 * @return  int Returns the ID of the newly created listing.
 */
function createListing($formFields){
    $sql = "
        INSERT INTO Listings (title, street, city, state, rent, available_date, is_furnished, is_bill_included, description, owner_name)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    DB::insert($sql, array( $formFields['title'], $formFields['street'], $formFields['city'], $formFields['state'],
                            $formFields['rent'], $formFields['availableDate'],
                            $formFields['isFurnished'], $formFields['isBillIncluded'],
                            $formFields['description'], $formFields['ownerName']));

    $listingId = DB::getPdo()->lastInsertId();

    if (!$listingId) {
        die("Error while adding listing");
    }

    return $listingId;
}

/*
 * Updates an existing listing in the database.
 * @usage   Used when editing a listing via the listing edit form.
 * @return  void
 */
function updateListing($listingId, $formFields) {
    $sql = "
        UPDATE Listings
        SET
            title = ?, street = ?, city = ?, state = ?,
            rent = ?, available_date = ?, is_furnished = ?, is_bill_included = ?,
            description = ?
        WHERE
            id = ?
    ";
    
    DB::update($sql, array( $formFields['title'], $formFields['street'], $formFields['city'], $formFields['state'],
                            $formFields['rent'], $formFields['availableDate'], $formFields['isFurnished'], $formFields['isBillIncluded'],
                            $formFields['description'], $listingId));
}

/*
 * Deletes a specific listing and its associated reviews.
 * @usage   Used when deleting a listing and cleaning up associated data.
 * @return  void
 */
function deleteListing($listingId) {
    $sql = "DELETE FROM Reviews WHERE listing_id = ?";
    DB::delete($sql, array($listingId));

    $sql = "DELETE FROM Listings WHERE id = ?";
    DB::delete($sql, array($listingId));
}

// 2.2.2. Public functions that handle reviews.
/*
 * Fetches all reviews for a specific listing.
 * @usage   Used to display all reviews for a listing.
 * @return  array Returns an array of reviews.
 */
function getListingReviews($listingId) {
    $sql = "
        SELECT
            R.id        AS reviewId,
            R.user_name AS userName,
            R.rating    AS rating,
            R.date      AS date,
            R.review    AS review
        FROM
            Reviews     AS R,
            Listings    AS L
        WHERE
            R.listing_id = L.id AND
            L.id = ?
        ORDER BY
            R.id
    ";

    $listingReviews = DB::select($sql, array($listingId));

    return $listingReviews;
}

/*
 * Fetches a specific review by its ID.
 * @usage   Used to load the review edit form.
 * @return  object Returns the review object.
 */
function getReview($reviewId) {
    $sql = "
        SELECT
            R.id        AS reviewId,
            R.user_name AS userName,
            R.rating    AS rating,
            R.review    AS review
        FROM
            Reviews     AS R,
            Listings    AS L
        WHERE
            R.listing_id = L.id AND
            R.id = ?
    ";

    $reviews = DB::select($sql, array($reviewId));

    if (count($reviews) !== 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $review = $reviews[0];

    return $review;
}

/*
 * Creates a new review for a listing in the database.
 * @usage   Used when a new review is submitted via the review creation form.
 * @return  void
 */
function createReview($listingId, $formFields) {
    // Insert the new review into the Reviews table
    $sql = "
        INSERT INTO Reviews (user_name, rating, date, review, listing_id)
        VALUES (?, ?, ?, ?, ?)
    ";

    DB::insert($sql, array(
        $formFields['userName'],
        $formFields['rating'],
        now(),
        $formFields['review'],
        $listingId)
    );

    // Get the last inserted review ID
    $reviewId = DB::getPdo()->lastInsertId();

    if (!$reviewId) {
        die("Error while adding review");
    }

    // Update the Listings table to adjust the average rating and review count
    $sql = "
        UPDATE Listings
        SET
            average_rating  = (IFNULL(average_rating, 0) * review_count + ?)/(review_count + 1.0),
            review_count    = review_count + 1
        WHERE
            id = ?
    ";

    DB::update($sql, array(
        $formFields['rating'],
        $listingId)
    );
}

/*
 * Updates an existing review in the database.
 * @usage   Used when editing a review via the review edit form.
 * @return  void
 */
function updateReview($listingId, $reviewId, $formFields) {
    $sql = "
        UPDATE Reviews
        SET
            rating = ?, date = ?, review = ?
        WHERE
            id = ?
    ";
    
    DB::update($sql, array($formFields['rating'], now()->format('Y-m-d'), $formFields['review'], $reviewId));

    // After updating a review, recalculate the average rating
    $averageRating = _getAverageRating($listingId);
    _updateAverageRating($listingId, $averageRating);
}

/*
 * Deletes a specific review from the database.
 * @usage   Used when deleting a review.
 * @return  void
 */
function deleteReview($listingId, $reviewId) {
    $sql = "DELETE FROM Reviews WHERE id = ?";
    DB::delete($sql, array($reviewId));

    $averageRating = _getAverageRating($listingId);
    _updateAverageRating($listingId, $averageRating);
    _decrementReviewCount($listingId);
}

// 2.2.3. Public functions that handle owners.
/*
 * Fetches all owners, optionally sorted by a specified criterion.
 * @usage   Used to display all owners on the owners page, allowing sorting by listing count or rating.
 * @return  array Returns an array of owners.
 */
function getOwners($sort) {
    $sql = "
        SELECT
            O.id                    AS ownerId,
            O.name                  AS ownerName,
            COUNT(L.id)             AS listingCount,
            AVG(L.average_rating)   AS averageRating,
            SUM(L.review_count)     AS reviewCount
        FROM
            Owners                  AS O,
            Listings                AS L
        WHERE
            L.owner_name = O.name
        GROUP BY O.id
    ";

    switch ($sort){
        case 'date-desc':
            $orderSql = "ORDER BY O.id DESC";
            break;
        case 'date-asc':
            $orderSql = "ORDER BY O.id";
            break;
        case 'rating-desc':
            $orderSql = "ORDER BY averageRating DESC";
            break;
        case 'rating-asc':
            $orderSql = "ORDER BY averageRating";
            break;
        case 'reviews-desc':
            $orderSql = "ORDER BY reviewCount DESC";
            break;
        case 'reviews-asc':
            $orderSql = "ORDER BY reviewCount";
            break;
    }

    $sql = $sql . $orderSql;

    $owners = DB::select($sql);

    return $owners;
}

/*
 * Fetches all listings for a specific owner.
 * @usage   Used to display all listings owned by a specific owner, optionally sorted by a specified criterion.
 * @return  array Returns an array of listings for the owner.
 */
function getOwnerListings($ownerId, $sort) {
    $sql = "
        SELECT
            L.id                AS listingId,
            L.title             AS title,
            L.rent              AS rent,
            L.city              AS city,
            L.state             AS state,
            L.average_rating    AS averageRating,
            L.review_count      AS reviewCount'
        FROM
            Listings            AS L,
            Owners              AS O
        WHERE
            L.owner_name = O.name AND
            O.id = ?
        GROUP BY L.id
    ";

    switch ($sort){
        case 'date-desc':
            $orderSql = "ORDER BY L.id DESC";
            break;
        case 'date-asc':
            $orderSql = "ORDER BY L.id";
            break;
        case 'rating-desc':
            $orderSql = "ORDER BY L.average_rating DESC";
            break;
        case 'rating-asc':
            $orderSql = "ORDER BY L.average_rating";
            break;
        case 'reviews-desc':
            $orderSql = "ORDER BY L.review_count DESC";
            break;
        case 'reviews-asc':
            $orderSql = "ORDER BY L.review_count";
            break;
    }

    $sql = $sql . $orderSql;
    
    $listings = DB::select($sql, array($ownerId));

    return $listings;
}

/*
 * Fetches a specific owner's information by their ID.
 * @usage   Used to display the owner's information on the website.
 * @return  object Returns the owner object containing the owner's name.
 */
function getOwner($ownerId) {
    $sql = "
        SELECT
            O.id    AS ownerId,
            O.name  AS ownerName
        FROM
            Owners  AS O
        WHERE
            O.id = ?
    ";

    $owners = DB::select($sql, array($ownerId));

    if (count($owners) !== 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }
    
    $owner = $owners[0];
    
    return $owner;
}

/*
 * Creates a new owner in the database.
 * @usage   Used when creating a new owner, typically when a new listing is created.
 * @return  void
 */
function createOwner($ownerName) {
    $sql = "
        INSERT OR IGNORE INTO Owners (name)
        VALUES (?)
    ";

    DB::update($sql, array($ownerName));
}
?>