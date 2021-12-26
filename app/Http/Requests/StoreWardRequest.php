<?php

namespace App\Http\Requests;

use App\Rules\UniqueCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWardRequest extends FormRequest
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
        return [
            'code' => [
                'required',
                'numeric',
                'min:01',
                'max:99',
                new UniqueCode()
            ],
            'name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name field is required',
            'code' => 'Code error'
        ];
    }
}
