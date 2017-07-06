<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LocationStoreRequest
 *
 * @author GK
 * @package App\Http\Requests
 */
class LocationStoreRequest extends FormRequest
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
            'reference' => 'required|regex:/^[A-Za-z0-9\-]+$/',
            'installation_id' => 'required',
            'name' => 'required',
            'email' => 'required|max:255',
            'address' => 'required',
        ];
    }
}
