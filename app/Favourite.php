<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Favourite extends Pivot
{
    protected $guarded = [];
    protected $table = 'video_favourites';
}
