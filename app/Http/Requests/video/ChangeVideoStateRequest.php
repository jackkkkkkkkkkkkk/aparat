<?php

namespace App\Http\Requests\video;

use App\Video;
use Illuminate\Foundation\Http\FormRequest;

class ChangeVideoStateRequest extends FormRequest
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
        $states = implode(',', Video::STATES);
        return [
            'state' => 'required|in:' . $states];
    }
}
