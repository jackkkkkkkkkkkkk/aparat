<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';
    protected $fillable = ['icon', 'title', 'banner','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
