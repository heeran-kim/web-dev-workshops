<?php

use Illuminate\Support\Facades\DB;

/**
 * This file provides several functions to detect suspicious or fake reviews based on various criteria:
 * - Review Frequency:              Detects if a user has created or edited an excessive number of reviews in a short period,
 *                                  which could indicate spamming or automated reviews.
 * - Extreme Ratings:               Identifies users who frequently leave 1-star or 5-star reviews, which may point to biased or unreliable reviews.
 *                                  This check only applies if the user has a sufficient number of reviews.
 * - Content Similarity:            Flags reviews that are too similar to existing reviews, indicating possible duplication or spam.
 * - Mentions of Another Owner:     Detects reviews that mention a different owner, which could suggest an attempt to mislead or harm a competitor.
 * 
 * These functions are used to analyse review data and prevent potential abuse of the review system by identifying patterns consistent with fake or unreliable reviews.
 */


// Private functions
#region
/**
 * Checks whether the user has created or edited more than a specified number of reviews within a given timeframe.
 * 
 * @param   int         $timeFrame     The timeframe in minutes within which to check for high review activity.
 * @param   int         $reviewLimit   The maximum allowed number of review actions within the timeframe.
 * @param   string      $userName      The username of the person whose review activity is being checked.
 * 
 * @return  bool        Returns false if the user exceeds the review limit in the given timeframe, otherwise true.
 */
function _checkHighFrequencyReviews($timeFrame, $reviewLimit, $userName) {
    // Note: 'created_at' is updated during both creation and editing of reviews.
    $sql = "
        SELECT
            COUNT(*)    AS count
        FROM
            Reviews     AS R
        WHERE
            R.user_name = ? AND
            R.created_at >= ?
    ";

    $reviewsInTimeFrame = DB::select($sql, array($userName, now()->subMinutes($timeFrame)->format('Y-m-d H:i:s')));

    // Adding 1 because the current review action (creation or edit) is not yet included in the count.
    if (($reviewsInTimeFrame[0]->count + 1) > $reviewLimit) {
        return false;
    }

    return true;
};

/**
 * Checks whether the percentage of extreme (1-star or 5-star) reviews exceeds the allowed threshold.
 *
 * @param   float       $ratingThreshold    The threshold percentage at which extreme reviews are considered excessive.
 * @param   int         $minReviews         The minimum number of reviews required before the check is applied.
 * @param   string      $userName           The username whose reviews are being evaluated.
 * @param   int         $currentRating      The rating of the current review (used for both creation and editing).
 * @param   int|null    $reviewId           The ID of the review being edited (null if this is a new review).
 *
 * @return  bool        Returns true if the user's extreme reviews are within the acceptable threshold, false otherwise.
 */
function _checkExtremeReviews($ratingThreshold, $minReviews, $userName, $currentRating, $reviewId) {
    // Retrieves the total number of reviews for the user
    $sql = "
        SELECT
            COUNT(*)    AS totalCount
        FROM
            Reviews     AS R
        WHERE
            R.user_name = ?
    ";

    $totalCount = DB::select($sql, array($userName));
    $totalCount = $totalCount[0]->totalCount;

    // If this is a new review, increment total count
    $totalCount += $reviewId ? 0 : 1;

    // If the user has less than the required number of reviews, skip the check
    if ($totalCount < $minReviews){
        return true;
    }

    // Retrieves the number of extreme (1-start and 5-star) reviews for the user
    $sql = "
        SELECT
            COUNT(*)    AS extremeCount
        FROM
            Reviews     AS R
        WHERE
            R.user_name = ? AND
            R.rating in (1, 5)
    ";

    $extremeCount = DB::select($sql, array($userName));
    $extremeCount = $extremeCount[0]->extremeCount;

    // Add 1 to extremeCount if the current rating is 1 or 5
    if (in_array($currentRating, [1,5])){
        $extremeCount++;
    }

    // Calclate the ratio of extreme reviews
    $extremeReviewRatio = $extremeCount / $totalCount;

    // Return false if the ratio exceeds the threshold
    if ($extremeReviewRatio >= $ratingThreshold) {
        return false;
    }

    return true;
}

