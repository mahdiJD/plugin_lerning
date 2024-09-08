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
            'ID'        => 'شناسه',
            'name'      => 'نام',
            'family'    => 'نام خانوادگی',
            'birthdate' => 'تاریخ تولد',
            'avatar'    => 'تصویر',
            'weight'    => 'وزن',
            'mission'   => 'ماموریت',
            'date'      => 'تاریخ ثبت',
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

    public function get_bulk_actions(){
        return [
            'delete' => 'حذف',
            'send_message' => 'ارسال پیام',
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
                    <p>$record_count تا با موفقیت حذف شد </p>
                </div>
            ";
        }
        wp_redirect(
            admin_url('admin.php?page=jdme_employees&employee_status=bulk_deleted&deleted_count='.$record_count)
        );
    }
    
    public function column_name($item){

        $actions = [
            'edit'   => '<a href="' . admin_url('admin.php?page=jdme_employees_create&employee_status=edited&employee_id='.$item['ID']) .'"> ویرایش </a>',
            'delete' => '<a href="' . admin_url('admin.php?page=jdme_employees&action=delete_employee&id='.$item['ID']) .'"> حذف </a>',

        ];

        return $item['first_name'] . $this->row_actions($actions);
    }

    public function column_family($item){
        return $item['last_name'];
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

        $results = $wpdb->get_results(
            "SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->jdme_employyees} $orderClause LIMIT $per_page OFFSET $offset",
            ARRAY_A
        );
        $this->_column_headers = array( $this->get_columns(),array(), $this->get_sortable_columns(),'name');

        $this->set_pagination_args([
            'total_items' => $wpdb->get_var("SELECT FOUND_ROWS() "),
            'per_page'    => $per_page,
        ]);
        $this->items    = $results;
    }
}