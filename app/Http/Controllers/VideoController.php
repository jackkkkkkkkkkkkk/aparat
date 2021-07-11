<?php

namespace App\Http\Controllers;

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
use App\Services\VideoService;

class VideoController extends Controller
{
    public function upload(UploadVideoRequest $request)
    {
        return VideoService::upload($request);
    }

    public function createVideo(CreateVideoRequest $request)
    {
        return VideoService::createVideo($request);
    }

    public function uploadBanner(UploadVideoBannerRequest $request)
    {
        return VideoService::uploadBanner($request);
    }

    public function changeState(ChangeVideoStateRequest $request)
    {
        return VideoService::changeState($request);
    }

    public function republish(RepublishVideoRequest $request)
    {
        return VideoService::republish($request);
    }

    public function list(VideoListRequest $request)
    {
        return VideoService::list($request);
    }

    public function like(LikeVideoRequest $request)
    {
        return VideoService::like($request);
    }

    public function likedList()
    {
        return VideoService::likedList();
    }

    public function show(ShowVideoRequest $request)
    {
        return VideoService::show($request);
    }

    public function delete(DeleteVideoRequest $request)
    {
        return VideoService::delete($request);
    }

    public function statistics(VideoStatisticsRequest $request)
    {
        return VideoService::statistics($request);
    }

    public function update(UpdateVideoRequest $request)
    {
        return VideoService::update($request);
    }
}
