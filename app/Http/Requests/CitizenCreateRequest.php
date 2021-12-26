<?php

namespace App\Http\Requests;

use App\Rules\CheckConditionCanOperate;
use App\Rules\CheckHamletCanOpearate;
use Illuminate\Foundation\Http\FormRequest;

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
            'id_card' => 'required|regex:/^\d+$/|digits:12|unique:citizens,id_card|unique:citizens,id_card',
            'fullname' => 'required|max:255',
            'dob' => 'required|before_or_equal:today',
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
