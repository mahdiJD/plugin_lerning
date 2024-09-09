<?php
/**
 * Plugin name: product metabox
**/
defined('ABSPATH') || exit;

define('JDMB_ADMIN', plugin_dir_path(__FILE__).'admin/');
define('JDMB_VIEW', plugin_dir_path(__FILE__).'views/');
define('JDMB_IMAGE', plugin_dir_path(__FILE__).'assets/images/');

if(is_admin()){
    include(JDMB_ADMIN.'metabox_manager.php');
}
