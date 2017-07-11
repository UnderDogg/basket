<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ApplicationCancellationRequest
 *
 * @author JH
 * @package App\Http\Requests
 */
class ApplicationCancellationRequest extends FormRequest
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
            'description' => 'required',
        ];
    }
}
