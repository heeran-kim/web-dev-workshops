<?php
/**
 * @file: clear_session.php
 * @description: This file clears the posts array from the session and redirects back to the index page.
 *               It is used to reset the session data related to posts.
 * 
 * @author: Heeran Kim
 * @date: 07-AUG-2024
 */
    session_start();

    // Clear the posts array from the session
    unset($_SESSION['posts']);

    // Redirect back to the index page
    header('Location: index.php');
    exit();
?>