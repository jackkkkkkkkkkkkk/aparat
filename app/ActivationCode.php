<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    protected $table = 'activation_codes';
    protected $fillable = [
        'user_id', 'code', 'used', 'expire_time', 'type','email','phone'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
