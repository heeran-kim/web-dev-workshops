<?php

/*
 * Validates that a name field is between 3-20 characters and doesn't contain forbidden symbols.
 * @usage   Used when validating form input during listing creation and editing (for title, owner name, city)
 *          and during review creation (for user name).
 * @return  bool Returns true if the name is valid, otherwise false.
 */
function _validateName ($name) {
    if (strlen($name) < 3 || strlen($name) > 20){
        return false;
    }
    
    $forbiddenChars = ['+', '-', '_', '"'];
    foreach ($forbiddenChars as $forbiddenChar) {
        if (strpos($name, $forbiddenChar)) {
            return false;
        }
    }

    return true;
}

/*
 * Validates that a number field is an integer.
 * @usage   Used when validating rent input during listing creation and editing.
 * @return  bool Returns true if the number is valid, otherwise false.
 */
function _validateNumber ($number) {
    if (!filter_var($number, FILTER_VALIDATE_INT)){
        return false;
    }
    return true;
}

/*
 * Checks if the user name for a review is unique to the listing.
 * @usage   Used when validating user name input during review creation
 *          to prevent duplicate reviewer names for the same listing.
 * @return  bool Returns true if a duplicate name is not found, otherwise false.
 */
function _checkDuplicateName ($name, $listingId) {
    $reviews = getListingReviews($listingId);
    
    foreach ($reviews as $review) {
        if ($review->userName === $name){
            return false;
        }
    }

    return true;
}

/*
 * Validates that a review has at least 3 words.
 * @usage   Used when validating review input during review creation and editing.
 * @return  bool Returns true if the review is valid, otherwise false.
 */
function _validateReviewText ($reviewText) {
    if (str_word_count($reviewText) < 3) {
        return false;
    }
    return true;
}


/*
 * Removes any digits from the provided name.
 * @usage   Used to sanitize the owner's name by removing digits during listing creation and editing.
 * @return  string|null Returns the name without digits, or null if no digits were found.
 */
function removeDigitsFromName ($name) {
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

/*
 * Validates input for creating or editing a listing, such as title, owner name, city, state, and rent.
 * @usage   Used during listing creation and editing to ensure that all required fields are valid.
 * @return  array|null Returns an array of error messages if any fields are invalid, or null if all input is valid.
 */
function validateListingInput ($formFields) {
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

/*
 * Validates input for creating a review, including user name, rating, and review length.
 * @usage   Used during review creation to ensure that the input is valid and the name is unique within the listing's reviews.
 * @return  string|null Returns the first encountered error message if the input is invalid, or null if all input is valid.
 */
function validateCreateReviewInput ($formFields, $listingId) {
    if (!_validateName($formFields['userName'])){
        return 'A name must be 3-20 characters long after removing numbers and cannot have the following symbols: -, _, +, ".';
    }

    if (!_checkDuplicateName($formFields['userName'], $listingId)){
       return 'User (with the same name) cannot post multiple reviews for the same item.';
    }

    if (!isset($formFields['rating'])){
        return 'A rating must be selected.';
    }

    if (!_validateReviewText($formFields['reviewText'])){
        return 'A review text must have a minimum of 3 words.';
    }

    return null;
}

/*
 * Validates input for editing a review, including rating and review length.
 * @usage   Used during review editing to ensure that the input is valid.
 * @return  string|null Returns the first encountered error message if the input is invalid, or null if all input is valid.
 */
function validateEditReviewInput ($formFields, $listingId) {
    if (!isset($formFields['rating'])){
        return 'A rating must be selected.';
    }

    if (!_validateReviewText($formFields['reviewText'])){
        return 'A review text must have a minimum of 3 words.';
    }

    return null;
}
?>