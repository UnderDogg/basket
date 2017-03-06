<?php

namespace App\Http\Requests;

/**
 * Class RoleStoreRequest
 *
 * @author GK
 * @package App\Http\Requests
 */
class RoleStoreRequest extends Request
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
            'name' => 'required',
            'display_name' => 'required',
        ];
    }
}
