<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              zworthkey.com/about-us
 * @since             1.0.0
 * @package           Zwk_custom_review
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Review
 * Plugin URI:        zworthkey.com
 * Description:       This plugin used to create Review and also upload an avatar from admin dashboard.
 * Version:           1.0.0
 * Author:            Zworthkey
 * Author URI:        zworthkey.com/about-us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       zwk_custom_review
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ZWK_CUSTOM_REVIEW_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-zwk_custom_review-activator.php
 */
function activate_zwk_custom_review() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zwk_custom_review-activator.php';
	Zwk_custom_review_Activator::activate();
}
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'admin_notices', 'zwkcr_general_admin_notice' );
	return;
}
function zwkcr_general_admin_notice() {
	echo '<div class="notice notice-error ">
             <p>woocommerce is not activate! It should be active to Use the plugin.</p>
         </div>';
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-zwk_custom_review-deactivator.php
 */
function deactivate_zwk_custom_review() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zwk_custom_review-deactivator.php';
	Zwk_custom_review_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_zwk_custom_review' );
register_deactivation_hook( __FILE__, 'deactivate_zwk_custom_review' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-zwk_custom_review.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_zwk_custom_review() {

	$plugin = new Zwk_custom_review();
	$plugin->run();

}
run_zwk_custom_review();

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
 
function add_action_links ( $actions ) {
   $mylinks = array(
      '<a href="' . admin_url( 'admin.php?page=zwkaddreview' ) . '">Add Review</a>',
   );
   $actions = array_merge( $mylinks, $actions );
   return $actions;
}


