<?php
/**
 * 
 * Plugin Name: csutom option
**/

defined('ABSPATH') || exit;

add_action('admin_menu',function(){
    add_options_page(
        'تنطیمات جاوادرینکرز',
        'تنطیمات جاوادرینکرز',
        'manage_options',
        'ja-options',
        function(){
            include 'view.php';
        }
    );
});

add_action('admin_init' ,'jd_add_setting');
function jd_add_setting(){
    add_settings_field(
        'developer_name',
        'نام توسعه دهنده',
        'jd_render_fild',
        'jd-options',
        'default',
        [
            'class' => 'developer_class'
        ]
    );

    register_setting(
        'general',
        'developer_name',
        [
            'sanitize_callback' => 'sanitize_text_fild'
        ]
    );

    add_settings_section(
        'jd_section',
        'تنظیمات پلاگین جاوا درینکرز',
        'jd_render_section',
        'general',
        [
            'before_section' => '<section id="custom-section">',
            'after_section' => '</div></section>'
        ]
    );
}

function jd_render_section(){
    '<div class="jd-acc" style="display: none;" >';
}

add_action('admin_enqueue_scripts',function( $hook_suffix){
    if($hook_suffix == 'options-general.php'){
        wp_enqueue_script(
            'jd_settings',
            plugin_dir_url(__FILE__).'sctipt.js',
            ['jquery'],
            '1.0'
        );
    }
});

function jd_render_fild($args){
    ?>
    <input type="text" name="developer_name" class="<?php $args['class'] ?>" placeholder="نام توسعه دهنده" value="" >
    <?php
}