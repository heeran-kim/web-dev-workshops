<?php
namespace wad;
use wad\Comment;
require 'comment.php';

/* A class that defines Posts */
class Post{

    // Member variables
    private $user;
    private $message;
    private $image;
    private $date;
    private $comments;
    
    // constructor
    function __construct($user, $message, $image, $date){
        $this->user = $user;
        $this->message = $message;
        $this->image = $image;
        $this->date = $date;
        $this->comments = [];
    }

    // add a comment for a post
    function addComment($user, $message){
        $this->comments[] = new Comment($user, $message);
    }

    // get user name
    function getUser(){
        return $this->user;
    }

    // get message
    function getMessage(){
        return $this->message;
    }

    // get image src
    function getImage(){
        return $this->image;
    }

    // get date
    function getDate(){
        return $this->date;
    }

    // get comments
    function getComments(){
        return $this->comments;
    }
}
?>