<?php

namespace App\Http\Controllers;

use App\Http\Requests\comment\ChangeCommentStateRequest;
use App\Http\Requests\comment\CommentListRequest;
use App\Http\Requests\comment\CreateCommentRequest;
use App\Http\Requests\comment\DeleteCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function list(CommentListRequest $request)
    {
        return CommentService::list($request);
    }

    public function create(CreateCommentRequest $request)
    {
        return CommentService::create($request);
    }

    public function changeState(ChangeCommentStateRequest $request)
    {
        return CommentService::changeState($request);
    }

    public function delete(DeleteCommentRequest $request)
    {
        return CommentService::delete($request);
    }
}
