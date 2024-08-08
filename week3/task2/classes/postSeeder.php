<?php
namespace wad;
use wad\Post;
require 'post.php';

/* A class that create mock data of class Post for testing */
class PostSeeder{
    // No constructor needed as it only has static methods
    public static function seed(): array{
        // Create posts
        $posts = [];
        $posts['Post1'] = new Post('Amy', 'The First Cat', 'images/cat1.jpeg', '01/07/2024');
        $posts['Post2'] = new Post('Bryan', 'The Second Cat', 'images/cat2.jpeg', '02/07/2024');
        $posts['Post3'] = new Post('Carl', 'The Third Cat', 'images/cat3.jpeg', '03/07/2024');
        
        return $posts;
    }
}
?>