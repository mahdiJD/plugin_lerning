<?php
/**
 * Plugin name: manage employees
**/
defined('ABSPATH') || exit;

define('JDME_ADMIN', plugin_dir_path(__FILE__).'admin/');
define('JDME_VIEW', plugin_dir_path(__FILE__).'view/');
define('JDME_IMAGE', plugin_dir_path(__FILE__).'assets/images/');

if(is_admin()){
    include(JDME_ADMIN.'menus.php');
}

register_activation_hook(__FILE__,'jdme_install');
function jdme_install(){
    global $wpdb;
    $table_name = $wpdb->prefix .'jdme_employees';
    $table_collation = $wpdb->collate;
    $sql = "CREATE TABLE $table_name ( 
                `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
                `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                `birthdate` date DEFAULT NULL,
                `avatar` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                `weight` float NOT NULL,
                `mission` smallint unsigned NOT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$table_collation COMMENT='keep employees information '
    ";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}