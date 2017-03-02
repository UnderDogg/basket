<?php
/**
 * Created by PhpStorm.
 * User: gregorykreko
 * Date: 02/03/2017
 * Time: 14:48
 */

namespace App\Http\Requests;

/**
 * Class LocationUpdateRequest
 *
 * @package App\Http\Requests
 */
class LocationUpdateRequest extends Request
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
            'reference' => 'required|sometimes|regex:/^[A-Za-z0-9\-]+$/',
            'active' => 'required|sometimes',
            'name' => 'required',
            'email' => 'required|max:255',
            'address' => 'required',
            'converted_email' => 'required|sometimes',
        ];
    }
}