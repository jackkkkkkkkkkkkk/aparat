<?php

namespace App;

use App\Http\Requests\channel\UpdateSocialsRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use SoftDeletes;
    protected $table = 'channels';
    protected $fillable = [
        'name', 'user_id', 'info', 'website', 'socials', 'banner'
    ];

    public function setSocialsAttribute($value)
    {
        $this->attributes['socials'] = json_encode($value);
    }

    public function getSocialsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
