<?php

namespace App\Rules;

use App\Models\Hamlet;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckStatusAccount implements Rule
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
        if (Auth::user()->status == 0) {
            return false;
        }

        $address = Hamlet::where('id', Auth::user()->address_id)->first();
        dd($address);

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Tài khoản bị khóa thao tác!';
    }
}
