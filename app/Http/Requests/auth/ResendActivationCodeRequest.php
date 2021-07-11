<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class ResendActivationCodeRequest extends FormRequest
{
    use GetRegisterValueAndField;

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
            'value'=>'required'
        ];
    }
}
