<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class StoreMerchantRequest
 *
 * @package App\Http\Requests
 */
class StoreMerchantRequest extends Request
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
            'name' => 'required|max:255',
            'token' => 'required|min:32|max:32',
        ];
    }
}
