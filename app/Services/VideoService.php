<?php


namespace App\Services;


use App\Events\UploadNewVideo;
use App\Events\VideoDeleted;
use App\Events\VideoViewed;
use App\Favourite;
use App\Http\Requests\video\ChangeVideoStateRequest;
use App\Http\Requests\video\CreateVideoRequest;
use App\Http\Requests\video\DeleteVideoRequest;
use App\Http\Requests\video\LikeVideoRequest;
use App\Http\Requests\video\RepublishVideoRequest;
use App\Http\Requests\video\ShowVideoRequest;
use App\Http\Requests\video\UpdateVideoRequest;
use App\Http\Requests\video\UploadVideoBannerRequest;
use App\Http\Requests\video\UploadVideoRequest;
use App\Http\Requests\video\VideoListRequest;
use App\Http\Requests\video\VideoStatisticsRequest;
use App\Video;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VideoService extends BaseService
{
    public static function list(VideoListRequest $request)
    {
        $user = auth('api')->user();
        if ($request->has('republished')) {
            if ($user) {
                $videos = $request->republished ? $user->republishedVideo() : $user->channelVideo();
            } else {
                $videos = $request->republished ? Video::republishedVideos() : Video::notRepublishedVideos();
            }
        } else {
            $videos = $user ? $user->video() : Video::query();
        }

        return response($videos->get());
    }

    public static function show(ShowVideoRequest $request)
    {
        $video = $request->video;
        $likeCount = Favourite::where('video_id', $video->id)->count();
        $data = $video->toArray();
        $data['likeCount'] = $likeCount;
        $data['tags'] = $video->tag;
        $comments = $video->comment;
        $data['comments'] = comment_data($comments);
        // return $video->relatedVideos()->get();
        $data['related'] = $video->relatedVideos();
        event(new VideoViewed($video));
        return $data;
    }

    public static function upload(UploadVideoRequest $request)
    {
        try {
            $video = $request->file('video');
            $fileName = auth()->user()->id . '-' . md5(Carbon::now()->timestamp);
            Storage::disk('video')->put('temp/' . $fileName, $video->get());
            return response(['video' => $fileName]);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function uploadBanner(UploadVideoBannerRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName = auth()->user()->id . '-' . md5(Carbon::now()->timestamp) . '_banner';
            Storage::disk('video')->put('temp/' . $fileName, $banner->get());
            return response(['banner' => $fileName]);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function createVideo(CreateVideoRequest $request)
    {
        try {
            DB::beginTransaction();

            $video = auth()->user()->channelVideo()->create([
                "banner" => $request->banner_id,
                "category_id" => $request->category_id,
                "info" => $request->info,
                "title" => $request->title,
                "duration" => 0,
                "channel_category_id" => $request->channel_category_id,
                "publish_at" => $request->publish_at,
                "enable_comments" => $request->enable_comments,
                "slug" => ''
            ]);

            event(new UploadNewVideo($video, $request->all()));

            if ($request->tags) {
                $video->tag()->attach($request->tags);
            }


            if ($request->playlists) {
                $video->playlist()->attach($request->playlists);
            }

            DB::commit();
            return $video;
        } catch (\Exception $e) {

            DB::rollBack();
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function update(UpdateVideoRequest $request)
    {

        try {
            DB::beginTransaction();
            $video = $request->video;

            $video->update([
                "category_id" => $request->category_id,
                "info" => $request->info,
                "title" => $request->title,
                "channel_category_id" => $request->channel_category_id,
                "enable_comments" => $request->enable_comments,
                "enable_watermark" => $request->enable_watermark,
            ]);

            if ($request->banner_id) {
                Storage::disk('video')->delete($video->banner);
                Storage::disk('video')->move('/temp/' . $request->banner_id, $video->banner);
            }
            $video->tag()->sync($request->tags);
            $video->playlist()->sync($request->playlists);


            DB::commit();
            return $video;
        } catch (\Exception $e) {

            DB::rollBack();
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function changeState(ChangeVideoStateRequest $request)
    {
        $request->video->update([
            'state' => $request->state
        ]);
        return response(['message' => 'وضعیت با موفقیت تغییر کرد']);
    }

    public static function republish(RepublishVideoRequest $request)
    {
        try {
            auth()->user()->republishedVideo()->attach($request->video->id);
            return response(['message' => 'ویدیو با موفقیت باز نشر شد']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function like(LikeVideoRequest $request)
    {
        try {
            $video = $request->video;
            $user = auth('api')->user();
            if ($user) {
                $favourites = $user->likedVideo();
                $liked = $favourites->where('video_id', $video->id);
                if ($liked->count()) {
                    $favourites->detach($video->id);
                } else {
                    Favourite::create([
                        'video_id' => $video->id,
                        'user_id' => $user->id,
                        'user_ip' => client_ip()
                    ]);
                }
            } else {
                $liked = Favourite::where(['video_id' => $video->id, 'user_ip' => client_ip(), 'user_id' => null]);
                if ($liked->count()) {
                    $liked->delete();
                } else {
                    Favourite::create([
                        'video_id' => $video->id,
                        'user_ip' => client_ip()
                    ]);
                }
            }
            return response(['message' => 'عملیات با موفقیت انجام شد']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function likedList()
    {
        try {
            return response(['data' => auth()->user()->likedVideo()->paginate(10)]);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function delete(DeleteVideoRequest $request)
    {
        if ($request->video->user_id !== auth()->user()->id) {
            auth()->user()->republishedVideo()->detach($request->video->id);
            return response(['message' => 'با موفقیت حذف شد']);
        }
        $request->video->forceDelete();
        //delete video and banner using event
        event(new VideoDeleted($request->video));
        return response(['message' => 'ویدیو با موفقیت حذف شد']);
    }

    public static function statistics(VideoStatisticsRequest $request)
    {
        $data = [
            'totalViews' => 0
        ];
        Video::statisticsForUserVideosViews($request->user()->id, $request->video->id, $request->last_n_days)->get()
            ->each(function ($value) use (&$data) {
                $data['totalViews'] += $value['views'];
                $data['views'][$value['date']] = $value['views'];
            });
        return $data;
    }


}
