<?php

namespace App\Http\Controllers;

use App\ActivationCode;
use App\Events\NewUserRegistered;
use App\Http\Requests\auth\ChangeEmailRequest;
use App\Http\Requests\auth\ChangePasswordRequest;
use App\Http\Requests\user\DeleteUserRequest;
use App\Services\UserService;
use App\User;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function changeEmail(ChangeEmailRequest $request)
    {
        return UserService::changeEmail($request);
    }

    public function verifyChangeEmail($code)
    {
        return UserService::verifyChangeEmail($code);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return UserService::changePassword($request);
    }

    public function delete(DeleteUserRequest $request)
    {
        return UserService::delete($request);
    }
}
