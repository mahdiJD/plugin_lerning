<?php
/**
 * Plugin name: custom style
**/

defined('ABSPATH') || exit;

define('JD_CUSTOM_STYLE_VIEW_PATH', plugin_dir_path(__FILE__).'view/');
define('JD_CUSTOM_STYLE_ADMIN_PATH', plugin_dir_path(__FILE__).'admin/');
if(is_admin()){
    include(JD_CUSTOM_STYLE_ADMIN_PATH . 'menues.php');
}else{
    add_action( 'wp_head',function(){
        $customStyle = get_option('jd-custom-style','');
        printf('<style>%s</style>', $customStyle);
    });
    add_action( 'wp_footer',function(){
        $customScript = get_option('jd-custom-script','');
        printf('<script>%s</script>', $customScript);
    });
}
