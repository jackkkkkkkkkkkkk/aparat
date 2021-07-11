<?php

namespace App\Http\Controllers;

use App\Http\Requests\tag\createTagRequest;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getAllTags()
    {
        return TagService::getAllTags();
    }

    public function createTag(createTagRequest $request)
    {
        return TagService::createTag($request);
    }
}
