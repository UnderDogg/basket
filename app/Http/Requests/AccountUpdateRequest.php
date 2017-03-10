<?php

namespace App\Http\Requests;

/**
 * Class AccountUpdateRequest
 *
 * @author JH
 * @package App\Http\Requests
 */
class AccountUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @author JH
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @author JH
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|max:255',
        ];
    }
}
