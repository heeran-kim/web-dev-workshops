<?php
namespace wad;
use wad\Post;
require 'post.php';

/* A class that create mock data of class Post for testing */
class PostSeeder{
    // No constructor needed as it only has static methods
    public static function seed(){
        // Create posts
        $posts = [];
        $posts[] = new Post('Amy', 'The First Cat', 'images/cat1.jpeg', '01/07/2024');
        $posts[] = new Post('Bryan', 'The Second Cat', 'images/cat2.jpeg', '02/07/2024');
        $posts[] = new Post('Carl', 'The Third Cat', 'images/cat3.jpeg', '03/07/2024');

        // Add comments to posts
        $posts[0]->addComment('Dave', 'Amy! Cute cat!');
        $posts[1]->addComment('Emma', 'Bryan! Lovely!');
        $posts[2]->addComment('Frank', 'Carl! Nice picture!');
        
        return $posts;
    }
}
?>