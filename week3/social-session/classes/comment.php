<?php
namespace wad;

/* A class that contains a user name and a comment. */
class Comment{
    private string $user;
    private string $message;

    // constructor
    function __construct(string $user, string $message){
        $this->user = $user;
        $this->message = $message;
    }

    // retrieve the user name
    function getUser(): string{
        return $this->user;    
    }

    // retrieve the message
    function getMessage(): string{
        return $this->message;    
    }
}
?>