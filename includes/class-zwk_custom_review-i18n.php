<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       zworthkey.com/about-us
 * @since      1.0.0
 *
 * @package    Zwk_custom_review
 * @subpackage Zwk_custom_review/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Zwk_custom_review
 * @subpackage Zwk_custom_review/includes
 * @author     Zworthkey <sales@zworthkey.com>
 */
class Zwk_custom_review_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'zwk_custom_review',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
