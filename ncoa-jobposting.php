<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ncoa.com.au
 * @since             1.0.0
 * @package           Ncoa_Jobposting
 *
 * @wordpress-plugin
 * Plugin Name:       NCOA Jobposting
 * Plugin URI:        https://ncoa.com.au
 * Description:       Scrape job ads and show a customisable list to users
 * Version:           1.0.0
 * Author:            Rohan
 * Author URI:        https://ncoa.com.au/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ncoa-jobposting
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
define( 'NCOA_JOBPOSTING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ncoa-jobposting-activator.php
 */
function activate_ncoa_jobposting() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ncoa-jobposting-activator.php';
	Ncoa_Jobposting_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ncoa-jobposting-deactivator.php
 */
function deactivate_ncoa_jobposting() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ncoa-jobposting-deactivator.php';
	Ncoa_Jobposting_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ncoa_jobposting' );
register_deactivation_hook( __FILE__, 'deactivate_ncoa_jobposting' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ncoa-jobposting.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ncoa_jobposting() {

	$plugin = new Ncoa_Jobposting();
	$plugin->run();

}
run_ncoa_jobposting();
