<?php

use Illuminate\Support\Facades\Route;
include "includes/crud.php";
include "includes/validator.php";
include "includes/fake-review-detector.php";


// Route definitions that handle HTTP requests for the application.

// Listings
// Display all listings with optional sorting.
Route::get('/', function () {
    $sort = request('sort') ?? 'date-desc';
    $listings = getListings($sort);
    return view('listings.index')->with('listings', $listings)->with('sort', $sort);
});

// Show the form for creating a new listing.
Route::get('listings/create', function () {
    return view('listings/create');
});

// Store a new listing in the database.
Route::post('listings', function () {
    // Input from the request
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    // Sanitize the owner's name by removing any digits.
    $alteredName = removeDigitsFromName($formFields['ownerName']);
    if ($alteredName){
        $formFields['ownerName'] = $alteredName;
    }
    
    // Validate the input
    $errorMessage = validateListingInput($formFields);
    if ($errorMessage) {
        return back()->with("errorMessage", $errorMessage)->with("formFields", $formFields);
    }

    // Add the owner if they don't exist
    createOwner($formFields['ownerName']);

    // Create the listing
    $listingId = createListing($formFields);

    // Redirect to the specific listing's page and inform the user if the name was changed.
    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Show the form for editing a specific listing.
Route::get('listings/{listingId}/edit', function ($listingId) {
    $listing = getListing($listingId);
    return view('listings.edit')->with('listing', $listing);
});

// Update a specific listing in the database.
Route::put('listings/{listingId}', function ($listingId) {
    // Input from the request
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    // Sanitize the owner's name by removing any digits.
    $alteredName = removeDigitsFromName($formFields['ownerName']);
    if ($alteredName){
        $formFields['ownerName'] = $alteredName;
    }
    
    // Validate the input
    $errorMessage = validateListingInput($formFields);
    if ($errorMessage) {
        return back()->with("errorMessage", $errorMessage)->with("formFields", $formFields);
    }

    // Update the listing
    updateListing($listingId, $formFields);

    // Redirect to the updated listing's page and inform the user if the name was changed.
    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Delete a specific listing from the database.
Route::delete('listings/{listingId}', function ($listingId) {
    deleteListing($listingId);
    return redirect(url("/"));
});

// Display a specific listing and its reviews.
Route::get('listings/{listingId}', function ($listingId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews);
});


// Reviews
// Store a new review for a specific listing.
Route::post('listings/{listingId}/reviews', function ($listingId) {
    // Input from the request
    $createFields = request()->all();

    // Sanitize the user's name by removing any digits.
    $alteredName = removeDigitsFromName($createFields['userName']);
    if ($alteredName){
        $createFields['userName'] = $alteredName;
    }
    
    // Validate the input
    $createError = validateCreateReviewInput($createFields, $listingId);
    if ($createError) {
        return back()->with("createError", $createError)->with("createFields", $createFields);
    }

    // Detect fake review
    $fakeReviewError = _detectFakeReview($createFields);
    if ($fakeReviewError){
        return back()->with("fakeReviewError", $fakeReviewError);
    }

    // Create the review
    createReview($listingId, $createFields);

    // Store the reviewer's name in the session
    session(['userName' => $createFields['userName']]);

    // Redirect to the listing's page and inform the user if the name was changed.
    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Show the form for editing a specific review.
Route::get('listings/{listingId}/reviews/{reviewId}/edit', function ($listingId, $reviewId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);
    $reviewToEdit = getReview($reviewId);
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews)->with('reviewToEdit', $reviewToEdit);
});

// Update a specific review in the database.
Route::put('listings/{listingId}/reviews/{reviewId}', function ($listingId, $reviewId) {
    $editFields = request()->all();
    
    // input validation
    $editError = validateEditReviewInput($editFields, $listingId);
    if ($editError) {
        return back()->with("editError", $editError)->with("editFields", $editFields);
    }
    
    updateReview($listingId, $reviewId, $editFields);
    
    return redirect(url("listings/$listingId"));
});

// Delete a specific review from the database.
Route::delete('listings/{listingId}/reviews/{reviewId}', function ($listingId, $reviewId) {
    deleteReview($listingId, $reviewId);
    return redirect(url("listings/$listingId"));
});


// Owners
// Display all owners with optional sorting.
Route::get('owners', function() {
    $sort = request('sort') ?? 'date-desc';
    $owners = getOwners($sort);
    return view('owners.index')->with('owners', $owners)->with('sort', $sort);
});

// Display all listings of a specific owner.
Route::get('owners/{ownerId}', function ($ownerId) {
    $owner = getOwner($ownerId);
    $sort = request('sort') ?? 'date-desc';
    $listings = getOwnerListings($ownerId, $sort);
    return view('owners.show')->with('owner', $owner)->with('listings', $listings)->with('sort', $sort);
});
