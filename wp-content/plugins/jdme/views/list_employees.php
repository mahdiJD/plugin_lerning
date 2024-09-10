<?php
global $title;
global $employee_list_table;
?>
<div class=wrap>
    <h1 class="wp-heading-inline"><?php echo $title; ?></h1>
    <a href="<?php echo admin_url('admin.php?page=jdme_employees_create'); ?>" class="page-title-action">add</a>
    
    <form method="GET">
        <input type="hidden" name="page" value="jdme_employees"/>
        <?php
            $employee_list_table->views(); 
            $employee_list_table->search_box('searche' ,'employee_search');
            $employee_list_table->display();
        ?>
    </form>
</div>