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

      // Handle new job posting form submission
      if (
         isset($_POST['ncoa_add_job_nonce']) &&
         wp_verify_nonce($_POST['ncoa_add_job_nonce'], 'ncoa_add_job') &&
         current_user_can('manage_options')
      ) {
         $title = sanitize_text_field($_POST['title']);
         $company = sanitize_text_field($_POST['company']);
         $location = sanitize_text_field($_POST['location']);
         $salary = sanitize_text_field($_POST['salary']);
         $description = sanitize_textarea_field($_POST['description']);
         $link = esc_url_raw($_POST['link']);
         $is_active = isset($_POST['is_active']) ? 1 : 0;
         $post_date = current_time('mysql');

         $wpdb->insert($table_name, array(
            'title' => $title,
            'company' => $company,
            'location' => $location,
            'salary' => $salary,
            'description' => $description,
            'link' => $link,
            'is_active' => $is_active,
            'post_date' => $post_date,
         ));

         echo '<div class="notice notice-success is-dismissible"><p>New job posting added.</p></div>';
      }

      // Handle actions
      if (isset($_GET['ncoa_action'], $_GET['job_id']) && current_user_can('manage_options')) {
         $job_id = intval($_GET['job_id']);
         if ($_GET['ncoa_action'] === 'toggle_active') {
            // Get current is_active value
            $current = $wpdb->get_var($wpdb->prepare("SELECT is_active FROM $table_name WHERE id = %d", $job_id));
            $new_value = ($current == 1) ? 0 : 1;
            $wpdb->update($table_name, array('is_active' => $new_value), array('id' => $job_id));
            $msg = $new_value ? 'Job restored.' : 'Job archived.';
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($msg) . '</p></div>';
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

         // Table header
         echo '
            <th>Title</th>
            <th>Company</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Description</th>
            <th>Link</th>
            <th>Active</th>
            <th>Date</th>
         ';
         echo '<th>Actions</th>';
         echo '</tr></thead><tbody>';
         foreach ($results as $row) {
            echo '<tr>';

            // Table data row
            echo '
               <td>' . esc_html($row['title']) . '</td>
               <td>' . esc_html($row['company']) . '</td>
               <td>' . esc_html($row['location']) . '</td>
               <td>' . esc_html($row['salary']) . '</td>
               <td>' . esc_html(wp_trim_words($row['description'], 50, '...')) . '</td>
               <td>' . esc_html($row['link']) . '</td>
               <td>' . esc_html($row['is_active']) . '</td>
               <td>' . esc_html($row['post_date']) . '</td>
            ';

            // Action buttons
            $toggle_url = add_query_arg(array(
               'page' => 'ncoa-jobposting-list',
               'ncoa_action' => 'toggle_active',
               'job_id' => $row['id']
            ), admin_url('options-general.php'));
            $toggle_label = ($row['is_active']) ? 'Archive' : 'Restore';

            $delete_url = add_query_arg(array(
               'page' => 'ncoa-jobposting-list',
               'ncoa_action' => 'delete',
               'job_id' => $row['id']
            ), admin_url('options-general.php'));

            echo '<td>
               <a href="' . esc_url($toggle_url) . '" class="button">' . esc_html($toggle_label) . '</a>
               <a href="' . esc_url($delete_url) . '" class="button" onclick="return confirm(\'Are you sure you want to delete this job posting?\')">Delete</a>
            </td>';
            echo '</tr>';
         }
         echo '</tbody></table>';
      } else {
         echo '<p>No job postings found.</p>';
      }
      echo '</div>';

      // Add Job Posting Form
?>
      <h2>Add New Job Posting</h2>
      <form method="post">
         <?php wp_nonce_field('ncoa_add_job', 'ncoa_add_job_nonce'); ?>
         <table class="form-table">
            <tr>
               <th><label for="title">Title</label></th>
               <td><input name="title" type="text" id="title" class="regular-text" required></td>
            </tr>
            <tr>
               <th><label for="company">Company</label></th>
               <td><input name="company" type="text" id="company" class="regular-text" required></td>
            </tr>
            <tr>
               <th><label for="location">Location</label></th>
               <td><input name="location" type="text" id="location" class="regular-text"></td>
            </tr>
            <tr>
               <th><label for="salary">Salary</label></th>
               <td><input name="salary" type="text" id="salary" class="regular-text"></td>
            </tr>
            <tr>
               <th><label for="description">Description</label></th>
               <td><textarea name="description" id="description" class="large-text" rows="4"></textarea></td>
            </tr>
            <tr>
               <th><label for="link">Link</label></th>
               <td><input name="link" type="url" id="link" class="regular-text"></td>
            </tr>
            <tr>
               <th><label for="is_active">Active</label></th>
               <td><input name="is_active" type="checkbox" id="is_active" value="1" checked> Yes</td>
            </tr>
         </table>
         <p><input type="submit" class="button button-primary" value="Add Job Posting"></p>
      </form>
<?php
   }
}
