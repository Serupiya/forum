<?php

namespace Forum\Models;



class PostModel extends BaseModel
{
    public $username, $date, $message, $email;

    public function __construct($username = null, $date = null, $message = null, $email = null,  $id = null)
    {
        $this->username = $username;
        $this->date = $date;
        $this->message = $message;
        $this->email = $email;
        $this->id = $id;
    }
}