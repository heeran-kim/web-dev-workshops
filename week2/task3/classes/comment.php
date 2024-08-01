<?php
namespace wad;

/* A class that contains a user name and a comment. */
class Comment{
    private $user;
    private $message;

    // constructor
    function __construct($user, $message){
        $this->user = $user;
        $this->message = $message;
    }

    // retrieve the user name
    function getUser(){
        return $this->user;    
    }

    // retrieve the message
    function getMessage(){
        return $this->message;    
    }
}
?>