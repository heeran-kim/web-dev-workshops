<?php
use Illuminate\Support\Facades\DB;

/**
 * This file handles all CRUD (Create, Read, Update, Delete) operations for Listings, Reviews, and Owners in the EasyStay app.
 * - Listings: Full CRUD operations, including sorting by various criteria such as date, rating, review count and rent.
 * - Reviews: Full CRUD operations, with automatic recalculation of average ratings and review counts when reviews are added, edited, or deleted.
 * - Owners: Create and Read operations only, including sorting by listing count, average rating, review count and listing count.
 * 
 * The main focus of this file is the interaction with the database, handling data retrieval, insertion, updates, and deletion.
 * All public functions allow the app to dynamically manage and display Listings, Reviews, and Owners based on user input.
 */

// Private functions for managing ratings and reviews.
#region

/**
 * Updates the average rating of a listing after reviews are modified.
 * 
 * @usage   Used to recalculate and update the average rating of a listing after reviews are added, updated, or deleted.
 * @param   int     $listingId      The ID of the listing to recalculate and update.
 * @return  void
 */
function _updateAverageRating ($listingId) {
    // Recalculate the average rating based on the reviews for the listing.
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

    // Get the recalculated average rating.
    $averageRating = $averageRatings[0]->averageRating;

    // Update the Listings table with the new average rating.
    $sql = "
        UPDATE Listings
        SET
            average_rating = ?
        WHERE
            id = ?
    ";

    DB::update($sql, array($averageRating, $listingId));
}

/**
 * Updates the review count of a listing.
 * @usage   Used when a review is created or deleted to update the review count of the listing.
 * @param   int     $listingId      The ID of the listing to update.
 * @param   int     $number         The number to adjust the count (+1 for adding a review, -1 for deleting a review).
 */
function _updateReviewCount ($listingId, $number) {
    $sql = "
        UPDATE Listings
        SET
            review_count = review_count + ?
        WHERE
            id = ?
    ";

    DB::update($sql, array($number, $listingId));
}
#endregion

// Public functions that handle CRUD operations.
// Listing-related functions
#region
/**
 * Retrieves all listings from the database, optionally sorted by a given criterion.
 * 
 * @usage   Used to display all listings on the main listings page, allowing sorting by date, rating, review count or rent.
 * @param   string  $sort   Sorting criteria (e.g., by date, rating, review count or rent).
 * @return  array   Returns an array of listings.
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

    // Add sorting based on the selected criterion.
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
        case 'rent-desc':
            $orderSql = "ORDER BY L.rent DESC";
            break;
        case 'rent-asc':
            $orderSql = "ORDER BY L.rent";
            break;
    }

    $sql = $sql . $orderSql;

    $listings = DB::select($sql);

    return $listings;
}

/**
 * Retrieves a specific listing by its ID.
 * 
 * @usage   Used to display a specific listing or load the listing edit form.
 * @param   int     $listingId  The ID of the listing to retrieve.
 * @return  object  Returns the listing object with detailed information.
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

/**
 * Creates a new listing in the database.
 * 
 * @usage   Used when a new listing is submitted via the listing creation form.
 * @param   array   $formFields     The data submitted from the form.
 * @return  int     Returns the ID of the newly created listing.
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

/**
 * Updates an existing listing in the database.
 * 
 * @usage   Used when editing a listing via the listing edit form.
 * @param   int     $listingId      The ID of the listing to update.
 * @param   array   $formFields     The updated data from the form.
 */
function updateListing($listingId, $formFields) {
    $sql = "
        UPDATE Listings
        SET
            title = ?, street = ?, city = ?, state = ?,
            rent = ?, available_date = ?, is_furnished = ?, is_bill_included = ?,
            description = ?, owner_name = ?
        WHERE
            id = ?
    ";
    
    DB::update($sql, array( $formFields['title'], $formFields['street'], $formFields['city'], $formFields['state'],
                            $formFields['rent'], $formFields['availableDate'], $formFields['isFurnished'], $formFields['isBillIncluded'],
                            $formFields['description'], $formFields['ownerName'], $listingId));
}

/**
 * Deletes a specific listing and its associated reviews.
 * 
 * @usage   Used when deleting a listing and cleaning up associated data.
 * @param   int     $lisingId   The ID of the listing to delete.
 */
function deleteListing($listingId) {
    $sql = "DELETE FROM Reviews WHERE listing_id = ?";
    DB::delete($sql, array($listingId));

    $sql = "DELETE FROM Listings WHERE id = ?";
    DB::delete($sql, array($listingId));
}
#endregion

// Review-related functions
#region
/**
 * Retrieves all reviews for a specific listing.
 * @usage   Used to display all reviews for a listing.
 * @param   int     $listingId      The ID of the listing to retrieve reviews for.
 * @return  array   Returns an array of reviews.
 */
function getListingReviews($listingId) {
    $sql = "
        SELECT
            R.id            AS reviewId,
            R.user_name     AS userName,
            R.rating        AS rating,
            R.created_at    AS date,
            R.review_text   AS reviewText
        FROM
            Reviews         AS R,
            Listings        AS L
        WHERE
            R.listing_id = L.id AND
            L.id = ?
        ORDER BY
            R.created_at
    ";

    $listingReviews = DB::select($sql, array($listingId));

    return $listingReviews;
}

