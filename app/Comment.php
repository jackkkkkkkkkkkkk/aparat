<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Comment extends Model
{
    use SoftDeletes;
    const STATE_PENDING = 'pending';
    const STATE_READ = 'read';
    const STATE_ACCEPTED = 'accepted';
    const STATES = [self::STATE_PENDING, self::STATE_READ, self::STATE_ACCEPTED];
    protected $guarded = [];

    public function comment()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public static function scopeUserVideosComments($query, $userId, $state = null)
    {
        /** @var Builder $query */
        $query->selectRaw('comments.*')
            ->join('videos', 'videos.id', '=', 'comments.video_id')
            ->where('videos.user_id', $userId);
        if ($state) {
            $query->where('comments.state', $state);
        }
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($comment) {
            $comment->comment()->delete();
        });
    }
}
