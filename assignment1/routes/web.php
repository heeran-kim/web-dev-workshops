<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// display all listings
function getListings() {
    $sql = "
        SELECT
            L.Id                                    AS 'ListingId',
            L.Title                                 AS 'Title',
            L.Rent                                  AS 'Rent',
            L.City                                  AS 'City',
            L.State                                 AS 'State'
        FROM
            Listings AS L
        GROUP BY L.Id
        ORDER BY L.Id DESC
    ";
    
    $listings = DB::select($sql);

    foreach ($listings as $listing){
        $listingReviewStat = getListingReviewStat($listing->ListingId);
        $listing->ReviewStat = $listingReviewStat;
    }

    return $listings;
}

// display all listings of specific user
function getUserListings($userId) {
    $sql = "
        SELECT
            L.Id                                    AS 'ListingId',
            L.Title                                 AS 'Title',
            L.Rent                                  AS 'Rent',
            L.City                                  AS 'City',
            L.State                                 AS 'State'
        FROM
            Listings AS L,
            Users AS U
        WHERE
            L.UserId = U.Id AND
            U.Id = ?
        GROUP BY L.Id
        ORDER BY L.Id DESC
    ";
    
    $listings = DB::select($sql, array($userId));

    foreach ($listings as $listing){
        $listingReviewStat = getListingReviewStat($listing->ListingId);
        $listing->ReviewStat = $listingReviewStat;
    }

    return $listings;
}

