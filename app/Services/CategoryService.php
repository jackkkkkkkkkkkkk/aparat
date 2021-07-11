<?php


namespace App\Services;


use App\Category;
use App\Channel;
use App\Http\Requests\category\CategoryBannerUploadRequest;
use App\Http\Requests\category\CreateCategoryRequest;
use App\Http\Requests\channel\ChannelUpdateRequest;
use App\Http\Requests\channel\UpdateSocialsRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CategoryService extends BaseService
{

    public static function getAllCategories()
    {
        $categories = Category::all();
        return response($categories);
    }

    public static function getMyCategories()
    {
        $categories = auth()->user()->category;
        return $categories;
    }

    public static function uploadBanner(CategoryBannerUploadRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName = auth()->user()->id . '-' . md5(Carbon::now()->timestamp) . '_banner';
            Storage::disk('category')->put('temp/' . $fileName, $banner->get());
            return response(['banner' => $fileName]);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public static function create(CreateCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $category = auth()->user()->category()->create([
                'title' => $request->title,
                'icon' => $request->icon,
                'banner' => $request->banner_id
            ]);
            if ($request->banner_id) {
                $fileName = uniqueId($category->id);
                Storage::disk('category')->move('/temp/' . $request->banner_id, $fileName);
                $category->update([
                    'banner' => $fileName
                ]);
            }
            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage()]);
        }
    }
}
