<?php

namespace App\Http\Requests\channel;

use App\Rules\ChannelNameRule;
use Illuminate\Foundation\Http\FormRequest;

class ChannelUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route()->hasParameter('id') && auth()->user()->type != 'admin') {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->route()->hasParameter('id')) {
            $id = $this->route('id');
        } else {
            $id = auth()->user()->channel->id;
        }
        return [
            'info' => 'nullable|string',
            'website' => 'nullable|url',
            'name' => ['required',"unique:channels,name,$id",new ChannelNameRule()]
        ];
    }
}
