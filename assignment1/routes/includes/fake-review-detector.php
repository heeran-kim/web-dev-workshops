<?php

use Illuminate\Support\Facades\DB;


function _checkHighFrequencyReviews ($timeFrame, $reviewLimit, $userName) {
    $sql = "
        SELECT
            COUNT(*)    AS count
        FROM
            Reviews     AS R
        WHERE
            user_name = ? AND
            date >= ?
    ";

    $reviewsInTimeFrame = DB::select($sql, array($userName, now()->subMinutes($timeFrame)->format('Y-m-d H:i:s')));

    if (($reviewsInTimeFrame[0]->count + 1) > $reviewLimit) {
        return false;
    }

    return true;
};

function _checkExtremeReviews ($ratingThreshold, $minReviews, $userName) {

    $sql = "
        SELECT
            COUNT(*)    AS totalCount
        FROM
            Reviews     AS R
        WHERE
            user_name = ?
    ";

    $totalCount = DB::select($sql, array($userName));
    $totalCount = $totalCount[0]->totalCount;

    if (($totalCount + 1) >= $minReviews){
        $sql = "
            SELECT
                COUNT(*)    AS extremeCount
            FROM
                Reviews     AS R
            WHERE
                user_name = ? AND
                rating in (1, 5)
        ";
    
        $extremeCount = DB::select($sql, array($userName));
        $extremeCount = $extremeCount[0]->extremeCount;
    
        // Calculate the percentage of extreme ratings (1 or 5)
        if ($totalCount > 0) {
            $extremeReviewRatio = $extremeCount / $totalCount;
    
            if ($extremeReviewRatio >= $ratingThreshold) {
                return false;
            }
        }
    }

    return true;
}

function _isSimilarReview ($newReview, $existingReview, $similarityThreshold) {
    $newReview = preg_replace('/[^a-zA-Z\s]/', '', strtolower($newReview));
    $existingReview = preg_replace('/[^a-zA-Z\s]/', '', strtolower($existingReview));

    $newReviewWords = explode(' ', $newReview);
    $existingReviewWords = explode(' ', $existingReview);

    $commonWords = array_intersect($newReviewWords, $existingReviewWords);

    $similarity = count($commonWords) / count($newReviewWords);

    return $similarity >= $similarityThreshold;
}

function _checkSimilarity ($similarityThreshold, $newReview) {
    $sql = "
        SELECT
            R.review    AS review
        FROM
            Reviews     AS R
    ";

    $reviews = DB::select($sql);

    foreach ($reviews as $review) {
        if (_isSimilarReview($newReview, $review->review, $similarityThreshold)){
            return false;
        }
    }

    return true;
}

function _checkOtherOwnerMentioned($userName, $review) {
    $sql = "
        SELECT
            O.name  AS name
        FROM
            Owners  AS O
        WHERE
            name != ?
    ";

    $otherOwners = DB::select($sql, array($userName));

    foreach ($otherOwners as $owner) {
        if (stripos($review, $owner->name) !== false) {
            return false;
        }
    }

    return true;
}


function _detectFakeReview ($formFields) {
    // Check if this user posted more than 'reviewLimit' reviews within 'timeFrame' minutes
    $timeFrame = 1;
    $reviewLimit = 2;

    if (!_checkHighFrequencyReviews($timeFrame, $reviewLimit, $formFields['userName'])){
        return "Fake review suspected: Too many reviews in a short period.";
    };
    
    // Check if 'ratingThreshold'% of this user's reviews are either 1 or 5, but only if they have at least 'minReviews' reviews.
    $ratingThreshold = 0.75;
    $minReviews = 4;

    if (!_checkExtremeReviews($ratingThreshold, $minReviews, $formFields['userName'])){
        return "Fake review suspected: Too many of the user's reviews are either 1 or 5 stars.";
    }
    
    // Check if 'similarityThreshold'% or more of the review content is similar to existing reviews
    $similarityThreshold = 0.8;

    if (!_checkSimilarity($similarityThreshold, $formFields['review'])){
        return "Fake review suspected: The review content is too similar to other existing reviews.";
    }

    // Check if the review mentions another owner
    if (!_checkOtherOwnerMentioned($formFields['userName'], $formFields['review'])){
        return "Fake review suspected: The review mentions another owner.";
    }
};
?>