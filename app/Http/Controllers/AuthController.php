<?php

namespace App\Http\Controllers;

use App\ActivationCode;
use App\Events\NewUserRegistered;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\auth\NewUserRegisterRequest;
use App\Http\Requests\auth\ResendActivationCodeRequest;
use App\Http\Requests\auth\VerifyUserMobileRequest;
use App\Services\UserService;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(newUserRegisterRequest $request)
    {
        return UserService::register($request);
    }

    /**
     * @param $code
     * @return string
     * @throws Exception
     */

    public function verifyUserEmail($code)
    {
        return UserService::verifyUserEmail($code);
    }

    public function verifyUserMobile(VerifyUserMobileRequest $request)
    {
        return UserService::verifyUserMobile($request);
    }

    public function resendActivationCode(ResendActivationCodeRequest $request)
    {
        return UserService::resendActivationCode($request);
    }
}
