<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// CRUD
// Private
function _getAverageRating ($listingId) {
    $sql = "
        SELECT
            AVG(R.rating)   AS 'averageRating'
        FROM
            Reviews         AS R,
            Listings        AS L
        WHERE
            R.listing_id = L.id AND
            L.id = ?
    ";

    $averageRatings = DB::select($sql, array($listingId));

    if (count($averageRatings) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $averageRating = $averageRatings[0]->averageRating;

    return $averageRating;
}

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

// Validation
// Private
function _validateName ($name) {
    $forbiddenChars = ['+', '-', '_', '"'];

    if (strlen($name) <= 2){
        return false;
    }

    if (strlen($name) > 20){
        return false;
    }

    foreach ($forbiddenChars as $forbiddenChar) {
        if (strpos($name, $forbiddenChar)) {
            return false;
        }
    }

    return true;
}

function _removeDigitsFromName ($name) {
    $output = "";

    for ($i = 0; $i < strlen($name); $i++){
        $char = $name[$i];
        if (!is_numeric($char)){
            $output .= $char;
        }
    }

    // If the name hasn't changed, return null
    if ($output === $name) {
        return null;
    }

    return $output;
}

function _checkDuplicateName ($name, $listingId) {
    $reviews = getListingReviews($listingId);
    
    foreach ($reviews as $review) {
        if ($review->userName == $name){
            return false;
        }
    }

    return true;
}

function _validateNumber ($number) {
    if (!filter_var($number, FILTER_VALIDATE_INT)){
        return false;
    }
    return true;
}

function _validateReview ($review) {
    if (strlen($review) <= 5){
        return false;
    }
    return true;
}

function _validateListingInput ($formFields) {
    $errorMessage = null;
    
    if(!_validateName($formFields['title'])){
        $errorMessage['title'] = 'A title must be 3-20 characters long and cannot have the following symbols: -, _, +, ".';
    }

    if (!_validateName($formFields['ownerName'])){
        $errorMessage['ownerName'] = 'A name must be 3-20 characters long after removing numbers and cannot have the following symbols: -, _, +, ".';
    }

    if (!_validateName($formFields['city'])){
        $errorMessage['city'] = 'A city must be 3-20 characters long and cannot have the following symbols: -, _, +, ".';
    }

    if (!isset($formFields['state'])){
        $errorMessage['state'] = 'A state must be selected.';
    }

    if (!_validateNumber($formFields['rent'])){
        $errorMessage['rent'] = 'A rent is required and must be an integer.';
    }

    return $errorMessage;
}

function _validateCreateReviewInput ($formFields, $listingId) {
    if (!_validateName($formFields['userName'])){
        return 'A name must be 3-20 characters long after removing numbers and cannot have the following symbols: -, _, +, ".';
    }

    if (!_checkDuplicateName($formFields['userName'], $listingId)){
       return 'A name must be unique.';
    }

    if (!isset($formFields['rating'])){
        return 'A rating must be selected.';
    }

    if (!_validateReview($formFields['review'])){
        return 'A review must have more than 5 characters.';
    }

    return null;
}

function _validateEditReviewInput ($formFields, $listingId) {
    if (!isset($formFields['rating'])){
        return 'A rating must be selected.';
    }

    if (!_validateReview($formFields['review'])){
        return 'A review must have more than 5 characters.';
    }

    return null;
}

// Public
// Listings
// Usage: Display all listings
function getListings($sort) {
    $sql = "
        SELECT
            L.id                AS 'listingId',
            L.title             AS 'title',
            L.rent              AS 'rent',
            L.city              AS 'city',
            L.state             AS 'state',
            L.average_rating    AS 'averageRating',
            L.review_count      AS 'reviewCount'
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

// Usage: Display specific listing, show listing edit form
function getListing($listingId) {
    $sql = "
        SELECT
            L.id                                    AS 'listingId',
            L.title                                 AS 'title',
            O.id                                    AS 'ownerId',
            O.name                                  AS 'ownerName',
            L.rent                                  AS 'rent',
            L.street                                AS 'street',
            L.city                                  AS 'city',
            L.state                                 AS 'state',
            L.available_date                        AS 'availableDate',
            L.description                           AS 'description',
            L.is_furnished                          AS 'isFurnished',
            L.is_bill_included                      AS 'isBillIncluded',
            FORMAT(ROUND(L.average_rating, 1),1)    AS 'averageRating',
            L.review_count                          AS 'reviewCount'
        FROM
            Owners                                  AS O,
            Listings                                AS L
        WHERE
            L.owner_name = O.name AND
            L.id = ?
    ";

    $listings = DB::select($sql, array($listingId));

    if (count($listings) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $listing = $listings[0];

    return $listing;
}

// Usage: Create submit to store listing
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

// Usage: Edit submit to update specific listing
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

// Usage: Delete specific listing
function deleteListing($listingId) {
    $sql = "DELETE FROM Reviews WHERE listing_id = ?";
    DB::delete($sql, array($listingId));

    $sql = "DELETE FROM Listings WHERE id = ?";
    DB::delete($sql, array($listingId));
}

// Reviews
// Usage: Display specific listing
function getListingReviews($listingId) {
    $sql = "
        SELECT
            R.id        AS 'reviewId',
            R.user_name AS 'userName',
            R.rating    AS 'rating',
            R.date      AS 'date',
            R.review    AS 'review'
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

// Usage: show review edit form
function getReview($reviewId) {
    $sql = "
        SELECT
            R.id        AS 'reviewId',
            R.user_name AS 'userName',
            R.rating    AS 'rating',
            R.review    AS 'review'
        FROM
            Reviews     AS R,
            Listings    AS L
        WHERE
            R.listing_id = L.id AND
            R.id = ?
    ";

    $reviews = DB::select($sql, array($reviewId));

    if (count($reviews) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $review = $reviews[0];

    return $review;
}

// Usage: Create submit to store review
function createReview($listingId, $formFields) {
    // Insert the new review into the Reviews table
    $sql = "
        INSERT INTO Reviews (user_name, rating, date, review, listing_id)
        VALUES (?, ?, ?, ?, ?)
    ";

    DB::insert($sql, array(
        $formFields['userName'],
        $formFields['rating'],
        now()->format('Y-m-d'),
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
            average_rating  = (IFNULL(average_rating, 0) * review_count + ?)/(review_count + 1),
            review_count    = review_count + 1
        WHERE
            id = ?
    ";

    DB::update($sql, array(
        $formFields['rating'],
        $listingId)
    );
}

// Usage: Edit submit to update specific review
function updateReview($listingId, $reviewId, $formFields) {
    $sql = "
        UPDATE Reviews
        SET
            rating = ?, date = ?, review = ?
        WHERE
            id = ?
    ";
    
    DB::update($sql, array($formFields['rating'], now()->format('Y-m-d'), $formFields['review'], $reviewId));

    $averageRating = _getAverageRating($listingId);
    _updateAverageRating($listingId, $averageRating);
}

// Usage: Delete specific review
function deleteReview($listingId, $reviewId) {
    $sql = "DELETE FROM Reviews WHERE id = ?";
    DB::delete($sql, array($reviewId));

    $averageRating = _getAverageRating($listingId);
    _updateAverageRating($listingId, $averageRating);
    _decrementReviewCount($listingId);
}

// Owners
// Usage: Display all owners
function getOwners($sort) {
    $sql = "
        SELECT
            O.id                                        AS 'ownerId',
            O.name                                      AS 'ownerName',
            COUNT(L.id)                                 AS 'listingCount',
            CAST(ROUND(AVG(L.average_rating),1) AS DECIMAL(1,1))    AS 'averageRating',
            SUM(L.review_count)                         AS 'reviewCount'
        FROM
            Owners                                      AS O,
            Listings                                    AS L
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

// Usage: Display all listings of specific owner
function getOwnerListings($ownerId, $sort) {
    $sql = "
        SELECT
            L.id                        AS 'listingId',
            L.title                     AS 'title',
            L.rent                      AS 'rent',
            L.city                      AS 'city',
            L.state                     AS 'state',
            FORMAT(L.average_rating,1)  AS 'averageRating',
            L.review_count              AS 'reviewCount'
        FROM
            Listings                    AS L,
            Owners                      AS O
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
    dd($listings);

    return $listings;
}

// Usage: Display all listings of specific owner
function getOwner($ownerId) {
    $sql = "
        SELECT
            O.id    AS 'ownerId',
            O.name  AS 'ownerName'
        FROM
            Owners  AS O
        WHERE
            O.id = ?
    ";

    $owners = DB::select($sql, array($ownerId));

    if (count($owners) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }
    
    $owner = $owners[0];
    
    return $owner;
}

function createOwner($ownerName) {
    $sql = "
        INSERT OR IGNORE INTO Owners (name)
        VALUES (?)
    ";

    DB::update($sql, array($ownerName));
}


// Route
// Listings
// Display all listings
Route::get('/', function () {
    $sort = request('sort') ?? 'date-desc';
    $listings = getListings($sort);
    return view('listings.index')->with('listings', $listings)->with('sort', $sort);
});

// Show create form
Route::get('listings/create', function () {
    return view('listings/create');
});

// Create submit to store listing
Route::post('listings', function () {
    // input
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    // Sanitize the owner's name by removing any digits.
    $alteredName = _removeDigitsFromName($formFields['ownerName']);
    if ($alteredName){
        $formFields['ownerName'] = $alteredName;
    }
    
    // input validation
    $errorMessage = _validateListingInput($formFields);
    if ($errorMessage) {
        return back()->with("errorMessage", $errorMessage)->with("formFields", $formFields);
    }

    // add user if don't exist
    createOwner($formFields['ownerName']);

    // create listing
    $listingId = createListing($formFields);

    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Show listing edit form
Route::get('listings/{listingId}/edit', function ($listingId) {
    $listing = getListing($listingId);
    return view('listings.edit')->with('listing', $listing);
});

// Edit submit to update specific listing
Route::put('listings/{listingId}', function ($listingId) {
    // input
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    // Sanitize the owner's name by removing any digits.
    $alteredName = _removeDigitsFromName($formFields['ownerName']);
    if ($alteredName){
        $formFields['ownerName'] = $alteredName;
    }
    
    // input validation
    $errorMessage = _validateListingInput($formFields);
    if ($errorMessage) {
        return back()->with("errorMessage", $errorMessage)->with("formFields", $formFields);
    }

    // update listing
    updateListing($listingId, $formFields);

    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Delete specific listing
Route::delete('listings/{listingId}', function ($listingId) {
    deleteListing($listingId);
    return redirect('/');
});

// Display specific listing
Route::get('listings/{listingId}', function ($listingId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews);
});

// Create submit to store review
Route::post('listings/{listingId}/reviews', function ($listingId) {
    $createFields = request()->all();

    // Sanitize the user's name by removing any digits.
    $alteredName = _removeDigitsFromName($createFields['userName']);
    if ($alteredName){
        $createFields['userName'] = $alteredName;
    }
    
    // input validation
    $createError = _validateCreateReviewInput($createFields, $listingId);
    if ($createError) {
        return back()->with("createError", $createError)->with("createFields", $createFields);
    }

    createReview($listingId, $createFields);

    session(['userName' => $createFields['userName']]);

    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Show review edit form
Route::get('listings/{listingId}/reviews/{reviewId}/edit', function ($listingId, $reviewId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);
    $reviewToEdit = getReview($reviewId);
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews)->with('reviewToEdit', $reviewToEdit);
});

// Edit submit to update specific review
Route::put('listings/{listingId}/reviews/{reviewId}', function ($listingId, $reviewId) {
    $editFields = request()->all();
    
    // input validation
    $editError = _validateEditReviewInput($editFields, $listingId);
    if ($editError) {
        return back()->with("editError", $editError)->with("editFields", $editFields);
    }
    
    updateReview($listingId, $reviewId, $editFields);
    
    return redirect(url("listings/$listingId"));
});

// Delete specific review
Route::delete('listings/{listingId}/reviews/{reviewId}', function ($listingId, $reviewId) {
    deleteReview($listingId, $reviewId);
    return redirect(url("listings/$listingId"));
});

// Owners
// Display all owners
Route::get('owners', function() {
    $sort = request('sort') ?? 'date-desc';
    $owners = getOwners($sort);
    return view('owners.index')->with('owners', $owners)->with('sort', $sort);
});

// display all listings of specific owner
Route::get('owners/{ownerId}', function ($ownerId) {
    $owner = getOwner($ownerId);
    $sort = request('sort') ?? 'date-desc';
    $listings = getOwnerListings($ownerId, $sort);
    return view('owners.show')->with('owner', $owner)->with('listings', $listings)->with('sort', $sort);
});
