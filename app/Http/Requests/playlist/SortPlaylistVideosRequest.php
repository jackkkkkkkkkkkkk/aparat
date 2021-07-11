<?php

namespace App\Http\Requests\playlist;

use App\Rules\SortPlaylistVideosRule;
use App\Rules\UniqueForUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SortPlaylistVideosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('sort', [$this->playlist,$this->data]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data'=>['required',new SortPlaylistVideosRule($this->playlist)]
        ];
    }
}
