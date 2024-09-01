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

    public function column_name($item){
        return $item['first_name'];
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

    public function prepare_items()
    {
        global $wpdb;

        $per_page = 2;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page -1 ) * $per_page;

        $results = $wpdb->get_results(
            "SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->jdme_employyees} ORDER BY created_at LIMIT $per_page OFFSET $offset",
            ARRAY_A
        );
        $this->_column_headers = array( $this->get_columns(),array(),array(),'name');

        $this->set_pagination_args([
            'total_items' => $wpdb->get_var("SELECT FOUND_ROWS() "),
            'per_page'    => $per_page,
        ]);
        $this->items    = $results;
    }
}