<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckHamletCanOpearate implements Rule
{
    protected $permanentHamlet;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($permanentHamlet)
    {
        $this->permanentHamlet = $permanentHamlet;
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
        $user = Auth::user();
        if ($user->address_id == $this->permanentHamlet) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Bạn không có quyền thao tác!';
    }
}
