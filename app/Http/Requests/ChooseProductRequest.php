<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ChooseProductRequest
 *
 * @package App\Http\Requests
 */
class ChooseProductRequest extends Request
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
            'amount' => 'required|numeric'
        ];
    }
}
