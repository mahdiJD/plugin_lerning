<?php

if(! defined('WP_UNINSTALL_PLUGIN')){
        exit;
}
global $wpdb;
$table_employees = $wpdb->prefix.'jdme_employees';

$wpdb->query("DROP TABLE IF EXISTS $table_employees");