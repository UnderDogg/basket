<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AmendOrderRequest
 *
 * @author JH
 * @package App\Http\Requests
 */
class AmendOrderRequest extends Request
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
            'amount' => 'required|numeric',
            'description' => 'required',
        ];
    }

    /**
     * Error messages for each of the validation rules
     *
     * @author JH
     * @return array
     */
    public function messages()
    {
        return [
            'amount.required' => 'An amount is required',
            'amount.numeric' => 'The amount must be a number',
            'description.required' => 'A description is required',
        ];
    }
}
