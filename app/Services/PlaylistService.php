<?php


namespace App\Services;


use App\Category;
use App\Channel;
use App\Http\Requests\category\CategoryBannerUploadRequest;
use App\Http\Requests\category\CreateCategoryRequest;
use App\Http\Requests\channel\ChannelUpdateRequest;
use App\Http\Requests\channel\UpdateSocialsRequest;
use App\Http\Requests\playlist\AddVideoToPlaylistRequest;
use App\Http\Requests\playlist\CreatePlaylistRequest;
use App\Http\Requests\playlist\ShowPlaylistRequest;
use App\Http\Requests\playlist\SortPlaylistVideosRequest;
use App\Playlist;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PlaylistService extends BaseService
{

    public static function getAllPlaylists()
    {
        $playlists = Playlist::all();
        return response(['data' => $playlists]);
    }

    public static function getMyPlaylist()
    {
        $playlists = auth()->user()->playlist;
        return $playlists;
    }

    public static function create(CreatePlaylistRequest $request)
    {
        try {
            $playlist=auth()->user()->playlist()->create([
                'title' => $request->title
            ]);
            return $playlist;
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function addVideo(AddVideoToPlaylistRequest $request)
    {
        $video = $request->video;
        $video->playlist()->sync($request->playlist);
        return response(['message' => 'ویدیو با موفقیت به لیست پخش افزوده شد'], 500);
    }

    public static function sortVideos(SortPlaylistVideosRequest $request)
    {
        $request->playlist->video()->detach();
        $request->playlist->video()->attach($request->data);
        return response(['لیست پخش با موفقیت مرتب سازی شد'], 500);
    }

    public static function show(ShowPlaylistRequest $request)
    {
        return $request->playlist->with(['video'=> function ($query) {
            $query->orderBy('playlist_video.id', 'asc');
        }])->where('playlists.id', $request->playlist->id)->get();
    }
}
