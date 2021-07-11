<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ViewVideo extends Pivot
{
    protected $table = 'video_views';
    protected $guarded=[];
}
