<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Email;

use Illuminate\Http\Request;

/**
 * Email Template Engine
 *
 * @author EB
 * @package App\Basket\Email
 */
class EmailTemplateEngine
{
    /**
     * Formats a Request so it can be merged with a template
     *
     * @author EB
     * @param Request $request
     * @param string $emailParameter
     * @return array
     */
    public static function formatRequestForEmail(Request $request, $emailParameter)
    {
        return [
            'customer_title' => $request->get('title'),
            'customer_first_name' => $request->get('first_name'),
            'customer_last_name' => $request->get('last_name'),
            'email_recipient' => $request->get($emailParameter),
            'order_description' => $request->get('description'),
        ];
    }
}
