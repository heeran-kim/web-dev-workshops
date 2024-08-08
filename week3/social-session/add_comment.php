<?php
/**
 * @file: add_comment.php
 * @description: This is an action script that handles the addition of comments to an existing post.
 *               It retrieves the post data from the session, adds the new comment,
 *               updates the session data, and redirects back to the index page.
 * 
 * @author: Heeran Kim
 * @date: 07-AUG-2024
 */
    require 'classes/postSeeder.php';
    
    session_start();

    // Retrieve the posts array from the session
    $posts = $_SESSION['posts'];

    // Add the new comment using the comment value from the GET request
    // to the specified post using the index value from the GET request
    $name = $_GET["name"] ?? "John";
    $index = $_GET["index"] ?? "";
    $comment = $_GET['comment'] ?? "";

    if (!empty($name) && !empty($index) && !empty($comment)){
        $posts[$index]->addComment($name, "{$comment}");
    }
    
    // Update the posts array in the session
    $_SESSION['posts'] = $posts;

    // Redirect back to the calling  page.
    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");
    exit();
?>