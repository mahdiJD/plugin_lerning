<?php
add_action('wp_dashboard_setup','jdmw_add_dashboard_widget');

function jdmw_add_dashboard_widget(){

    global $wp_meta_boxes;

    if ( isset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] )) {
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
    }
    // print_r($wp_meta_boxes);

    wp_add_dashboard_widget( 
        'jdw_dashboard_rss',
        'اخرین های دانشجویار',
        'jdmw_add_dashboard_widget_render', //end
        'jdmw_add_dashboard_widget_controll',
        // $callback_args:array|null,
        // $context:string,
        // $priority:string 
    );
}

function jdmw_add_dashboard_widget_render(){
    $options = get_option('dashbord_widget_options',[]);
    $rss_options = isset( $options['jdw_dashboard_rss'] ) ? $options['jdw_dashboard_rss'] : [
        'count' => 0,
        'show_author' => 0,
    ];

    $count = $rss_options['count'];
    $show_author = $rss_options['show_author'];
    wp_widget_rss_output('https://www.daneshjooyar.com/feed', 
    [
        'items' => $count,
        'show_author' => $show_author,
    ]);
}

function jdmw_add_dashboard_widget_controll(){
    $options = get_option('dashbord_widget_options',[]);
    $rss_options = isset( $options['jdw_dashboard_rss'] ) ? $options['jdw_dashboard_rss'] : [
        'count' => 0,
        'show_author' => 0,
    ];

    if (isset($_POST['jdw_rss_count'])) {
        $rss_options['count'] = absint($_POST['jdw_rss_count']);
        $rss_options['show_auther'] = isset($_POST['jdw_rss_show_auther']) ? 1 : 0 ;

        $options['jdw_dashboard_rss'] = $rss_options;
        update_option('dashboard_widget_option', $options);
    }

    ?>
    <label for="jdw_rss_count">تعداد</label>
    <input type="number" class="widefat" id="jdw_rss_count" name="jdw_rss_count" value="<?php echo esc_attr($rss_options['count']) ?>">
    <label for="jdw_rss_show_auther">نمایش نویسنده</label>
    <input type="checkbox" <?php checked($rss_options['show_auther']) ?> class="widefat" id="jdw_rss_show_auther" name="jdw_rss_show_auther" value="1">
        
    <?php 
}