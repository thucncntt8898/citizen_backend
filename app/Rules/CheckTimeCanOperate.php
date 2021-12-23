<?php

namespace App\Rules;

use App\Models\Hamlet;
use Illuminate\Contracts\Validation\Rule;

class CheckTimeCanOperate implements Rule
{
    protected $hamletId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($hamletId)
    {
        $this->hamletId = $hamletId;
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
//        $time = Hamlet::where('id', '')
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
