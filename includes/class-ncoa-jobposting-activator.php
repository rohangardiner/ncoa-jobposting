<?php

/**
 * Fired during plugin activation
 *
 * @link       https://ncoa.com.au
 * @since      1.0.0
 *
 * @package    Ncoa_Jobposting
 * @subpackage Ncoa_Jobposting/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ncoa_Jobposting
 * @subpackage Ncoa_Jobposting/includes
 * @author     Rohan <rohan@actac.com.au>
 */
class Ncoa_Jobposting_Activator {

   /**
    * Short Description. (use period)
    *
    * Long Description.
    *
    * @since    1.0.0
    */
   public static function activate() {
      self::ncoa_jobposting_db_table();
   }

   public static function ncoa_jobposting_db_table() {
      global $wpdb;
      $table_name = $wpdb->prefix . 'ncoa_jobposting';
      $charset_collate = $wpdb->get_charset_collate();

      // Create table structure
      $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        company VARCHAR(255) NOT NULL,
        location VARCHAR(255) NOT NULL,
        salary VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        link TEXT NOT NULL,
        is_active BOOLEAN DEFAULT 1 NOT NULL,
        post_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

      // We need to include the 'upgrade.php' file from the WordPress admin includes,
      // as this file contains the dbDelta() function we need to create/update the table.
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

      // Execute the SQL query using dbDelta to compare $sql table structure with the existing one and make changes.
      // This is safer than running a direct CREATE TABLE query, as it won't cause errors if the table already exists.
      dbDelta($sql);
   }
}
