<?php

namespace App\Http\Requests\video;

use App\Rules\CategoryIdRule;
use App\Rules\PlaylistIdRule;
use App\Rules\VideoBannerUploadRule;
use App\Rules\VideoUploadSubmitRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
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
            'video_id' => ['required', new VideoUploadSubmitRule],
            'category_id' => ['required', new CategoryIdRule('public')],
            'info' => 'nullable',
            'title' => 'required',
            'banner_id' => ['nullable', new VideoBannerUploadRule],
            'link' => 'nullable|url',
            'channel_category_id' => ['nullable', new CategoryIdRule('private')],
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'playlists' => ['nullable', new PlaylistIdRule()],
            'publish_at' => 'nullable|date|after:now|date_format:Y-m-d H:i:s',
            'enable_comments'=>'required|boolean',
            'enable_watermark'=>'required|boolean'
        ];
    }
}
