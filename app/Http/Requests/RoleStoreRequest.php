<?php

namespace App\Http\Requests;

/**
 * Class RoleStoreRequest
 *
 * @package App\Http\Requests
 */
class RoleStoreRequest extends Request
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
            'name' => 'required',
            'display_name' => 'required',
        ];
    }
}
