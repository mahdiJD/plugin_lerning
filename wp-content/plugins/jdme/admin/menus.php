<?php 
defined('ABSPATH') || exit;

add_action('admin_menu','jdme_add_menuse');

function jdme_add_menuse(){

    add_menu_page(
        'کارمندان',
        'کارمندان',
        'manage_options',
        'jdme_employees',
        function(){
            include(JDME_VIEW.'list_employees.php');
        }
    );

    add_submenu_page(
        'jdme_employees',
        'ایجاد کارمندان',
        'ایجاد کارمندان',
        'manage_options',
        'jdme_employees_create',
        function(){
            include(JDME_VIEW.'form_employees.php');
        }
    );
}

add_action('admin_init','jdme_form_submit');
function jdme_form_submit(){
    global $pagenow;
    // die($pagenow);
    if($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page']=='jdme_employees_create'){
        if ( $_POST['save_employee' ] == 1 ) {
            // print_r($_POST);exit;
            $data = [
                'first_name' => sanitize_text_field($_POST['first_name']),
                'last_name'  => sanitize_text_field($_POST['lastـname']),
                'birthdate'  => sanitize_text_field($_POST['birthdate']),
                'avatar'     => esc_url_raw($_POST['avatar']),
                'weight'     => floatval($_POST['weight']),
                'mission'    => absint($_POST['mission']),
                'created_at' => current_time('mysql')
            ];
            global $wpdb;
            $table_name = $wpdb->prefix .'jdme_employees';
            $inserted = $wpdb->insert(
                $table_name,
                $data,
                [
                    '%s','%s','%s','%s','%d','%f','%s'
                ]
            );

            if($inserted){
                $employee_id = $wpdb->insert_id;
                wp_redirect(
                    admin_url('admin.php?page=jdme_employees_create&employee_status=inserted&employee_id='.$employee_id), 
                );
            }else{
                wp_redirect(
                    admin_url('admin.php?page=jdme_employees_create&employee_status=inserted_erro'), 
                );
                exit;
            }
        }
    }
}

add_action('admin_notices','jdme_notices');
function jdme_notices(){
    $type = '';
    $message = '';

    if (isset($_GET['employee_status'])) {
        $status = sanitize_text_field( $_GET['employee_status'] );
        if($status == 'inserted'){
            $message = 'کارمند با موفقیت ثبت شد';
            $type    = 'success'; 
        }elseif($status == 'inserted_error'){
            $message = 'ثبت با خطا مواجه شد';
            $type    = 'error'; 
        }
    }
    if ($type && $message) {
        ?>
        <div class="notice notice-<?php echo $type; ?> is-dismissible">
            <p><?php echo $message; ?></p>
        </div>
        <?php 
    }
}
