<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// CRUD
// Private
// Description: Get review stat of specific listing
function _getListingReviewStat($listingId) {
    $sql = "
        SELECT
            ROUND(AVG(R.rating), 1) AS 'averageRating',
            COUNT(R.rating)         AS 'reviewCount'
        FROM
            Reviews                 AS R,
            Listings                AS L
        WHERE
            R.listing_id = L.id AND
            L.id = ?
    ";

    $listingReviewStats = DB::select($sql, array($listingId));

    if (count($listingReviewStats) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $listingReviewStat = $listingReviewStats[0];

    return $listingReviewStat;
}

// Description: Get review stat of specific user
function _getUserReviewStat($userId) {
    // 1. user가 listing X => null null => []
        // 0    1.5     2
        // 1    4.0     1 
        // 2    null    0
        // 3    3.0     4
    // 2. user가 listing 가졌는데 review X => null 0 => [0]->AverageRating = null, [0]->ReviewCount = 0
        // 0    1.5     2
        // 1    4.0     1 
        // 2    null    0
        // 3    3.0     4
    // 3. user가 listing 여러개가졌는데 일부 listing review X => null 제외 계산
        // 0    1.5     2
        // 1    4.0     1 
        // 2    null    0
        // 3    3.0     4
    $sql = "
        SELECT
            ROUND(AVG(Review_stat.average),1)   AS 'averageRating',
            SUM(Review_stat.count)              AS 'reviewCount'
        FROM
            Listings                            AS L,
            Users                               AS U,
            (SELECT
                L.id                    AS 'listing_id',
                ROUND(AVG(R.rating),1)  AS 'average',
                COUNT(R.rating)         AS 'count'
            FROM
                Reviews AS R,
                Listings AS L
            WHERE
                R.listing_id = L.id
            GROUP BY L.id)                      AS Review_stat
        WHERE
            L.user_id = U.id AND
            Review_stat.listing_id = L.id AND
            U.id = ?
        GROUP BY U.id
    ";

    $userReviewStats = DB::select($sql, array($userId));
    
    if (count($userReviewStats) > 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    if ($userReviewStats){
        $userReviewStat = $userReviewStats[0];
    }else {
        $userReviewStat = [];
    }

    return $userReviewStat;
}

// Description: Get user id of specific user
function _getUserId($userName) {
    $sql = "
        SELECT
            U.id    AS 'userId'
        FROM
            Users   AS U
        WHERE
            U.name == ?
    ";

    $users = DB::select($sql, array($userName));

    if (count($users) != 1) {
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $userId = $users[0]->userId;

    return $userId;
}

// Public
// Listings
// Usage: Display all listings
function getListings($sort) {
    $sql = "
        SELECT
            L.id        AS 'listingId',
            L.title     AS 'title',
            L.rent      AS 'rent',
            L.city      AS 'city',
            L.state     AS 'state'
        FROM
            Listings    AS L
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
            $orderSql = "ORDER BY ";
    }

    $sql = $sql . $orderSql;

    // dd($sql);
    
    $listings = DB::select($sql);

    foreach ($listings as $listing){
        $listingReviewStat = _getListingReviewStat($listing->listingId);
        $listing->reviewStat = $listingReviewStat;
    }

    return $listings;
}

// Usage: Display specific listing, show listing edit form
function getListing($listingId) {
    $sql = "
        SELECT
            L.id                AS 'listingId',
            L.title             AS 'title',
            L.user_id           AS 'userId',
            U.name              AS 'userName',
            L.rent              AS 'rent',
            L.street            AS 'street',
            L.city              AS 'city',
            L.state             AS 'state',
            L.available_date    AS 'availableDate',
            L.description       AS 'description',
            L.is_furnished      AS 'isFurnished',
            L.is_bill_included  AS 'isBillIncluded'
        FROM
            Users               AS U,
            Listings            AS L
        WHERE
            L.user_id = U.id AND
            L.id = ?
    ";

    $listings = DB::select($sql, array($listingId));

    if (count($listings) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $listing = $listings[0];

    $listing->reviewStat = _getListingReviewStat($listingId);

    return $listing;
}

// Usage: Create submit to store listing
function createListing($formFields){
    $sql = "
        INSERT INTO Listings (title, street, city, state, rent, available_date, is_furnished, is_bill_included, description, user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    DB::insert($sql, array( $formFields['title'], $formFields['street'], $formFields['city'], $formFields['state'],
                            $formFields['rent'], $formFields['availableDate'],
                            $formFields['isFurnished'], $formFields['isBillIncluded'],
                            $formFields['description'], _getUserId($formFields['userName'])));

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
            U.id        AS 'userId',
            U.name      AS 'userName',
            R.rating    AS 'rating',
            R.date      AS 'date',
            R.review    AS 'review'
        FROM
            Reviews     AS R,
            Users       AS U,
            Listings    AS L
        WHERE
            R.listing_id = L.id AND
            R.user_id = U.id AND
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
            U.name      AS 'userName',
            R.rating    AS 'rating',
            R.review    AS 'review'
        FROM
            Reviews     AS R,
            Users       AS U,
            Listings    AS L
        WHERE
            R.listing_id = L.id AND
            R.user_id = U.id AND
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
    $sql = "
        INSERT INTO Reviews (rating, date, review, listing_id, user_id)
        VALUES (?, ?, ?, ?, ?)
    ";

    DB::insert($sql, array( $formFields['rating'], now()->format('Y-m-d'), $formFields['review'],
                            $listingId, _getUserId($formFields['userName'])));

    $reviewId = DB::getPdo()->lastInsertId();

    if (!$reviewId) {
        die("Error while adding review");
    }
}

// Usage: Edit submit to update specific review
function updateReview($reviewId, $formFields) {
    $sql = "
        UPDATE Reviews
        SET
            rating = ?, date = ?, review = ?
        WHERE
            id = ?
    ";
    
    DB::update($sql, array($formFields['rating'], now()->format('Y-m-d'), $formFields['review'], $reviewId));
}

// Usage: Delete specific review
function deleteReview($reviewId) {
    $sql = "DELETE FROM Reviews WHERE id = ?";
    DB::delete($sql, array($reviewId));
}

// Users
// Usage: Display all users
function getUsers() {
    $sql = "
        SELECT
            U.id        AS 'userId',
            U.name      AS 'userName',
            COUNT(L.id) AS 'listingCount'
        FROM
            Users       AS U,
            Listings    AS L
        WHERE
            L.user_id = U.id
        GROUP BY U.id
    ";

    $users = DB::select($sql);

    foreach ($users as $user){
        $userReviewStat = _getUserReviewStat($user->userId);
        $user->reviewStat = $userReviewStat;
    }

    return $users;
}

// Usage: Display all listings of specific user
function getUserListings($userId) {
    $sql = "
        SELECT
            L.id        AS 'listingId',
            L.title     AS 'title',
            L.rent      AS 'rent',
            L.city      AS 'city',
            L.state     AS 'state'
        FROM
            Listings    AS L,
            Users       AS U
        WHERE
            L.user_id = U.id AND
            U.id = ?
        GROUP BY L.id
        ORDER BY L.id DESC
    ";
    
    $listings = DB::select($sql, array($userId));

    foreach ($listings as $listing){
        $listingReviewStat = _getListingReviewStat($listing->listingId);
        $listing->reviewStat = $listingReviewStat;
    }

    return $listings;
}

// Usage: Display all listings of specific user
function getUser($userId) {
    $sql = "
        SELECT
            U.id    AS 'userId',
            U.name  AS 'userName'
        FROM
            Users   AS U
        WHERE
            U.id = ?
    ";

    $users = DB::select($sql, array($userId));

    if (count($users) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }
    
    $user = $users[0];
    
    return $user;
}


// Route
// Listings
// Display all listings
Route::get('/', function () {
    $sort = request('sort') ?? 'date-desc';
    $listings = getListings($sort);
    return view('listings.index')->with('listings', $listings);
});

// Show create form
Route::get('listings/create', function () {
    return view('listings/create');
});

// Create submit to store listing
Route::post('listings', function () {
    $formFields = request()->all();
    // check
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    $listingId = createListing($formFields);

    return redirect(url("listings/$listingId"));
});

// Show listing edit form
Route::get('listings/{listingId}/edit', function ($listingId) {
    $listing = getListing($listingId);
    return view('listings.edit')->with('listing', $listing);
});

// Edit submit to update specific listing
Route::put('listings/{listingId}', function ($listingId) {
    $formFields = request()->all();
    // check
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    updateListing($listingId, $formFields);

    return redirect(url("listings/$listingId"));
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
    $formFields = request()->all();
    createReview($listingId, $formFields);

    return redirect(url("listings/$listingId"));
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
    $formFields = request()->all();
    updateReview($reviewId, $formFields);
    return redirect(url("listings/$listingId"));
});

// Delete specific review
Route::delete('listings/{listingId}/reviews/{reviewId}', function ($listingId, $reviewId) {
    deleteReview($reviewId);
    return redirect(url("listings/$listingId"));
});

// Users
// Display all users
Route::get('users', function() {
    $users = getUsers();
    return view('users.index')->with('users', $users);
});

// display all listings of specific user
Route::get('users/{userId}', function ($userId) {
    $user = getUser($userId);
    $listings = getUserListings($userId);
    return view('users.show')->with('user', $user)->with('listings', $listings);
});