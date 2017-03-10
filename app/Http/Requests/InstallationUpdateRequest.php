<?php

namespace App\Http\Requests;

/**
 * Class InstallationUpdateRequest
 *
 * @author GK
 * @package App\Http\Requests
 */
class InstallationUpdateRequest extends Request
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
            'name' => 'required|max:255',
            'active' => 'required|sometimes',
            'validity' => 'required|numeric|between:7200,2592000',
            'custom_logo_url' => 'url|max:255',
            'email_reply_to' => 'email|max:255',
            'ext_return_url' => 'url|max:255',
            'ext_notification_url' => 'url|max:255',
            'finance_offers' => 'required|integer',
        ];
    }

    /**
     * Modifies the request's data before validation
     *
     * @author GK
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $data = $this->all();
        // amend Validity Period
        $data['validity'] *= 24 * 60 * 60;
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
