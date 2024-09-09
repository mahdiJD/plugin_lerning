<?php
/**
 * Plugin name: custom widget
**/
defined('ABSPATH') || exit;

define('JDME_ADMIN', plugin_dir_path(__FILE__).'admin/');
define('JDME_VIEW', plugin_dir_path(__FILE__).'views/');
define('JDME_IMAGE', plugin_dir_path(__FILE__).'assets/images/');


include(JDME_ADMIN.'widget-manegar.php');
if (is_admin()) {
    include(JDME_ADMIN.'dashboard_widget.php');
}