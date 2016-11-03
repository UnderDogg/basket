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

use App\Basket\Application;

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
     * @author EB, EA
     * @param Application $application
     * @return array
     * @throws \Exception
     */
    public static function getEmailTemplateFields(Application $application)
    {
        if (
            !(
                empty($application->ext_customer_first_name) ||
                empty($application->ext_customer_last_name) ||
                empty($application->ext_customer_email_address) ||
                empty($application->ext_order_description)
            )
        ) {
            return [
                'customer_title' => $application->ext_customer_title,
                'customer_first_name' => $application->ext_customer_first_name,
                'customer_last_name' => $application->ext_customer_last_name,
                'email_recipient' => $application->ext_customer_email_address,
                'order_description' => $application->ext_order_description,
            ];
        }

        if (
            !(
                empty($application->ext_applicant_first_name) ||
                empty($application->ext_applicant_last_name) ||
                empty($application->ext_applicant_email_address) ||
                empty($application->ext_order_description)
            )
        ) {
            return [
                'customer_title' => $application->ext_applicant_title,
                'customer_first_name' => $application->ext_applicant_first_name,
                'customer_last_name' => $application->ext_applicant_last_name,
                'email_recipient' => $application->ext_applicant_email_address,
                'order_description' => $application->ext_order_description,
            ];
        }

        throw new \Exception('Missing Template Application Fields');
    }
}
