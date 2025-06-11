<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ncoa.com.au
 * @since      1.0.0
 *
 * @package    Ncoa_Jobposting
 * @subpackage Ncoa_Jobposting/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ncoa_Jobposting
 * @subpackage Ncoa_Jobposting/admin
 * @author     Rohan <rohan@actac.com.au>
 */
class Ncoa_Jobposting_Admin {

   /**
    * The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_name    The ID of this plugin.
    */
   private $plugin_name;

   /**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
   private $version;

   /**
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_name       The name of this plugin.
    * @param      string    $version    The version of this plugin.
    */
   public function __construct($plugin_name, $version) {

      $this->plugin_name = $plugin_name;
      $this->version = $version;

      // Add Admin menu item with link to plugin settings
      add_action('admin_menu', array($this, 'add_admin_menu'));
   }

   /**
    * Register the stylesheets for the admin area.
    *
    * @since    1.0.0
    */
   public function enqueue_styles() {

      /**
       * This function is provided for demonstration purposes only.
       *
       * An instance of this class should be passed to the run() function
       * defined in Ncoa_Jobposting_Loader as all of the hooks are defined
       * in that particular class.
       *
       * The Ncoa_Jobposting_Loader will then create the relationship
       * between the defined hooks and the functions defined in this
       * class.
       */

      wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ncoa-jobposting-admin.css', array(), $this->version, 'all');
   }

   /**
    * Register the JavaScript for the admin area.
    *
    * @since    1.0.0
    */
   public function enqueue_scripts() {

      /**
       * This function is provided for demonstration purposes only.
       *
       * An instance of this class should be passed to the run() function
       * defined in Ncoa_Jobposting_Loader as all of the hooks are defined
       * in that particular class.
       *
       * The Ncoa_Jobposting_Loader will then create the relationship
       * between the defined hooks and the functions defined in this
       * class.
       */

      wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ncoa-jobposting-admin.js', array('jquery'), $this->version, false);
   }

   public function add_admin_menu() {
      add_options_page(
         'Job Postings',
         'Job Postings',
         'manage_options',
         'ncoa-jobposting-list',
         array($this, 'display_jobposting_table')
      );
   }

   public function display_jobposting_table() {
      global $wpdb;
      $table_name = $wpdb->prefix . 'ncoa_jobposting';

      // Handle actions
      if (isset($_GET['ncoa_action'], $_GET['job_id']) && current_user_can('manage_options')) {
         $job_id = intval($_GET['job_id']);
         if ($_GET['ncoa_action'] === 'archive') {
            $wpdb->update($table_name, array('is_active' => 0), array('id' => $job_id));
            echo '<div class="notice notice-success is-dismissible"><p>Job archived.</p></div>';
         } elseif ($_GET['ncoa_action'] === 'delete') {
            $wpdb->delete($table_name, array('id' => $job_id));
            echo '<div class="notice notice-success is-dismissible"><p>Job deleted.</p></div>';
         }
      }

      $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

      echo '<div class="wrap"><h1>Job Postings</h1>';
      if ($results) {
         echo '<table class="widefat fixed striped">';
         echo '<thead><tr>';
         foreach (array_keys($results[0]) as $col) {
            echo '<th>' . esc_html($col) . '</th>';
         }
         echo '<th>Actions</th>';
         echo '</tr></thead><tbody>';
         foreach ($results as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
               echo '<td>' . esc_html($cell) . '</td>';
            }
            // Action buttons
            $archive_url = add_query_arg(array(
               'page' => 'ncoa-jobposting-list',
               'ncoa_action' => 'archive',
               'job_id' => $row['id']
            ), admin_url('options-general.php'));
            $delete_url = add_query_arg(array(
               'page' => 'ncoa-jobposting-list',
               'ncoa_action' => 'delete',
               'job_id' => $row['id']
            ), admin_url('options-general.php'));
            echo '<td>
                <a href="' . esc_url($archive_url) . '" class="button">Archive</a>
                <a href="' . esc_url($delete_url) . '" class="button" onclick="return confirm(\'Are you sure you want to delete this job posting?\')">Delete</a>
            </td>';
            echo '</tr>';
         }
         echo '</tbody></table>';
      } else {
         echo '<p>No job postings found.</p>';
      }
      echo '</div>';
   }
}
