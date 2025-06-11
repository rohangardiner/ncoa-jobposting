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
if (! defined('WPINC')) {
   die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('NCOA_JOBPOSTING_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ncoa-jobposting-activator.php
 */
function activate_ncoa_jobposting() {
   require_once plugin_dir_path(__FILE__) . 'includes/class-ncoa-jobposting-activator.php';
   Ncoa_Jobposting_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ncoa-jobposting-deactivator.php
 */
function deactivate_ncoa_jobposting() {
   require_once plugin_dir_path(__FILE__) . 'includes/class-ncoa-jobposting-deactivator.php';
   Ncoa_Jobposting_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ncoa_jobposting');
register_deactivation_hook(__FILE__, 'deactivate_ncoa_jobposting');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-ncoa-jobposting.php';

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

   add_shortcode('joblist', 'ncoa_joblist');

   function ncoa_joblist() {
      return 'List';
   }

   // Check for plugin updates
   add_action('init', 'github_plugin_updater_ncoa_jobposting');
   function github_plugin_updater_ncoa_jobposting() {
      require_once plugin_dir_path(__FILE__) . 'includes/class-ncoa-jobposting-updater.php';

      if (! defined('WP_GITHUB_FORCE_UPDATE')) {
         define('WP_GITHUB_FORCE_UPDATE', true);
      }

      if (is_admin()) {
         $config = array(
            'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
            'proper_folder_name' => 'ncoa-jobposting', // this is the name of the folder your plugin lives in
            'api_url' => 'https://api.github.com/repos/rohangardiner/ncoa-jobposting', // the GitHub API url of your GitHub repo
            'raw_url' => 'https://raw.github.com/rohangardiner/ncoa-jobposting/main', // the GitHub raw url of your GitHub repo
            'github_url' => 'https://github.com/rohangardiner/ncoa-jobposting', // the GitHub url of your GitHub repo
            'zip_url' => 'https://github.com/rohangardiner/ncoa-jobposting/zipball/main', // the zip url of the GitHub repo
            'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
            'requires' => '4.3', // which version of WordPress does your plugin require?
            'tested' => '6.8.1', // which version of WordPress is your plugin tested up to?
            'readme' => 'README.md', // which file to use as the readme for the version number
            'access_token' => '', // Access private repositories by authorizing under Plugins > GitHub Updates when this example plugin is installed
         );
         new WP_GitHub_Updater_Ncoa_Jobposting($config);
      }
   }
}
run_ncoa_jobposting();
