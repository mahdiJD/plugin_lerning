<?php
/**
 * Plugin name: JD Post Like
 * Auther: Mahdi Bijari
 * Text Domain: jdpl
 * Domain Path: /languages
 */
defined('ABSPATH') || exit;


define('JDPL_VERSION', '1.0.0');
define('JDPL_INC', plugin_dir_path(__FILE__).'inc/');
define('JDPL_JS', plugin_dir_url(__FILE__).'assets/js/');
define('JDPL_CSS', plugin_dir_url(__FILE__).'assets/css/');

add_action('plugins_loaded' ,function(){
    load_plugin_textdomain('jdpl',false,dirname( plugin_basename( __FILE__)) . 'languages');
});

global $wpdb;
$wpdb->jdpl_table = $wpdb->prefix . 'jdpl_table';

require_once( JDPL_INC . 'functions.php');

register_activation_hook( __FILE__, 'jdpl_install');
add_action( 'wp_enqueue_scripts', 'jdpl_script');
add_action( 'wp_head', 'jdpl_style');
add_filter('the_content', 'jdpl_button');
add_action( 'wp_ajax_jdpl_like', 'jdpl_ajax_callback');
add_action( 'wp_ajax_nopriv_jdpl_like', 'jdpl_ajax_callback');