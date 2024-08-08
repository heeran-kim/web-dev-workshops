<?php
namespace wad;
use wad\Comment;
require 'comment.php';

/* A class that defines Posts */
class Post{

    // Member variables
    // type declaration in object properties
    private string $user;
    private string $message;
    private string $image;
    private string $date;
    private array $comments;
    
    // constructor
    function __construct(string $user, string $message, string $image, string $date){
        $this->user = $user;
        $this->message = $message;
        $this->image = $image;
        $this->date = $date;
        $this->comments = [];
    }

    // add a comment for a post
    function addComment(string $user, string $message){
        $this->comments[] = new Comment($user, $message);
    }

    // get user name
    function getUser(): string{
        return $this->user;
    }

    // get message
    function getMessage(): string{
        return $this->message;
    }

    // get image src
    function getImage(): string{
        return $this->image;
    }

    // get date
    function getDate(): string{
        return $this->date;
    }

    // get comments
    function getComments(): array{
        return $this->comments;
    }
}
?>