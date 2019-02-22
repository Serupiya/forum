<?php

namespace Forum\Models;


class BaseModel
{
    public $id;

    public function getValues() : array{
        return get_object_vars($this);
    }

}