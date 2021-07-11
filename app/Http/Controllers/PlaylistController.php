<?php

namespace App\Http\Controllers;

use App\Http\Requests\playlist\AddVideoToPlaylistRequest;
use App\Http\Requests\playlist\CreatePlaylistRequest;
use App\Http\Requests\playlist\ShowPlaylistRequest;
use App\Http\Requests\playlist\SortPlaylistVideosRequest;
use App\Services\PlaylistService;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function getAllPlaylists()
    {
        return PlaylistService::getAllPlaylists();
    }

    public function getMyPlaylists()
    {
        return PlaylistService::getMyPlaylist();
    }

    public function create(CreatePlaylistRequest $request)
    {
        return PlaylistService::create($request);
    }

    public function addVideo(AddVideoToPlaylistRequest $request)
    {
        return PlaylistService::addVideo($request);
    }

    public function sortVideos(SortPlaylistVideosRequest $request)
    {
        return PlaylistService::sortVideos($request);
    }

    public function show(ShowPlaylistRequest $request)
    {
        return PlaylistService::show($request);
    }
}
