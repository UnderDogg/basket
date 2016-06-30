<?php

namespace App\Basket\Email;

use Illuminate\Http\Request;

class EmailTemplateEngine
{
    /**
     * Formats a Request so it can be merged with a template
     *
     * @author EB
     * @param Request $request
     * @return array
     */
    public static function formatRequestForEmail(Request $request)
    {
        return [
            'customer_title' => $request->get('title'),
            'customer_first_name' => $request->get('first_name'),
            'customer_last_name' => $request->get('last_name'),
            'email_recipient' => $request->get('email'),
            'email_subject' => $request->get('subject'),
            'order_description' => $request->get('description'),
        ];
    }
}
