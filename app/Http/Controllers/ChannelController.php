<?php

namespace App\Http\Controllers;

use App\Http\Requests\channel\ChannelUpdateRequest;
use App\Http\Requests\channel\FollowChannelRequest;
use App\Http\Requests\channel\UnFollowChannelRequest;
use App\Http\Requests\channel\UpdateSocialsRequest;
use App\Http\Requests\channel\uploadBannerRequest;
use App\Services\ChannelService;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function update(ChannelUpdateRequest $request)
    {
        return ChannelService::channelUpdate($request);
    }

    public function uploadBanner(UploadBannerRequest $request)
    {
        return ChannelService::UploadBanner($request);
    }

    public function updateSocials(UpdateSocialsRequest $request)
    {
        return ChannelService::updateSocials($request);
    }

    public function follow(FollowChannelRequest $request)
    {
        return ChannelService::follow($request);
    }

    public function unFollow(UnFollowChannelRequest $request)
    {
        return ChannelService::unFollow($request);
    }

    public function followers()
    {
        return ChannelService::followers();
    }

    public function followings()
    {
        return ChannelService::followings();
    }

    public function statistics()
    {
        return ChannelService::statistics();
    }
}
