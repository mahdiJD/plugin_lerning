<?php
defined('ABSPATH') || exit;

function jd_custom_style_menu_content(){

    $customStyle = get_option('jd-custom-style','');
    $customScript = get_option('jd-custom-script','');
    include(JD_CUSTOM_STYLE_VIEW_PATH . 'input-form.php');
}

function jd_proccess_form(){

    $screen = get_current_screen();
    $screen->add_help_tab([
        'title' => 'راهنما',
        'id' => 'style-help',
        'content' => 'vjnsldkvnsdlkfvnsdk',
    ]);
    $screen->set_help_sidebar(
        '<p>hi</p>
        <p><a>view page source</a></p>'
    );

    $GLOBALS ['custom_jd_notice'] = false;
    if(isset($_POST['custom-style'])){
        $style = trim($_POST['custom-style']);
        $script = trim($_POST['custom-script']);
        $saveStyle = update_option('jd-custom-style',$style);
        $saveScript = update_option('jd-custom-script',$script);
        
        $notice = [
            'type' => 'success',
            'message' => 'success',
        ];
    
        $GLOBALS ['custom_jd_notice'] = $notice;
        
    }
}

function jd_custom_style_menu(){
    $menu_suffix = add_menu_page(
        'Custom style' ,
        'استایل سفارشی' ,
        'manage_options' ,
        'jd-custom-style' , 
        'jd_custom_style_menu_content' ,
        '',
        67
    );

    add_action('load-'.$menu_suffix, 'jd_proccess_form');
}
add_action('admin_menu','jd_custom_style_menu');

