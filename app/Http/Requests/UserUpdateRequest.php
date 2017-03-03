<?php
/**
 * Created by PhpStorm.
 * User: gregorykreko
 * Date: 02/03/2017
 * Time: 14:48
 */

namespace App\Http\Requests;

/**
 * Class UserUpdateRequest
 *
 * @package App\Http\Requests
 */
class UserUpdateRequest extends Request
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'sometimes|max:255',
            'merchant_id' => 'required',
        ];
    }
}
