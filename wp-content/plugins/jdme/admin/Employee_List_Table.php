<?php
if( ! class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Employee_List_Table extends WP_List_Table{

    public function no_items()
    {
        echo "no item in you'r db !";
    }

    public function get_columns()
    {
        return [
            'cb' => '<input type="checkbox" />',
            'ID'        => __( 'id' , 'jdme' ),
            'first_name'=> __( 'name' , 'jdme' ),
            'last_name' => __( 'family' , 'jdme' ),
            'birthdate' => __( 'date of birthdate' , 'jdme' ),
            'avatar'    => __( 'photo' , 'jdme' ),
            'weight'    => __( 'weight' , 'jdme' ),
            'mission'   => __( 'mission' , 'jdme' ),
            'date'      => __( 'created at' , 'jdme' ),
        ];
    }

    public function column_default( $item, $column_name){
        if(isset( $item[$column_name] ) )
            return $item[$column_name];
        return '-';
    }

    public function column_cb($item){
        return '<input type="checkbox" name="employee[]" value="'.$item['ID'].'" />';
    }

    private function create_view( $key,$label, $url, $count = 0){
        $current_status = isset( $_GET['employee_status']) ? $_GET['employee_status'] : 'all';
        $view_tag = sprintf( '<a href="%s" %s>%s</a>', $url,$current_status == $key ? 'class="current"' : '' , $label);
        if( $count ){
            $view_tag .= sprintf('<span class="count">(%d)</span>' , $count);
        }
        return $view_tag;
    }

    protected function get_views(){
        global $wpdb;

        $where = '';
        if (isset( $_GET['s'])) {
            $where = $wpdb->prepare(" AND last_name LIKE %s", '%' . $wpdb->esc_like( $_GET['s'] ) . '%');
        }

        $all = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->jdme_employees} WHERE 1=1 $where ");
        $has_photo = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->jdme_employees} WHERE avatar != '' $where ");
        $no_photo = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->jdme_employees} WHERE avatar = '' $where ");
        // echo " [{ $wpdb->jdme_employees }]";
        // print_r($no_photo);exit;
        return[
            'all' => $this->create_view('all','همه کارمندان',admin_url('admin.php?page=jdme_employees&employee_status=all', $all)),
            'has_photo' => $this->create_view('has_photo','has photo',admin_url('admin.php?page=jdme_employees&employee_status=has_photo', $has_photo )),
            'no_photo' => $this->create_view('no_photo','dont photo',admin_url('admin.php?page=jdme_employees&employee_status=no_photo', $no_photo )),
        ];
    }

    public function get_bulk_actions(){
        return [
            'delete' => 'delete',
            'send_message' => 'send message',
        ];
    }

    public function process_bulk_actions(){
        if($this->current_action() == 'delete'){
            $employees = $_POST['employee'];
            $record_count = count($employees);
            global $wpdb;
            foreach($employees as $employee_id ){
                $wpdb->delete(
                    $wpdb->jdme_employess,
                    [
                        'ID'    => $employee_id,
                    ]
                );
            }
            echo "
                <div class='notice notice-success'>
                    <p>$record_count deleted successfully</p>
                </div>
            ";
        
            wp_redirect(
                admin_url('admin.php?page=jdme_employees&employee_status=bulk_deleted&deleted_count='.$record_count)
            );
        }
    }
    
    public function column_first_name($item){

        $csrf = wp_create_nonce( 'delete_employee' . $item['ID']);

        $actions = [
            'edit'   => '<a href="' . admin_url('admin.php?page=jdme_employees_create&employee_status=edited&employee_id='.$item['ID']) .'"> edit </a>',
            'delete' => '<a href="' . admin_url('admin.php?page=jdme_employees&action=delete_employee&id='.$item['ID']) . '&csrf=' . $csrf .'"> delete </a>',

        ];

        return $item['first_name'] . $this->row_actions($actions);
    }

    public function column_date($item){
        return $item['created_at'];
    }

    public function column_avatar($item){
        if( $item['avatar'] ){
            return sprintf("<img src='%s' width='24' height='24'>",$item['avatar']);
        }
    }

    public function get_sortable_columns(){
        return[
            'weight' => ['weight','desc'],
            'mission' => ['mission', true ],
            'date' => ['date','asc'],
        ];
    }

    public function get_hidden_columns( ){
        return get_hidden_columns( get_current_screen() );
    }

    public function prepare_items()
    {
        global $wpdb;

        $this->process_bulk_actions();

        $per_page = 2;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page -1 ) * $per_page;

        $orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : false ;
        $order = isset( $_GET['order'] ) ? $_GET['order'] : false ;
        $orderClause = "ORDER BY created_at ";
        if($orderby == 'date'){
            $orderby = "created_at";
        }
        if($orderby && $order){
            $orderClause = "ORDER BY $orderby $order ";
        }

        $where = 'WHERE 1=1';
        if (isset( $_GET['employee_status'] ) && $_GET['employee_status'] != 'all') {
            if ( $_GET['employee_status'] == 'has_photo') {
                $where .= " AND avatar != '' ";
            }elseif( $_GET['employee_status'] == 'no_photo'){
                $where .= " AND avatar  = '' ";
            }
        }

        if (isset( $_GET['s'])) {
            $where .= $wpdb->prepare(" AND last_name LIKE %s", '%' . $wpdb->esc_like( $_GET['s'] ) . '%');
        }

        $results = $wpdb->get_results(
            "SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->jdme_employees} $where $orderClause LIMIT $per_page OFFSET $offset",
            ARRAY_A
        );
        $this->_column_headers = array( $this->get_columns(),$this->get_hidden_columns(), $this->get_sortable_columns(),'name');

        $this->set_pagination_args([
            'total_items' => $wpdb->get_var("SELECT FOUND_ROWS() "),
            'per_page'    => $per_page,
        ]);
        $this->items    = $results;
    }
}