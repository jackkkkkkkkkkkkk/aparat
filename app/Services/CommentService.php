<?php


namespace App\Services;


use App\Comment;
use App\Http\Requests\comment\ChangeCommentStateRequest;
use App\Http\Requests\comment\CommentListRequest;
use App\Http\Requests\comment\CreateCommentRequest;
use App\Http\Requests\comment\DeleteCommentRequest;
use App\Video;
use Illuminate\Support\Facades\DB;

class CommentService extends BaseService
{

    public static function list(CommentListRequest $request)
    {
        $userId = auth()->user()->id;
        $state = $request->state;
        $comments = Comment::userVideosComments($userId, $state)->get();
        return $comments;
    }

    public static function create(CreateCommentRequest $request)
    {
        $user = auth()->user();
        $video = Video::find($request->video_id);
        $comment = $user->comment()->create([
            'video_id' => $video->id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
            'state' => $video->user_id == $user->id ? Comment::STATE_ACCEPTED : Comment::STATE_PENDING
        ]);
        return $comment;
    }

    public static function changeState(ChangeCommentStateRequest $request)
    {
        $comment = $request->comment->update([
            'state' => $request->state
        ]);
        return $comment;
    }

    public static function delete(DeleteCommentRequest $request)
    {
        try {
            DB::beginTransaction();
            $request->comment->delete();
            DB::commit();
            return response(['message' => 'کامنت با موفیت حذف شد']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
