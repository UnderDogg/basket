<?php

namespace App\Http\Requests;

/**
 * Class MerchantStoreRequest
 *
 * @author JH
 * @package App\Http\Requests
 */
class MerchantStoreRequest extends Request
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
            'name' => 'required|max:255',
            'token' => 'required|min:32|max:32',
        ];
    }
}