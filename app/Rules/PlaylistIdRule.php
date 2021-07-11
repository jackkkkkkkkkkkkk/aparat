<?php

namespace App\Rules;

use App\Playlist;
use Illuminate\Contracts\Validation\Rule;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class PlaylistIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Playlist::where('user_id', auth()->user()->id)->where('id', $value)->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'playlist error';
    }
}
