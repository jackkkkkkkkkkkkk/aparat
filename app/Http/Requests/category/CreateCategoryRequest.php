<?php

namespace App\Http\Requests\category;

use App\Rules\CategoryBannerIdRule;
use App\Rules\UniqueForUser;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'banner_id' => ['nullable', new CategoryBannerIdRule],
            'title' => ['required', 'min:2', 'max:100', 'string', new UniqueForUser('categories', auth()->user()->id)],
            'icon' => 'nullable|string'
        ];
    }
}
