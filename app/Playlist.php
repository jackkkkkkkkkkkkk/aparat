<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use SoftDeletes;
    protected $table = 'playlists';
    protected $guarded = [];

    public function video()
    {
        return $this->belongsToMany(Video::class);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['count'] = $this->video()->count();
        return $data;
    }
}
