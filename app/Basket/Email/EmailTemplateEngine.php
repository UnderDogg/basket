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
            'CUSTOMER_TITLE' => $request->get('title'),
            'CUSTOMER_FIRST_NAME' => $request->get('first_name'),
            'CUSTOMER_LAST_NAME' => $request->get('last_name'),
            'ORDER_DESCRIPTION' => $request->get('description'),
        ];
    }
}
