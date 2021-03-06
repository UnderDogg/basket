<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ChooseProductRequest
 *
 * @author JH
 * @package App\Http\Requests
 */
class ChooseProductRequest extends FormRequest
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
            'amount_in_pounds' => 'required|numeric'
        ];
    }
}
