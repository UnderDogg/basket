<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserUpdateRequest
 *
 * @author GK
 * @package App\Http\Requests
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * @author GK
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @author GK
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'sometimes|max:255',
            'merchant_id' => 'required',
        ];
    }
}
