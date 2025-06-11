<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ncoa.com.au
 * @since      1.0.0
 *
 * @package    Ncoa_Jobposting
 * @subpackage Ncoa_Jobposting/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ncoa_Jobposting
 * @subpackage Ncoa_Jobposting/includes
 * @author     Rohan <rohan@actac.com.au>
 */
class Ncoa_Jobposting_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ncoa-jobposting',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
