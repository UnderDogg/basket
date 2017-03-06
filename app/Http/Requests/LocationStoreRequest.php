<?php

namespace App\Http\Requests;

/**
 * Class LocationStoreRequest
 *
 * @package App\Http\Requests
 */
class LocationStoreRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
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
