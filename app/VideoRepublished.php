<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoRepublished extends Pivot
{
    protected $table = 'video_republishes';
}
