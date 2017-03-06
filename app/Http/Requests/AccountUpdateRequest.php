<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AccountUpdateRequest
 *
 * @author JH
 * @package App\Http\Requests
 */
class AccountUpdateRequest extends Request
{
    /**
     * @author JH
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @author JH
     * Get the validation rules that apply to the request.
     *
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