/**
 * Retrieves a specific review by its ID.
 * @usage   Used to load the review edit form. 
 *          Also used in fake review detection to access reviewer's name.
 * @param   int     $reviewId       The ID of the review to retrieve.
 * @return  object  Returns the review object with detailed information.
 */
function getReview($reviewId) {
    $sql = "
        SELECT
            R.id            AS reviewId,
            R.user_name     AS userName,
            R.rating        AS rating,
            R.review_text   AS reviewText
        FROM
            Reviews         AS R,
            Listings        AS L
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

/**
 * Creates a new review for a listing in the database.
 * @usage   Used when a new review is submitted via the review creation form.
 * @param   int     $listingId      The ID of the listing the review belongs to
 *                                  to update average rating and review count.
 * @param   array   $formFields     The data from the review creating form.
 */
function createReview($listingId, $formFields) {
    // Insert the new review into the Reviews table
    $sql = "
        INSERT INTO Reviews (user_name, rating, created_at, review_text, listing_id)
        VALUES (?, ?, ?, ?, ?)
    ";

    DB::insert($sql, array(
        $formFields['userName'],
        $formFields['rating'],
        now(),
        $formFields['reviewText'],
        $listingId)
    );

    // Get the last inserted review ID
    $reviewId = DB::getPdo()->lastInsertId();

    if (!$reviewId) {
        die("Error while adding review");
    }

    // Call function to recalculate and update the average rating and review count
    _updateAverageRating($listingId);
    _updateReviewCount($listingId, 1);  // Increase review count by 1
}

/**
 * Updates an existing review in the database.
 * @usage   Used when editing a review via the review edit form.
 * @param   int     $listingId      The ID of the listing the review belongs to
 *                                  to update average rating.
 * @param   int     $reviewId       The ID of the review to update.
 * @param   array   $formFields     The updated data from the review edit form.
 */
function updateReview($listingId, $reviewId, $formFields) {
    $sql = "
        UPDATE Reviews
        SET
            rating = ?, created_at = ?, review_text = ?
        WHERE
            id = ?
    ";
    
    DB::update($sql, array($formFields['rating'], now(), $formFields['reviewText'], $reviewId));

    // Call function to recalculate and update the average rating
    _updateAverageRating($listingId);
}

/**
 * Deletes a specific review from the database.
 * @usage   Used when deleting a review.
 * @param   int     $listingId      The ID of the listing the review belongs to
 *                                  to update average rating and review count.
 * @param   int     $reviewId       The ID of the review to delete.
 */
function deleteReview($listingId, $reviewId) {
    // Delete the review
    $sql = "DELETE FROM Reviews WHERE id = ?";
    DB::delete($sql, array($reviewId));

    // Call function to recalculate and update the average rating
    _updateAverageRating($listingId);
    _updateReviewCount($listingId, -1); // Decrease review count by 1
}
#endregion

// Owner-related functions
#region
/**
 * Retrieves all owners,  optionally sorted by a given criterion.
 * 
 * @usage   Used to display all owners on the owners page,
 *          allowing sorting by date, rating, review count or listing count.
 * @param   string  $sort   Sorting criteria by date, rating, review count or listing count).
 * @return  array   Returns an array of owners.
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

    // Add sorting based on the selected criterion.
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
        case 'listing-desc':
            $orderSql = "ORDER BY listingCount DESC";
            break;
        case 'listing-asc':
            $orderSql = "ORDER BY listingCount";
            break;
    }

    $sql = $sql . $orderSql;

    $owners = DB::select($sql);

    return $owners;
}

/**
 * Retrieves all listings associated with a specific owner.
 * 
 * @usage   Used to display all listings owned by a specific owner,
 *          optionally sorted by a specified criterion.
 * @param   int     $ownerId    The ID of the owner.
 * @param   string  $sort       Sorting criteria by date, rating, review cont or rent.
 * @return  array Returns an array of listings belonging to the owner.
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
            L.review_count      AS reviewCount
        FROM
            Listings            AS L,
            Owners              AS O
        WHERE
            L.owner_name = O.name AND
            O.id = ?
        GROUP BY L.id
    ";

    // Add sorting based on the selected criterion.
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
        case 'rent-desc':
            $orderSql = "ORDER BY L.rent DESC";
            break;
        case 'rent-asc':
            $orderSql = "ORDER BY L.rent";
            break;
    }

    $sql = $sql . $orderSql;
    
    $listings = DB::select($sql, array($ownerId));

    return $listings;
}

/**
 * Retrieves a specific owner's information by their ID.
 * 
 * @usage   Used to display the owner's information on the website.
 * @param   int     $ownerId    The ID of the owner to retrieve.
 * @return  object  Returns the owner's details (owner's name).
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

/**
 * Creates a new owner in the database.
 * 
 * @usage   Used when creating a new owner, typically when a new listing is created.
 * @param   string  @ownerName The name of the owner to add.
 */
function createOwner($ownerName) {
    $sql = "
        INSERT OR IGNORE INTO Owners (name)
        VALUES (?)
    ";

    DB::update($sql, array($ownerName));
}
#endregion
?>