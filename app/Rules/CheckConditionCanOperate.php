<?php

namespace App\Rules;

use App\Models\Hamlet;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckConditionCanOperate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $now = date('Y-m-d H:i:s');
        $user = Auth::user();
        if ($user->status == config('constants.STATUS_USER.OFF') ||
            (!is_null($user->time_start) && strtotime($now) < strtotime($user->time_start)) ||
            (!is_null($user->time_finish) && strtotime($now) > strtotime($user->time_finish))
        ) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ngoài thời gian khai báo!';
    }
}
