<?php

namespace App\Rules;

use App\Category;
use Illuminate\Contracts\Validation\Rule;

class CategoryIdRule implements Rule
{
    private $type;

    /**
     * Create a new rule instance.
     *
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        if ($this->type == 'public') {
            return Category::whereId($value)->whereNull('user_id')->count();
        }
        if ($this->type == 'private') {
            return Category::whereId($value)->where('user_id',auth()->user()->id)->count();
        }
        return Category::whereId($value)->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'category id error';
    }
}
