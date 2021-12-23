<?php

namespace App\Http\Requests;

use App\Rules\CheckStatusAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CitizenCreateRequest extends FormRequest
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
        $user = Auth::user();
        $address = $user->address_id;
        return [
            'permanent_address_hamlet' => ["equal:$address", new CheckStatusAccount()],
        ];
    }
}
