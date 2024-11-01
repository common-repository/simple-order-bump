<?php
/**
 * Plugin Name: Simple Order Bump
 * Plugin URI: https://marcode.site/plugin/simple-order-bump/
 * Description: Create simples order bumps
 * Version: 1
 * Requires at least: 5.6
 * Requires PHP: 7.0
 * Author: Marco Mireles
 * Author URI: https://marcomireles.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sob
 * Domain Path: /languages
 */
/*
Simple Order Bump is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Simple Order Bump is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Simple Order Bump. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if( !class_exists( 'SIMPLE_ORDER_BUMP' )){
  class SIMPLE_ORDER_BUMP{

    public function __construct(){

      $this->define_constants();

//      require_once (SOB_PATH.'functions/functions.php');
      add_action('admin_menu',array($this,'add_menu'));

      require_once (SOB_PATH . 'class.sob-settings.php');
      $SOB_Settings = new SOB_Settings();

      require_once (SOB_PATH . 'class.ajax-add-to-cart.php');
      $Ajax_Add_To_Cart = new Ajax_Add_To_Cart();

      require_once (SOB_PATH . 'class.sob.php');
      $SOB_View = new SOB_View();
      add_action('wp_enqueue_scripts',array($this,'register_scripts'),999);
      add_action('admin_enqueue_scripts', array($this,'register_admin_scripts'), 999);
      add_action('plugin_row_meta', array($this,'filter_plugin_row_meta'),10,4);


    }

    public function define_constants(){
      // Path/URL to root of this plugin, with trailing slash.
      define ( 'SOB_PATH', plugin_dir_path( __FILE__ ) );
      define ( 'SOB_URL', plugin_dir_url( __FILE__ ) );
      define ( 'SOB_VERSION', '2.0.1' );
    }

    public function add_menu(){
      add_menu_page(
        esc_html__('Order Bump','sob'),
        'Order Bump',
        'manage_options',
        'simple_order_bump_admin',
        array($this,'simple_order_bump_settings_page'),
        SOB_URL.'/assets/img/order.svg',
        25
      );
    }

    public function simple_order_bump_settings_page(){
      if (!current_user_can('manage_options')){
        return;
      }
      if (isset($_GET['settings-updated'])){
        add_settings_error('sob_options','sob_message',esc_html__('Settings Saved','sob'),'success');
      }
      settings_errors('wa_bubble_options');
      require_once (SOB_PATH . 'views/settings-page.php');
    }

    public function register_scripts(){
      wp_register_script('sob-main-js',SOB_URL.'vendor/js/frontend.js',array('jquery'),SOB_VERSION,true);
      wp_register_style('sob-main-css',SOB_URL . 'assets/css/frontend.css',array(),SOB_VERSION,'all');
    }

    public function register_admin_scripts($hook){
      if( $hook == 'toplevel_page_simple_order_bump_admin'){
        wp_enqueue_style( 'sob-backend-css', SOB_URL . 'assets/css/backend.css',array(),SOB_VERSION,'all' );
        wp_enqueue_style( 'select2-css', SOB_URL .'vendor/css/select2.css',array(),SOB_VERSION,'all' );
        wp_register_style('font-poppins','https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap',array(),SOB_VERSION,'all');

        wp_enqueue_script('sob_adminjs', SOB_URL .'vendor/js/style.js', array('select2-js'), SOB_VERSION, true );
        wp_enqueue_script('select2-js', SOB_URL .'vendor/js/select2.js', array(), SOB_VERSION, false );

        if ( ! did_action( 'wp_enqueue_media' ) ) {
          wp_enqueue_media();
        }
      }
    }

    public function filter_plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status )
    {

      if (strpos($plugin_file_name, basename(__FILE__))) {
        // You can still use `array_unshift()` to add links at the beginning.
        $links_array[] = '<a href="https://paypal.me/marcodeoficial?country.x=MX&locale.x=es_XC">Donate 🍺</a>';
      }
      return $links_array;
    }

    /**
     * Activate the plugin
     */
    public static function activate(){
      update_option('rewrite_rules', '' );

    }

    /**
     * Deactivate the plugin
     */
    public static function deactivate(){
      flush_rewrite_rules();
    }

    /**
     * Uninstall the plugin
     */
    public static function uninstall(){
      delete_option('sob_options');
    }


  } //END CLASS
}

// Plugin Instantiation
if (class_exists( 'SIMPLE_ORDER_BUMP' )){

  // Installation and uninstallation hooks
  register_activation_hook( __FILE__, array( 'SIMPLE_ORDER_BUMP', 'activate'));
  register_deactivation_hook( __FILE__, array( 'SIMPLE_ORDER_BUMP', 'deactivate'));
  register_uninstall_hook( __FILE__, array( 'SIMPLE_ORDER_BUMP', 'uninstall' ) );

  // Instatiate the plugin class
  $SIMPLE_ORDER_BUMP = new SIMPLE_ORDER_BUMP();
}