<?php

namespace App\Http\Requests;

/**
 * Class LocationUpdateRequest
 *
 * @author GK
 * @package App\Http\Requests
 */
class LocationUpdateRequest extends Request
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
            'reference' => 'required|sometimes|regex:/^[A-Za-z0-9\-]+$/',
            'active' => 'required|sometimes',
            'name' => 'required',
            'email' => 'required|max:255',
            'address' => 'required',
            'converted_email' => 'required|sometimes',
        ];
    }
}
