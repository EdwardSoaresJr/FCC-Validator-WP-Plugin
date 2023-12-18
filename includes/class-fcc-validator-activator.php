<?php

/**
 * Fired during plugin activation
 *
 * @link       https://theauthorurl
 * @since      1.0.0
 *
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/includes
 * @author     Edward Soares - WRUE674 <esoares9483@gmail.com>
 */
class Fcc_Validator_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        // schedule weekly gmrs records update cron job
//        if ( ! wp_next_scheduled( 'fv_cron_weekly_gmrs_records' ) ) {
//            wp_schedule_event( time(), 'weekly', 'fv_cron_weekly_gmrs_records' );
//        }

        // schedule weekly gmrs records update cron job
//        if ( ! wp_next_scheduled( 'fv_cron_weekly_gmrs_records_sync' ) ) {
//            wp_schedule_event( time()+300, 'weekly', 'fv_cron_weekly_gmrs_records_sync' );
//        }
	}

}