/**
* Checks how similar the new review is to an existing one by calculating the ratio of common words.

* @param   string       $newReview           The content of the new review.
* @param   string       $existingReview      The content of the existing review.
* @param   float        $similarityThreshold The threshold at which a review is considered too similar.

* @return  bool         Returns true if the similarity ratio exceeds the threshold, otherwise false.
*/
function _isSimilarReview($newReview, $existingReview, $similarityThreshold) {
    // Clean both reviews by removing non-alphabetic characters and converting to lowercase.
    $newReview = preg_replace('/[^a-zA-Z\s]/', '', strtolower($newReview));
    $existingReview = preg_replace('/[^a-zA-Z\s]/', '', strtolower($existingReview));

    // Convert both reviews to arrays of words.
    $newReviewWords = explode(' ', $newReview);
    $existingReviewWords = explode(' ', $existingReview);

    // Find common words between the two reviews.
    $commonWords = array_intersect($newReviewWords, $existingReviewWords);

    // Calculate the similarity ratio.
    $similarity = count($commonWords) / count($newReviewWords);

    return $similarity >= $similarityThreshold;
}

/**
* Checks if the new review is too similar to any existing reviews.
*
* @param   float        $similarityThreshold The threshold at which a review is considered too similar.
* @param   string       $newReview           The content of the new review.
* @param   int|null     $reviewId            The ID of the review being edited (null if this is a new review).
* @return  bool         Returns false if the new review is too similar to any existing review, otherwise true.
*/
function _checkSimilarity($similarityThreshold, $newReview, $reviewId) {
    $sql = "
        SELECT
            R.review_text   AS reviewText
        FROM
            Reviews         AS R
        WHERE
            (? IS NULL OR R.id != ?)
    ";

    $reviews = DB::select($sql, array($reviewId));

    foreach ($reviews as $review) {
        // Compare the new review with each existing reviews.
        if (_isSimilarReview($newReview, $review->reviewText, $similarityThreshold)){
            return false;
        }
    }

    return true;
}

/**
* Retrieves the owner’s name for the given listing.
* 
* @param   int     $listingId   The ID of the listing.
* @return  string  Returns the owner’s name for the specified listing.
*/
function _getOwnerName($listingId) {
    $sql = "
        SELECT
            owner_name AS ownerName
        FROM
            Listings
        WHERE
            id = ?
    ";

    $listings = DB::select($sql, array($listingId));

    return $listings[0]->ownerName;
}

/**
* Checks if the review mentions any other owner, which may suggest biased or misleading content.
*
* @param   string  $ownerName   The name of the current owner.
* @param   string  $reviewText  The content of the review.
* @return  bool    Returns false if another owner is mentioned, otherwise true.
*/
function _checkOtherOwnerMentioned($ownerName, $reviewText) {
    $sql = "
        SELECT
            O.name  AS name
        FROM
            Owners  AS O
        WHERE
            O.name != ?
    ";

    $otherOwners = DB::select($sql, array($ownerName));

    foreach ($otherOwners as $owner) {
        // Check if any other owner's name is mentioned in the review.
        if (stripos($reviewText, $owner->name) !== false) {
            return false;
        }
    }

    return true;
}
#endregion

// Public function
/**
* Main function to detect potential fake reviews based on multiple factors such as frequency,
* extreme ratings, content similarity, and mentions of other owners.
*
* @param   array        $formFields     The data from the review creation or edit form.
* @param   int          $listingId      The ID of the listing for the review.
* @param   int|null     $reviewId       The ID of the review being edited (null if this is a new review).
* @return  string       Returns a message if a fake review is suspected, otherwise nothing.
*/
function detectFakeReview ($formFields, $listingId, $reviewId) {
    // Check if this user created or edited more than 'reviewLimit' reviews within 'timeFrame' minutes
    $timeFrame = 1;
    $reviewLimit = 5;

    if (!_checkHighFrequencyReviews($timeFrame, $reviewLimit, $formFields['userName'])){
        return "Fake review suspected: Too many review actions in a short period.";
    };
    
    // Check if 'ratingThreshold'% of this user's reviews are either 1 or 5, but only if they have at least 'minReviews' reviews.
    $ratingThreshold = 0.9;
    $minReviews = 4;

    if (!_checkExtremeReviews($ratingThreshold, $minReviews, $formFields['userName'], $formFields['rating'], $reviewId)){
        return "Fake review suspected: Too many of the user's reviews are either 1 or 5 stars.";
    }
    
    // Check if 'similarityThreshold'% or more of the review content is similar to existing reviews
    $similarityThreshold = 0.8;

    if (!_checkSimilarity($similarityThreshold, $formFields['reviewText'], $reviewId)){
        return "Fake review suspected: The review content is too similar to other existing reviews.";
    }

    // Check if the review mentions another owner
    $ownerName = _getOwnerName($listingId);
    if (!_checkOtherOwnerMentioned($ownerName, $formFields['reviewText'])){
        return "Fake review suspected: The review mentions another owner, possibly targeting a competitor.";
    }
};
?>