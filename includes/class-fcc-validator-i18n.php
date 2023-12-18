<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://theauthorurl
 * @since      1.0.0
 *
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/includes
 * @author     Edward Soares - WRUE674 <esoares9483@gmail.com>
 */
class Fcc_Validator_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fcc-validator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
