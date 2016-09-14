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
     * Returns the Application Configuration for Statuses
     *
     * @author EB
     * @return array
     */
    private static function getConfiguration()
    {
        return [
            'initialized' => [
                'display_name' => 'Initialised',
                'color' => '',
                'description' => '',
            ],
            'abandoned' => [
                'display_name' => 'Abandoned',
                'color' => 'text-muted',
                'description' => '',
            ],
            'pending' => [
                'display_name' => 'Pending',
                'color' => 'text-info',
                'description' => '',
            ],
            'pre_declined' => [
                'display_name' => 'Pre Declined',
                'color' => 'text-danger',
                'description' => '',
            ],
            'declined' => [
                'display_name' => 'Declined',
                'color' => 'text-danger',
                'description' => '',
            ],
            'referred' => [
                'display_name' => 'Referred',
                'color' => 'text-primary',
                'description' => '',
            ],
            'cancelled' => [
                'display_name' => 'Cancelled',
                'color' => 'text-warning',
                'description' => '',
            ],
            'expired' => [
                'display_name' => 'Expired',
                'color' => 'text-muted',
                'description' => '',
            ],
            'converted' => [
                'display_name' => 'Converted',
                'color' => 'text-success',
                'description' => 'This application is approved and ready to go, you should fulfil the order once the customers receives the goods/services.',
            ],
            'fulfilled' => [
                'display_name' => 'Fulfilled',
                'color' => 'text-success',
                'description' => '',
            ],
            'complete' => [
                'display_name' => 'Complete',
                'color' => 'text-success',
                'description' => '',
            ],
            'pending_cancellation' => [
                'display_name' => 'Pending Cancellation',
                'color' => 'text-warning',
                'description' => '',
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
            'display_name' => 'Status not set',
            'color' => '',
            'description' => 'This application may have not been synced yet and so no information regarding the status is available',
        ];
    }
}
