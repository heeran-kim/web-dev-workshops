<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Home page: listings
Route::get('/', function () {
    $listings = DB::select('
        SELECT
            L.Id AS "Id",
            L.Rent AS "Rent",
            L.City AS "City",
            L.State AS "State",
            AVG(R.Rating) AS "AverageRating",
            COUNT(R.Rating) AS "ReviewCount",
            I.Path AS "Image"
        FROM Reviews AS R, Listings AS L, Images AS I
        WHERE
            L.Id = R.ListingID AND
            L.Id = I.ListingID AND
            I.Id IN (SELECT MIN(I.Id) FROM Listings AS L, Images AS I WHERE I.ListingID = L.Id GROUP BY L.Id)
        GROUP BY L.Id
        ORDER BY L.Id DESC
    ');
    
    return view('listings.index', compact('listings'));
});

// Show create form
Route::get('/listings/create', function () {
    return view('listings/create');
});

// Store a listing
Route::post('/listings', function () {
    return redirect('/');
});

// Show edit form
Route::get('/listings/{id}/edit', function ($id) {
    return view('listings.edit');
});

// Review page: single listing
Route::get('/listings/{id}', function ($id) {
    $listing = DB::select('
        SELECT
            L.Id AS "Id",
            L.Title AS "Title",
            L.OwnerID AS "OwnerId",
            U.Name AS "Owner",
            L.Rent AS "Rent",
            L.Street AS "Street",
            L.City AS "City",
            L.State AS "State",
            strftime("%d-%m-%Y", L.AvailableDate) AS "AvailableDate",
            L.Description AS "Description",
            L.IsFurnished AS "IsFurnished",
            L.IsBillIncluded AS "IsBillIncluded", 
            ROUND(AVG(R.Rating), 1) AS "AverageRating",
            COUNT(R.Rating) AS "ReviewCount"
        FROM Reviews AS R, Users AS U, Listings AS L
        WHERE R.ListingID = L.Id AND L.OwnerID = U.Id AND L.Id = ?', [$id]
    );

    $images = DB::select('
        SELECT
            I.Path AS "Path"
        FROM Listings AS L, Images AS I
        WHERE L.Id = I.ListingID AND L.Id = ?', [$id]);

    $reviews = DB::select('
        SELECT
            U.Id AS "UserId",
            U.Name AS "Reviewer",
            R.Rating AS "Rating",
            strftime("%d-%m-%Y", R.Date) AS "Date",
            R.Review AS "Review"
        FROM Reviews AS R, Users AS U, Listings AS L
        WHERE R.ListingID = L.Id AND R.UserID = U.Id AND L.Id = ?
        ORDER BY R.Id DESC
        ', [$id]);

    return view('listings.show', compact('listing', 'images', 'reviews'));
});

// Store a review
Route::post('/listings/{id}', function ($id) {
    return back();
});

// List all owners
Route::get('/owners', function() {
    $owners = DB::select("
        SELECT
            U.Id AS 'Id',
            U.Name AS 'Name',
            AVG(NT.Average) AS 'AverageRating',
            SUM(NT.Count) AS 'ReviewCount'
        FROM Listings AS L, Users AS U, (SELECT L.Id AS 'ListingId', AVG(R.Rating) AS 'Average', COUNT(R.Rating) AS 'Count'
                                        FROM Reviews AS R, Listings AS L
                                        WHERE R.ListingID = L.Id
                                        GROUP BY L.Id) AS NT
        WHERE L.OwnerID = U.Id AND NT.ListingId = L.Id
        GROUP BY U.Name
        ORDER BY Average DESC
    ");

    return view('users.index', compact('owners'));
});

Route::get('/owners/{id}', function ($id) {
    $listings = DB::select('
        SELECT
            L.Id AS "Id",
            U.Name AS "Name",
            L.Rent AS "Rent",
            L.City AS "City",
            L.State AS "State",
            AVG(R.Rating) AS "AverageRating",
            COUNT(R.Rating) AS "ReviewCount",
            I.Path AS "Image"
        FROM Reviews AS R, Listings AS L, Images AS I, Users AS U
        WHERE
            L.Id = R.ListingID AND
            L.Id = I.ListingID AND
            L.OwnerId = U.Id AND
            I.Id IN (SELECT MIN(I.Id) FROM Listings AS L, Images AS I WHERE I.ListingID = L.Id GROUP BY L.Id) AND
            L.OwnerID = ?
        GROUP BY L.Id
        ORDER BY L.Id DESC'
        , [$id]);

    return view('users.show', compact('listings'));
});