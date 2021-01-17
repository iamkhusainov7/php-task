<?php

namespace App\Exception;

class ItemExistsException extends \Exception
{
    protected $message = "Item already already exists!";
}
