<?php

namespace App\Http\Requests;

/**
 * Class IpsStoreRequest
 *
 * @author GK
 * @package App\Http\Requests
 */
class IpsStoreRequest extends Request
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
            'ip' => 'required|ip'
        ];
    }
}
