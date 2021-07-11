<?php

namespace App\Http\Controllers;

use App\Http\Requests\category\CategoryBannerUploadRequest;
use App\Http\Requests\category\CreateCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function getAllCategories()
    {
        return CategoryService::getAllCategories();
    }

    public function getMyCategories()
    {
        return CategoryService::getMyCategories();
    }

    public function uploadBanner(CategoryBannerUploadRequest $request)
    {
        return CategoryService::uploadBanner($request);
    }

    public function create(CreateCategoryRequest $request)
    {
       return CategoryService::create($request);
    }
}
