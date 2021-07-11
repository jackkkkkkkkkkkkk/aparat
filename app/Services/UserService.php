<?php


namespace App\Services;


use App\ActivationCode;
use App\Events\NewUserRegistered;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\auth\changeEmailRequest;
use App\Http\Requests\auth\ChangePasswordRequest;
use App\Http\Requests\auth\NewUserRegisterRequest;
use App\Http\Requests\auth\ResendActivationCodeRequest;
use App\Http\Requests\auth\VerifyUserMobileRequest;
use App\Http\Requests\user\DeleteUserRequest;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class UserService extends BaseService
{
    public static function changeEmail(changeEmailRequest $request)
    {
        event(new NewUserRegistered(auth()->user(), 'email-change', $request->email));
        //send email
        return response('ایمیل  برای شما ارسال شد', 200);
    }

    public static function verifyChangeEmail($code)
    {
        $activationCode = ActivationCode::whereCode($code)->whereType('email-change')->where('used', 0)->where('expire_time', '>', Carbon::now())->first();
        if ($activationCode) {
            $activationCode->update([
                'used' => 1
            ]);
            $activationCode->user()->update([
                'email' => $activationCode->email
            ]);
            return response(['message' => 'ایمیل تغییر کرد']);
        }
        return response(['message' => 'کد معتبر نیست']);
    }

    public static function register(newUserRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();
            if (!$field) {
                throw new Exception('ورودی نامعتبر است');
            }
            $user = User::where($field, $value)->first();
            if (!$user) {
                $user = User::create([
                    $field => $value
                ]);
                event(new NewUserRegistered($user, $field));
            } else {
                //redirect login page
                throw new UserAlreadyRegisteredException('کاربر قبلا ثبت نام کرده است');
            }
            DB::commit();
        } catch (\Exception $e) {
            if ($e instanceof UserAlreadyRegisteredException) {
                throw $e;
            }
            DB::rollBack();
            return response(['message' => $e->getMessage()]);
        }
    }

    public static function verifyUserEmail($code)
    {
        $activationCode = ActivationCode::whereCode($code)->whereType('email')->first();
        if ($activationCode->expire_time < Carbon::now()) {
            throw new Exception('توکن اعتبار ندارد');
        }
        if ($activationCode->used) {
            throw new Exception('توکن اعتبار ندارد');
        }
        $activationCode->update([
            'used' => 1
        ]);
        $activationCode->user()->update([
            'verifyemail' => 1
        ]);
        return response(['message' => 'ایمیل با موفقیت تایید شد']);
    }

    public static function verifyUserMobile(VerifyUserMobileRequest $request)
    {
        $activationCode = ActivationCode::whereCode($request->code)->whereType('mobile')->first();
        if ($activationCode->expire_time < Carbon::now()) {
            throw new Exception('توکن اعتبار ندارد');
        }
        if ($activationCode->used) {
            throw new Exception('توکن اعتبار ندارد');
        }
        $activationCode->update([
            'used' => 1
        ]);
        $activationCode->user()->update([
            'verifymobile' => 1
        ]);
        return response(['message' => 'کاربر با موفقیت تایید شد'], 200);
    }

    public static function resendActivationCode(ResendActivationCodeRequest $request)
    {
        $field = $request->getFieldName();
        if (!$field) {
            throw new Exception('ورودی معتبر نیست');
        }
        $value = $request->getFieldValue();
        $user = User::where($field, $value)->first();
        if (!$user) {
            return response(['message' => "کاربر یافت نشد"], 200);
        }
        $activationCode = $user->activationCode()->where('expire_time', '>', Carbon::now())->where('used', 0)->first();
        if (!$activationCode) {
            event(new NewUserRegistered($user, $field));
            return response(['message' => 'کد فعال سازی ارسال شد'], 200);
        }
        return response(['message' => 'کد فعال سازی قبلا  ارسال شده است'], 200);
    }

    public static function changePassword(ChangePasswordRequest $request)
    {
        try {
            if (!Hash::check($request->input('old-password'), auth()->user()->password)) {
                return response(['message' => 'پسورد قدیمی نادرست است'], 400);
            }
            auth()->user()->update([
                'password' => bcrypt($request->input('new-password'))
            ]);
            return response(['message' => 'پسورد با موفقیت تغییر کرد']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public static function delete(DeleteUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $request->user()->delete();
            DB::commit();
            return response(['message' => 'کاربر با موفقیت حذف شد']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
