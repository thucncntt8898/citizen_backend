<?php

namespace App\Rules;

use App\Models\District;
use App\Models\Hamlet;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;

class UniqueCode implements Rule
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
        $authUser = Auth::user();
        switch ($authUser->role) {
            case 2:
                return !District::where('province_id', Auth::user()->province_id)
                    ->where('code', Auth::user()->username.sprintf('%02d', $value))
                    ->exists();
                break;
            case 3:
                return !Ward::where('province_id', Auth::user()->province_id)
                    ->where('district_id', Auth::user()->district_id)
                    ->where('code', Auth::user()->username.sprintf('%02d', $value))
                    ->exists();
                break;
            case 4:
                return !Hamlet::where('province_id', Auth::user()->province_id)
                    ->where('district_id', Auth::user()->district_id)
                    ->where('ward_id', Auth::user()->ward_id)
                    ->where('code', Auth::user()->username.sprintf('%02d', $value))
                    ->exists();
                break;
            default:
                return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (Auth::user()->role = 1) {
            $model = 'Quận / Huyện';
        } else if (Auth::user()->role = 2) {
            $model = 'Xã / Phường ';
        } else if (Auth::user()->role = 3) {
            $model = 'Thôn / Bản';
        } else {
            $model = ' ';
        }
        return 'Mã '.$model.' đã được sử dụng';
    }
}
