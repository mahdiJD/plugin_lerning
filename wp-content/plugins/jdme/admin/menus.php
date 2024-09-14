<?php 
defined('ABSPATH') || exit;

add_action('admin_menu','jdme_add_menuse');

function jdme_add_menuse(){

    $list_hook_suffix = add_menu_page(
        __( 'Employees', 'jdme'),
        __( 'Employees', 'jdme'),
        'manage_options',
        'jdme_employees',
        'show_employees'
    );

    // add_action('load-'. $list_hook_suffix , 'jdme_proccess_deletion' );
    add_action('load-'. $list_hook_suffix , 'jdme_proccess_table_data' );

    add_submenu_page(
        'jdme_employees',
        __('Create Employees', 'jdme'),
        __('Create Employees', 'jdme'),
        'manage_options',
        'jdme_employees_create',
        'jdme_render_form'
    );
}

function jdme_proccess_table_data(){
    require(JDME_ADMIN . 'Employee_List_Table.php');
    $GLOBALS['employee_list_table'] = new Employee_List_Table();
    $GLOBALS['employee_list_table']->prepare_items();
}

function jdme_proccess_deletion(){
    if( isset($_GET['action']) && $_GET['action'] == 'delete_employee' && isset($_GET['id']) ){
        $employee_id = absint($_GET['id']);

        if ( !isset( $_GET['scrf'] ) && wp_verify_nonce( $_GET['csrf'], 'delete_employee')) {
            wp_die('csrf is not valid');
        }

        global $wpdb;
        $jdme_employees = $wpdb->prefix .'jdme_employees';
        $deleted = $wpdb->delete(
            $jdme_employees,
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
    $jdme_employees = $wpdb->prefix .'jdme_employees';
    $employees = false;
    if( isset($_GET['employee_id'] ) ){
        $employee_id = absint($_GET['employee_id']);
        if($employee_id){
            $employees = $wpdb->get_row(
                "SELECT * FROM $jdme_employees WHERE ID = $employee_id"
            );
        }
    }
    include(JDME_VIEW.'form_employees.php');
}

function show_employees(){

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
            if ( ! isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( $_POST['_wpnonce'], 'edit_employee' . $employee_id) ) {
                wp_die('nonce invalid');
            }
            
            if ( ! check_admin_referer( 'edit_employee' . $employee_id) ) {
                wp_die('send data from form');
            }

            $gender = $_POST['gender'];
            if ($gender == 'male' || $gender == 'female') // in_array($gender, [male  , female])
            {
                //creact
            }else{
                wp_die('Gender is not valid');
            }
            //save gender
            $phone = $_POST['phone'];
            if ( ! preg_match( "/^[0-9]{11}$/" , $phone )) // in_array($gender, [male  , female])
            {
                wp_die( 'phone is invalid');
            }else{
                // valide
            }

            $email = $_POST['email'];
            if (! is_email( $email ) ) {
                wp_die('email is invalid');
            }else{
                if (! email_exists( $email ) ) {
                    wp_die('email is exists');
                }else{
                    //valid
                }
            }

            $data = [
                'first_name' => sanitize_text_field($_POST['first_name']),
                'last_name'  => sanitize_text_field($_POST['lastـname']),
                'birthdate'  => sanitize_text_field($_POST['birthdate']),
                'avatar'     => esc_url_raw($_POST['avatar']),
                'weight'     => floatval($_POST['weight']),
                'mission'    => absint($_POST['mission']),
            ];

            global $wpdb;
            $jdme_employees = $wpdb->prefix .'jdme_employees';

            if($employee_id){
                $update_row = $wpdb->update(
                    $jdme_employees,
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
                $jdme_employees,
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
            $message = 'employee added successfully';
            $type    = 'success'; 
        }elseif($status == 'inserted_error'){
            $message = 'employee added error';
            $type    = 'error'; 
        }elseif($status == 'edited'){
            $message = 'employee edit successfully';
            $type    = 'success'; 
        }
        elseif($status == 'edited_error'){
            $message = 'employee edit error';
            $type    = 'error'; 
        }elseif($status == 'deleted_error'){
            $message = 'employee deleted error';
            $type    = 'error'; 
        }elseif($status == 'deleted'){
            $message = 'employee deleted successfully';
            $type    = 'success'; 
        }elseif($status == 'bulk_deleted'){
            $message = $_POST['deleted_count'] . 'deleted';
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
