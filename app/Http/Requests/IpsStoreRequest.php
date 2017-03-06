<?php

namespace App\Http\Requests;

/**
 * Class IpsStoreRequest
 *
 * @package App\Http\Requests
 */
class IpsStoreRequest extends Request
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
            'ip' => 'required|ip'
        ];
    }
}
