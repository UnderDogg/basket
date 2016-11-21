<?php

namespace App\Basket\Blade;

/**
 * Application Helper
 *
 * @package PayBreak\Basket\Blade
 * @author EB
 */
class ApplicationHelper
{
    /**
     * Returns a Bootstrap class for text colour for an Application Status
     *
     * @author EB
     * @param string $status
     * @return string
     */
    public static function getApplicationStatusTextColour($status)
    {
        return self::getStatusConfiguration($status)['color'];
    }

    /**
     * Returns the description for an Application Status
     *
     * @author EB
     * @param string $status
     * @return string
     */
    public static function getApplicationStatusDescription($status)
    {
        return self::getStatusConfiguration($status)['description'];
    }

    /**
     * Returns the display name for an Application Status
     *
     * @author EB
     * @param string $status
     * @return string
     */
    public static function getApplicationDisplayName($status)
    {
        return self::getStatusConfiguration($status)['display_name'];
    }

    /**
     * Returns the status configuration
     *
     * @author EB
     * @param string $status
     * @return array
     */
    private static function getStatusConfiguration($status)
    {
        $conf = self::getConfiguration();

        if (!array_key_exists($status, $conf)) {
            return self::getEmptyConfiguration();
        }

        return $conf[$status];
    }

    /**
     * Returns a Bootstrap class for background colour for an Application Status
     *
     * @author EA
     * @param string $status
     * @return string
     */
    public static function getApplicationStatusBackgroundColour($status)
    {
        return self::getStatusConfiguration($status)['background'];
    }

    /**
     * Returns the Application Configuration for Statuses
     *
     * @author EB
     * @return array
     */
    private static function getConfiguration()
    {
        return [
            'initialized' => [
                'display_name' => 'Initialized',
                'color' => '',
                'description' => '',
                'background' => '',
            ],
            'abandoned' => [
                'display_name' => 'Abandoned',
                'color' => '',
                'description' => '',
                'background' => '',
            ],
            'pending' => [
                'display_name' => 'Pending',
                'color' => '',
                'description' => '',
                'background' => '',
            ],
            'pre_declined' => [
                'display_name' => 'Pre&nbsp;Declined',
                'color' => 'application-status-text',
                'description' => '',
                'background' => 'application-bg-pre-declined',
            ],
            'declined' => [
                'display_name' => 'Declined',
                'color' => 'application-status-text',
                'description' => '',
                'background' => 'application-bg-declined',
            ],
            'referred' => [
                'display_name' => 'Referred',
                'color' => 'application-status-text',
                'description' => '',
                'background' => 'application-bg-referred',
            ],
            'cancelled' => [
                'display_name' => 'Cancelled',
                'color' => 'application-status-text',
                'description' => '',
                'background' => 'application-bg-cancelled',
            ],
            'expired' => [
                'display_name' => 'Expired',
                'color' => '',
                'description' => '',
                'background' => '',
            ],
            'converted' => [
                'display_name' => 'Converted',
                'color' => 'application-status-text',
                'description' => 'This application is approved and ready to go, you should fulfil the order once the customers receives the goods/services.',
                'background' => 'application-bg-converted',
            ],
            'fulfilled' => [
                'display_name' => 'Fulfilled',
                'color' => 'application-status-text',
                'description' => '',
                'background' => 'application-bg-fulfilled',
            ],
            'complete' => [
                'display_name' => 'Complete',
                'color' => 'application-status-text',
                'description' => '',
                'background' => 'application-bg-complete',
            ],
            'pending_cancellation' => [
                'display_name' => 'Pending&nbsp;Cancellation',
                'color' => 'application-status-text',
                'description' => '',
                'background' => 'application-bg-pending-cancellation',
            ],
        ];
    }

    /**
     * Will return an empty set of configuration values if a status does not exist
     *
     * @author EB
     * @return array
     */
    private static function getEmptyConfiguration()
    {
        return [
            'display_name' => 'Not&nbsp;set',
            'color' => '',
            'description' => 'This application may have not been synced yet and so no information regarding the status is available',
            'background' => '',
        ];
    }
}
