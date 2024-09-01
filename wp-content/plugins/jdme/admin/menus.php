<?php 
defined('ABSPATH') || exit;

add_action('admin_menu','jdme_add_menuse');

function jdme_add_menuse(){

    $list_hook_suffix = add_menu_page(
        'کارمندان',
        'کارمندان',
        'manage_options',
        'jdme_employees',
        'show_employees'
    );

    add_action('load-'. $list_hook_suffix , 'jdme_proccess_deletion' );

    add_submenu_page(
        'jdme_employees',
        'ایجاد کارمندان',
        'ایجاد کارمندان',
        'manage_options',
        'jdme_employees_create',
        'jdme_render_form'
    );
}

function jdme_proccess_deletion(){
    if( isset($_GET['action']) && $_GET['action'] == 'delete_employee' && isset($_GET['id']) ){
        $employee_id = absint($_GET['id']);
        global $wpdb;
        $table_name = $wpdb->prefix .'jdme_employees';
        $deleted = $wpdb->delete(
            $table_name,
            [
                'ID' => $employee_id
            ]
        );
        if($deleted){
            wp_redirect(
                admin_url('admin.php?page=jdme_employees&employee_status=deleted')
            );
        }else wp_redirect(admin_url('admin.php?page=jdme_employees&employee_status=deleted_error'));
    }
}

function jdme_render_form(){

    global $wpdb;
    $table_name = $wpdb->prefix .'jdme_employees';
    $employees = false;
    if( isset($_GET['employee_id'] ) ){
        $employee_id = absint($_GET['employee_id']);
        if($employee_id){
            $employees = $wpdb->get_row(
                "SELECT * FROM $table_name WHERE ID = $employee_id"
            );
        }
    }
    include(JDME_VIEW.'form_employees.php');
}

function show_employees(){
    require(JDME_ADMIN . 'Employee_List_Table.php');
    $employeeListTable = new Employee_List_Table();
    $employeeListTable->prepare_items();
    $employeeListTable->display();
    return;

    global $wpdb;
    $table_name = $wpdb->prefix .'jdme_employees';
    $page = isset($_GET['pagenum'] ) ? absint($_GET['pagenum'] ) : 1;
    $per_page =2 ;
    $limit = $per_page;
    $offset = ($page - 1) * $per_page;
    $employees = $wpdb->get_results(
        "SELECT SQL_CALC_FOUND_ROWS * FROM $table_name ORDER BY created_at DESC LIMIT $limit OFFSET $offset"
    );

    $found_rows = $wpdb->get_var( "SELECT FOUND_ROWS()" );
    $total_pages = ceil( $found_rows / $per_page );

    include(JDME_VIEW.'list_employees.php');
}

add_action('admin_init','jdme_form_submit');
function jdme_form_submit(){
    global $pagenow;
    // die($pagenow);
    if($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page']=='jdme_employees_create'){
        if ( $_POST['save_employee' ] == 1 ) {
            // print_r($_POST);exit;

            $employee_id = $_POST['ID'];
            $data = [
                'first_name' => sanitize_text_field($_POST['first_name']),
                'last_name'  => sanitize_text_field($_POST['lastـname']),
                'birthdate'  => sanitize_text_field($_POST['birthdate']),
                'avatar'     => esc_url_raw($_POST['avatar']),
                'weight'     => floatval($_POST['weight']),
                'mission'    => absint($_POST['mission']),
            ];

            global $wpdb;
            $table_name = $wpdb->prefix .'jdme_employees';

            if($employee_id){
                $update_row = $wpdb->update(
                    $table_name,
                    $data,
                    [ 'ID' => $employee_id ],
                    [
                        '%s','%s','%s','%s','%d','%f'
                    ],
                    ['%d']
                );

                if( $update_row === false){
                    wp_redirect(
                        admin_url('admin.php?page=jdme_employees_create&employee_status=edited_error&employee_id='.$employee_id), 
                    );
                }else{
                    wp_redirect(
                        admin_url('admin.php?page=jdme_employees_create&employee_status=edited&employee_id='.$employee_id), 
                    );
                }

            }
            $data['created_at'] = current_time('mysql');
            
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
                    admin_url('admin.php?page=jdme_employees_create&employee_status=inserted_error'), 
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
        }elseif($status == 'edited'){
            $message = 'کارمند ویرایش شد';
            $type    = 'success'; 
        }
        elseif($status == 'edited_error'){
            $message = 'ویرایش با خطا مواجه شد';
            $type    = 'error'; 
        }elseif($status == 'deleted_error'){
            $message = 'حذف با خطا مواجه شد';
            $type    = 'error'; 
        }elseif($status == 'deleted'){
            $message = 'حذف شد';
            $type    = 'success'; 
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
