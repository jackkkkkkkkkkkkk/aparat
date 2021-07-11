<?php


namespace App\Services;


use App\Channel;
use App\Comment;
use App\Http\Requests\channel\ChannelUpdateRequest;
use App\Http\Requests\channel\FollowChannelRequest;
use App\Http\Requests\channel\UnFollowChannelRequest;
use App\Http\Requests\channel\UpdateSocialsRequest;
use App\Http\Requests\channel\UploadBannerRequest;
use App\Video;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ChannelService extends BaseService
{
    public static function channelUpdate(ChannelUpdateRequest $request)
    {
        try {
            if ($request->route()->hasParameter('id')) {
                $channelId = $request->route('id');
                $channel = Channel::findOrFail($channelId);
            } else {
                $channel = auth()->user()->channel;
            }
            $channel->update([
                'name' => $request->name,
                'info' => $request->info,
                'website' => $request->website
            ]);
            return response(['message' => 'کانال اپدیت شد']);
        } catch (\Exception $exception) {
            return response(['message' => $exception->getMessage()], 500);
        }
    }

    public static function UploadBanner(UploadBannerRequest $request)
    {
        try {
            $file = $request->file('banner');
            $fileName = auth()->user()->id . '-' . md5(Str::random());
            Storage::disk('channel-banners')->put($fileName, $file->get());
            $channel = auth()->user()->channel;
            if ($channel->banner) {
                unlink(ltrim(auth()->user()->channel->banner, URL::to('/')));
            }
            $channel->update([
                'banner' => url('/channel-banners/' . $fileName)
            ]);
            return response(['message' => 'بنر با موفقیت اپلود شد']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function updateSocials(UpdateSocialsRequest $request)
    {
        $socials = ['facebook', 'twitter', 'lenzor', 'telegram', 'cloob'];
        $data = [];
        foreach ($socials as $social) {
            $data[$social] = $request->has($social) ? $request->input($social) : null;
        }
        auth()->user()->channel->update([
            'socials' => $data
        ]);
        return response(['messaga' => 'با موفقیت اپدیت شد']);
    }

    public static function follow(FollowChannelRequest $request)
    {
        try {
            $followingUser = auth()->user();
//            $followingUser->following()->attach($request->channel->user->id);
            $request->channel->user->follower()->attach($followingUser->id);
            return response(['message' => 'با موفقیت دنبال شد']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function unFollow(UnFollowChannelRequest $request)
    {
        try {
            $user = auth()->user();
//            $user->following()->detach($request->channel->user->id);
            $request->channel->user->follower()->detach($user->id);
            return response(['message' => 'با موفقیت انفالو شد']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function followings()
    {
        $data = auth()->user()->following()->paginate();
        return response(['data' => $data]);
    }

    public static function followers()
    {
        $data = auth()->user()->follower()->paginate();
        return response(['data' => $data]);
    }

    public static function statistics()
    {
        try {
            $user = auth()->user();
            $comments = Video::statisticsForUserVideosComments($user->id)->get();
            $data = [
                'totalFollowers' => $user->follower()->count(),
                'totalComments' => $comments[0]->commentsCount,
                'totalViews' => 0
            ];
            $views = Video::statisticsForUserVideosViews($user->id)->get()
                ->each(function ($value) use (&$data) {
                    $data['totalViews'] += $value['views'];
                    $data['views'][$value['date']] = $value['views'];
                });
//            $totalViews = 0;
//            $newView = [];
//            foreach ($views as $v) {
//                $totalViews += $v->views;
//                $newView[$v->date]['views'] = $v->views;
//            }
//            $data = [
//                'views' => $newView,
//                'totalFollowers' => $user->follower()->count(),
//                'totalComments' => $comments[0]->commentsCount,
//                'totalViews' => $totalViews
//            ];
            return $data;
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
