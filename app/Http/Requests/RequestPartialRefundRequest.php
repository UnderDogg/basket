<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class RequestPartialRefundRequest
 *
 * @package App\Http\Requests
 */
class RequestPartialRefundRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'refund_amount' => 'required|numeric|max:' . $application->ext_order_amount/100,
            'effective_date' => 'required|date_format:Y/m/d',
            'description' => 'required',
        ];
    }
}
