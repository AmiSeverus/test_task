<?php

namespace App\Rules;

use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Validation\Rule;

class UniqueImgName implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !File::exists(public_path('/files/' . $value->getClientOriginalName()));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Картинка с таким именем уже существует';
    }
}
