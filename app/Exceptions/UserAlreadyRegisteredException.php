<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyRegisteredException extends Exception
{
    public function render()
    {
        return response(['message'=>'کاربر قبلا ثبت نام کرده است']);
    }
}
