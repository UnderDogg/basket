<?php
/**
 * Created by PhpStorm.
 * User: gregorykreko
 * Date: 02/03/2017
 * Time: 14:48
 */

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