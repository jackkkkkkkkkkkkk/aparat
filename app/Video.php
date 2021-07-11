<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use SoftDeletes;
    protected $table = 'videos';
    protected $guarded = [];
    protected $with = ['playlist'];
    const STATE_PENDING = 'pending';
    const STATE_CONVERTED = 'converted';
    const STATE_ACCEPTED = 'accepted';
    const STATE_BLOCKED = 'blocked';
    const STATES = [
        self::STATE_PENDING,
        self::STATE_CONVERTED,
        self::STATE_ACCEPTED,
        self::STATE_BLOCKED,
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data['link'] = $this->video_link;
        $data['banner_link'] = $this->banner_link;
        $data['views'] = ViewVideo::where('video_id', $this->id)->count();
        return $data;
    }

    public function getVideoLinkAttribute()
    {
        try {
            $url = Storage::disk('video')->url($this->slug . '.mp4');
            return $url;
        } catch (FileNotFoundException $e) {
            return response(['فایل وجود ندارد'], 500);
        }
    }

    public function getBannerLinkAttribute()
    {
        try {
            $url = asset('/img/favicon.ico');
            if ($this->banner) {
                $t = strtotime($this->updated_at);
                $url = Storage::disk('video')->url($this->banner) . '?t=' . $t;
            }
            return $url;
        } catch (FileNotFoundException $e) {
            return response(['فایل وجود ندارد'], 500);
        }

    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function playlist()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function isAccepted()
    {
        return $this->isInState(self::STATE_ACCEPTED);
    }

    private function isInState(string $state)
    {
        return $this->state == $state;
    }

    public static function scopeRepublishedVideos($query)
    {
        $query->whereIn('id', VideoRepublished::all()->pluck('video_id')->toArray());
    }

    public static function scopeNotRepublishedVideos($query)
    {
        $query->whereNotIn('id', VideoRepublished::all()->pluck('video_id')->toArray());
    }

    /**
     * @param $query
     * @param $userId
     * @param null $videoId
     * @param int $fromDate // must be in days
     */
    public static function scopeStatisticsForUserVideosViews($query, $userId, $videoId = null, int $fromDate = null)
    {
        $query->selectRaw('date(video_views.created_at) as date,Count(*) as views ,videos.user_id as userId')
            ->join('video_views', 'videos.id', "=", 'video_views.video_id')
            ->where('videos.user_id', $userId)
            ->groupBy(DB::raw('date(video_views.created_at)'));
        if ($videoId) {
            $query->where('videos.id', $videoId);
        }

        if ($fromDate !== null) {
            $fromDate = now()->subDays($fromDate)->toDateString();
            $query->whereRaw("date(video_views.created_at) >= '{$fromDate}'");
        }
    }

    public static function scopeStatisticsForUserVideosComments($query, $userId)
    {
        $query->selectRaw('Count(*) as commentsCount ,videos.user_id as userId')
            ->join('comments', 'videos.id', "=", 'comments.video_id')
            ->where('videos.user_id', $userId);
    }

    public function viewer()
    {
        return $this->belongsToMany(User::class, 'video_views');
    }

    public function userLiked()
    {
        return $this->belongsToMany(User::Class, 'video_favourites');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeRelatedVideos($query)
    {
        $query->selectRaw('videos.*,count(videos.id) as related_tags')
            ->join('tag_video', 'videos.id', '=', 'tag_video.video_id')
            ->whereIn('tag_video.tag_id', static::selectRaw('tag_video.tag_id')
                ->join('tag_video', 'videos.id', '=', 'tag_video.video_id')
                ->where('videos.id', $this->id)
            )
            ->where('videos.id', '<>', $this->id)
            ->groupBy('videos.id')
            ->orderBy('related_tags', 'desc');
    }
}
