<?php

namespace App\Http\Requests;

/**
 * Class UserStoreRequest
 *
 * @author GK
 * @package App\Http\Requests
 */
class UserStoreRequest extends Request
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
            'password' => 'required|max:255',
            'merchant_id' => 'required',
        ];
    }
}
