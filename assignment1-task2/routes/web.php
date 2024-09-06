<?php

use Illuminate\Support\Facades\Route;
include "includes/crud.php";
include "includes/validator.php";
include "includes/fake-review-detector.php";

// Route definitions that handle HTTP requests for the application.
// Main actions: CRUD operations for Listings, Reviews, Owners
// Includes validation and fake review detection logic

// Listing Routes
#region
// Display all listings with optional sorting by date, rating, review count or rent
Route::get('/', function () {
    $sort = request('sort') ?? 'date-desc'; // Default sorting by newest first
    $listings = getListings($sort);
    return view('listings.index')->with('listings', $listings)->with('sort', $sort);
});

// Show the form for creating a new listing
Route::get('listings/create', function () {
    return view('listings/create');
});

// Store a new listing in the database after validating input
Route::post('listings', function () {
    // Retrieve and process form input
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    // Sanitize the owner's name by removing any digits.
    $alteredName = removeDigitsFromName($formFields['ownerName']);
    if ($alteredName){
        $formFields['ownerName'] = $alteredName;
    }
    
    // Validate form data
    $errorMessage = validateListingInput($formFields);
    if ($errorMessage) {
        // Redirect back with error messages and input data if validation fails
        return back()->with("errorMessage", $errorMessage)->with("formFields", $formFields);
    }

    // Add the owner to the database if they don't exist already
    createOwner($formFields['ownerName']);

    // Create the listing and store it in the database
    $listingId = createListing($formFields);

    // Redirect to the newly created listing page, inform if the owner name was altered
    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Show the form for editing an existing listing
Route::get('listings/{listingId}/edit', function ($listingId) {
    $listing = getListing($listingId);  // Retrieve the listing data
    return view('listings.edit')->with('listing', $listing);    // Pass the data to the view
});

// Update a specific listing in the database after validating the input
Route::put('listings/{listingId}', function ($listingId) {
    // Retrieve and process form input
    $formFields = request()->all();
    $formFields['isFurnished'] = request()->has('isFurnished');
    $formFields['isBillIncluded'] = request()->has('isBillIncluded');

    // Sanitize the owner's name by removing any digits.
    $alteredName = removeDigitsFromName($formFields['ownerName']);
    if ($alteredName){
        $formFields['ownerName'] = $alteredName;
    }
    
    // Validate form data
    $errorMessage = validateListingInput($formFields);
    if ($errorMessage) {
        // Redirect back with error messages and input data if validation fails
        return back()->with("errorMessage", $errorMessage)->with("formFields", $formFields);
    }

    // Update listing in the database
    updateListing($listingId, $formFields);

    // Redirect to the newly created listing page, inform if the owner name was altered
    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Delete a specific listing from the database, with different redirection based on the source
Route::delete('listings/{listingId}', function ($listingId) {
    // Delete the listing
    deleteListing($listingId);
    
    // If request includes ownerId, redirect to owner's listings page, else back to main listings page
    if (request()->has('ownerId')){
        $ownerId = request('ownerId');
        return redirect(url("owners/$ownerId"));
    }
    return redirect(url("/"));
});

// Display a specific listing and its reviews.
Route::get('listings/{listingId}', function ($listingId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);    // Retrieve associated reviews
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews);
});
#endregion

// Review Routes
#region
// Store a new review for a listing, with validation and fake review detection
Route::post('listings/{listingId}/reviews', function ($listingId) {
    // Input from the request
    $createFields = request()->all();

    // Sanitize the user's name by removing any digits.
    $alteredName = removeDigitsFromName($createFields['userName']);
    if ($alteredName){
        $createFields['userName'] = $alteredName;
    }
    
    // Validate review data
    $createError = validateCreateReviewInput($createFields, $listingId);
    if ($createError) {
        return back()->with("createError", $createError)->with("createFields", $createFields);
    }

    // Detect fake review
    $fakeReviewError = detectFakeReview($createFields, $listingId, null);
    if ($fakeReviewError){
        return back()->with("fakeReviewError", $fakeReviewError);
    }

    // Create the review
    createReview($listingId, $createFields);

    // Store the reviewer's name in the session for future use
    session(['userName' => $createFields['userName']]);

    // Redirect to the listing's page and inform the user if the name was changed.
    return redirect(url("listings/$listingId"))->with("alteredName", $alteredName);
});

// Show the form for editing an existing review.
Route::get('listings/{listingId}/reviews/{reviewId}/edit', function ($listingId, $reviewId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);
    $reviewToEdit = getReview($reviewId);   // Load the review being edited
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews)->with('reviewToEdit', $reviewToEdit);
});

// Update an existing review in the database after validation
Route::put('listings/{listingId}/reviews/{reviewId}', function ($listingId, $reviewId) {
    // Input from the request
    $editFields = request()->all();
    
    // Validate review data
    $editError = validateEditReviewInput($editFields, $listingId);
    if ($editError) {
        return back()->with("editError", $editError)->with("editFields", $editFields);
    }

    // Detect fake review
    $editFields['userName'] = getReview($reviewId)->userName;
    $fakeReviewError = detectFakeReview($editFields, $listingId, $reviewId);
    if ($fakeReviewError){
        return back()->with("fakeReviewError", $fakeReviewError);
    }
    
    // Update the review
    updateReview($listingId, $reviewId, $editFields);
    
    // Redirect to the listing's page
    return redirect(url("listings/$listingId"));
});

// Delete a specific review from the database.
Route::delete('listings/{listingId}/reviews/{reviewId}', function ($listingId, $reviewId) {
    deleteReview($listingId, $reviewId);
    return redirect(url("listings/$listingId"));
});
#endregion

// Owner Routes
#region
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

// Display a specific listing and its reviews for an owner
Route::get('owners/{ownerId}/listings/{listingId}', function ($ownerId, $listingId) {
    $listing = getListing($listingId);
    $listingReviews = getListingReviews($listingId);
    return view('listings.show')->with('listing', $listing)->with('reviews', $listingReviews)->with('ownerId', $ownerId);
});
#endregion