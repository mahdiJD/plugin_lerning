<?php
/**
 * 
 * Plugin Name: sutom login
**/

defined('ABSPATH') || exit;

define('JD_CUSTOM_LOGIN_VER','1.0.0');
define('JD_CUSTOM_LOGIN_ASSETS', plugin_dir_url(__FILE__). 'assets/');
define('JD_CUSTOM_LOGIN_CSS', JD_CUSTOM_LOGIN_ASSETS . 'css/');
define('JD_CUSTOM_LOGIN_JS', JD_CUSTOM_LOGIN_ASSETS . 'js/');
define('JD_CUSTOM_LOGIN_IMAGES', JD_CUSTOM_LOGIN_ASSETS . 'images/');

add_action( 'login_enqueue_scripts', function(){
    wp_enqueue_style( 
        'jd-login-style',
        JD_CUSTOM_LOGIN_CSS .'login.css',
        [],
        WP_DEBUG ? time() : JD_CUSTOM_LOGIN_VER,
        // 'screen and (max-width: 600'
    );

    $backgrond_image = JD_CUSTOM_LOGIN_IMAGES . 'img.jpg' ;

    // wp_add_inline_style(
    //     'jd-login-style',
    //     "body{background: url('$backgrond_image');}"
    // );
});

