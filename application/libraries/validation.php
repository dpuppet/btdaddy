<?php
class Validation
{
    public function __construct($config = array())
    {

    }

    public function password_validator($password)
    {
        return  preg_match("/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,}$/",$password) ? true : false;
    }
}