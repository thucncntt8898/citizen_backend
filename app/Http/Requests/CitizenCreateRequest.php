<?php

namespace App\Http\Requests;

use App\Rules\CheckConditionCanOperate;
use App\Rules\CheckHamletCanOpearate;
use App\Rules\CheckStatusAccount;
use App\Rules\CheckTimeCanOperate;
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
        return [
            'id_card' => 'required|regex:/^\d+$/|digits:12|unique:citizens,id_card',
            'full_name' => 'required|max:255',
            'dob' => 'date_format:Y-m-d|before:today',
            'gender' => 'required|max:10',
            'religion' => 'required|max:10',
            'edu_level' => 'required|max:10',
            'occupation' => 'required|max:10',
            'permanent_address_province' => ['required', 'integer'],
            'permanent_address_district' => ['required', 'integer'],
            'permanent_address_ward' => ['required', 'integer'],
            'permanent_address_hamlet' => ['required', 'integer',
                new CheckHamletCanOpearate($this->request->get('permanent_address_hamlet')),
                new CheckConditionCanOperate()],
        ];
    }
}
