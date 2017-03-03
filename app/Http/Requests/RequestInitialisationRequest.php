<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class RequestInitialisationRequest
 *
 * @package App\Http\Requests
 */
class RequestInitialisationRequest extends Request
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
            'amount' => 'required|integer',
            'group' => 'required',
            'product' => 'required',
            'reference' => 'required|min:6',
            'description' => 'required|min:6',
            'deposit' => 'sometimes|integer',
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'email' => 'sometimes|email|max:255',
            'phone_home' => 'sometimes|max:11',
            'phone_mobile' => 'sometimes|max:11',
        ];
    }
}
