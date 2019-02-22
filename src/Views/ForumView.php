<?php

namespace Forum\Views;


class ForumView extends BaseView
{
    public function __construct()
    {
        parent::__construct("forum.html");
    }

    public function render(array $posts, array $validationErrors){
        $this->_render(["posts" => $posts, "validationErrors" => $validationErrors]);
    }

}