<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;
    protected $table = 'users';
    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';
    const TYPES = [self::TYPE_ADMIN, self::TYPE_USER];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'avatar', 'verifyemail', 'type', 'verifymobile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     * @return User
     */
    public function findForPassport($username)
    {
        return $this->withTrashed()->where('email', $username)->orWhere('mobile', to_valid_mobile_number($username))->first();
    }

    public function createActivationCode($type, $email, $phone)
    {
        $activationCode = $this->activationCode()->create(
            [
                'code' => ($type == 'mobile' || $type == 'mobile-change') ? create_random_activation_code() : str_replace('/', '', bcrypt(create_random_activation_code())),
                'type' => $type,
                'email' => $email,
                'phone' => $phone,
                'expire_time' => now()->addMinutes(30)
            ]
        );
        return $activationCode->code;
    }

    public function activationCode()
    {
        return $this->hasMany(ActivationCode::class);
    }


    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = to_valid_mobile_number($value);
    }

    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function video()
    {
        return $this->channelVideo()->union($this->republishedVideo());
    }

    public function channelVideo()
    {
        return $this->hasMany(Video::class)->selectRaw('*,0 as republished');
    }

    public function republishedVideo()
    {
        return $this->belongsToMany(Video::class, 'video_republishes')->selectRaw('videos.*,1 as republished');
    }
//    public function republishedVideo()
//    {
//        return $this->hasManyThrough(Video::class, VideoRepublished::class, 'user_id', 'id', 'id', 'video_id');
//    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function playlist()
    {
        return $this->hasMany(Playlist::class);
    }

    public function likedVideo()
    {
        return $this->belongsToMany(Video::class, 'video_favourites');
    }

    public function follower()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id2', 'user_id1', 'id', 'id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id1', 'user_id2', 'id', 'id');
    }

    public function view()
    {
        return $this->belongsToMany(Video::class, 'video_views');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
            /** @var User $user */
            $user->playlist()->delete();
            $user->channelVideo()->delete();
        });
        static::restoring(function ($user) {
            /** @var User $user */
            $user->playlist()->restore();
            $user->channelVideo()->restore();
        });
    }
}
