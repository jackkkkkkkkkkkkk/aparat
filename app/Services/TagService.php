<?php


namespace App\Services;


use App\Http\Requests\tag\createTagRequest;
use App\Tag;

class TagService extends BaseService
{
    public static function getAllTags()
    {
        return Tag::select('id', 'title')->get();
    }

    public static function createTag(createTagRequest $request)
    {
        try {
            $tag = Tag::create([
                'title' => $request->title
            ]);
            return $tag;
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
