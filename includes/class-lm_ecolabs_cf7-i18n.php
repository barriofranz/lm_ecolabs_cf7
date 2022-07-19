<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/barriofranz
 * @since      1.0.0
 *
 * @package    Lm_ecolabs_cf7
 * @subpackage Lm_ecolabs_cf7/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Lm_ecolabs_cf7
 * @subpackage Lm_ecolabs_cf7/includes
 * @author     Franz Ian Barrio <barriofranz@gmail.com>
 */
class Lm_ecolabs_cf7_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'lm_ecolabs_cf7',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