// display single listing, show edit form
function getListing($listingId) {
    $sql = "
        SELECT
            L.Id                                    AS 'ListingId',
            L.Title                                 AS 'Title',
            L.UserId                                AS 'UserId',
            U.Name                                  AS 'UserName',
            L.Rent                                  AS 'Rent',
            L.Street                                AS 'Street',
            L.City                                  AS 'City',
            L.State                                 AS 'State',
            strftime('%d-%m-%Y', L.AvailableDate)   AS 'AvailableDate',
            L.Description                           AS 'Description',
            L.IsFurnished                           AS 'IsFurnished',
            L.IsBillIncluded                        AS 'IsBillIncluded'
        FROM
            Users AS U,
            Listings AS L
        WHERE
            L.UserId = U.Id AND
            L.Id = ?
    ";

    $listings = DB::select($sql, array($listingId));

    if (count($listings) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $listing = $listings[0];

    $listing->ReviewStat = getListingReviewStat($listingId);

    return $listing;
}

// Create submit to store listing
function createListing($formFields){
    $sql = "
        INSERT INTO Listings (Title, Street, City, State, Rent, AvailableDate, IsFurnished, IsBillIncluded, Description, UserId)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    DB::insert($sql, array( $formFields['title'], $formFields['street'], $formFields['city'], $formFields['state'],
                            $formFields['rent'], $formFields['availableDate'], $formFields['isFurnished'], $formFields['isBillIncluded'],
                            $formFields['description'], getUserId($formFields['userName'])));

    $listingId = DB::getPdo()->lastInsertId();

    return $listingId;
}

// Edit submit to update listing
function updateListing($listingId, $formFields) {
    $sql = "
        UPDATE Listings
        SET
            Title = ?, Street = ?, City = ?, State = ?,
            Rent = ?, AvailableDate = ?, IsFurnished = ?, IsBillIncluded = ?,
            Description = ?
        WHERE
            Id = ?
    ";
    
    DB::update($sql, array( $formFields['title'], $formFields['street'], $formFields['city'], $formFields['state'],
                            $formFields['rent'], $formFields['availableDate'], $formFields['isFurnished'], $formFields['isBillIncluded'],
                            $formFields['description'], $listingId));
}

// Delete listing
function deleteListing($listingId) {
    $sql = "DELETE FROM Reviews WHERE ListingId = ?";
    DB::delete($sql, array($listingId));

    $sql = "DELETE FROM Listings WHERE Id = ?";
    DB::delete($sql, array($listingId));
}

// 
function getListingReviewStat($listingId) {
    $sql = "
        SELECT
            ROUND(AVG(R.Rating), 1)                 AS 'AverageRating',
            COUNT(R.Rating)                         AS 'ReviewCount'
        FROM
            Reviews AS R,
            Listings AS L
        WHERE
            R.ListingId = L.Id AND
            L.Id = ?
    ";

    $listingReviewStats = DB::select($sql, array($listingId));

    if (count($listingReviewStats) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $listingReviewStat = $listingReviewStats[0];

    return $listingReviewStat;
}

// display single listing
function getListingReviews($listingId) {
    $sql = "
        SELECT
            U.Id                                    AS 'UserId',
            U.Name                                  AS 'UserName',
            R.Rating                                AS 'Rating',
            strftime('%d-%m-%Y', R.Date)            AS 'Date',
            R.Review                                AS 'Review'
        FROM
            Reviews AS R,
            Users AS U,
            Listings AS L
        WHERE
            R.ListingID = L.Id AND
            R.UserId = U.Id AND
            L.Id = ?
        ORDER BY
            R.Id DESC
    ";

    $listingReviews = DB::select($sql, array($listingId));

    return $listingReviews;
}

// display all users
function getUsers() {
    $sql = "
        SELECT
            U.Id                                    AS 'UserId',
            U.Name                                  AS 'UserName',
            COUNT(L.Id)                             AS 'ListingCount'
        FROM
            Users AS U,
            Listings AS L
        WHERE
            L.UserId = U.Id
        GROUP BY U.Id
    ";

    $users = DB::select($sql);

    foreach ($users as $user){
        $userReviewStat = getUserReviewStat($user->UserId);
        $user->ReviewStat = $userReviewStat;
    }

    return $users;
}

function getUserReviewStat($userId) {
    $sql = "
        SELECT
            ROUND(AVG(NT.Average),1)                AS 'AverageRating',
            SUM(NT.Count)                           AS 'ReviewCount'
        FROM
            Listings AS L,
            Users AS U,
            (SELECT
                L.Id                    AS 'ListingId',
                ROUND(AVG(R.Rating),1)  AS 'Average',
                COUNT(R.Rating)         AS 'Count'
            FROM
                Reviews AS R,
                Listings AS L
            WHERE
                R.ListingId = L.Id
            GROUP BY L.Id) AS NT
        WHERE
            L.UserId = U.Id AND
            NT.ListingId = L.Id AND
            U.Id = ?
        GROUP BY U.Id
    ";

    $userReviewStats = DB::select($sql, array($userId));
    
    if (count($userReviewStats) > 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }

    if ($userReviewStats){
        $userReviewStat = $userReviewStats[0];
    }
    else{
        $userReviewStat = [];
    }

    return $userReviewStat;
}

function getUser($userId) {
    $sql = "
        SELECT
            U.Name                                  AS 'UserName'
        FROM
            Users AS U
        WHERE
            U.Id = ?
    ";

    $users = DB::select($sql, array($userId));

    if (count($users) != 1){
        die("Something has gone wrong, invalid query or result: $sql");
    }
    
    $user = $users[0];
    
    return $user;
}

function getUserId($userName) {
    $sql = "
        SELECT
            U.Id AS 'UserId'
        FROM
            Users AS U
        WHERE
            U.Name == ?
    ";

    $users = DB::select($sql, array($userName));

    if (count($users) != 1) {
        die("Something has gone wrong, invalid query or result: $sql");
    }

    $userId = $users[0]->UserId;

    return $userId;
}

// Display all listings
Route::get('/', function () {
    $listings = getListings();
    return view('listings.index')->with('listings', $listings);
});

// Show create form
Route::get('listings/create', function () {
    return view('listings/create');
});

// Create submit to store listing
Route::post('listings', function () {
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    $listingId = createListing($formFields);

    if ($listingId){
        return redirect(url("listings/$listingId"));
    } else {
        die("Error while adding item.");
    }
});

// Display single listing
Route::get('listings/{listingId}', function ($listingId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews);
});

// Show edit form
Route::get('listings/{listingId}/edit', function ($listingId) {
    $listing = getListing($listingId);
    return view('listings.edit')->with('listing', $listing);
});

// Edit submit to update listing
Route::put('listings/{listingId}', function ($listingId) {
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    updateListing($formFields, $listingId);

    return redirect(url("listings/$listingId"));
});

// Delete listing
Route::delete('listings/{listingId}', function ($listingId) {
    deleteListing($listingId);
    return redirect('/');
});

// Store review
Route::post('listings/{id}', function ($id) {
    return back();
});

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