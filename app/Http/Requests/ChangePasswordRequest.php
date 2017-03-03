<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ChangePasswordRequest
 *
 * @package App\Http\Requests
 */
class ChangePasswordRequest extends Request
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
            'old_password' => 'required',
            'new_password' => 'required|confirmed|different:old_password',
            'new_password_confirmation' => 'required|different:old_password|same:new_password',
        ];
    }
}
