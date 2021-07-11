<?php

namespace App\Http\Requests\video;

use App\Rules\CategoryIdRule;
use App\Rules\PlaylistIdRule;
use App\Rules\VideoBannerUploadRule;
use App\Rules\VideoUploadSubmitRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => ['required', new CategoryIdRule('public')],
            'info' => 'nullable',
            'title' => 'required',
            'banner_id' => ['nullable', new VideoBannerUploadRule],
            'link' => 'nullable|url',
            'channel_category_id' => ['nullable', new CategoryIdRule('private')],
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'playlists' => ['nullable', new PlaylistIdRule()],
            'enable_comments' => 'required|boolean',
        ];
    }
}
