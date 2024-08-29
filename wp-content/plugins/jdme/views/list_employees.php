<?php
$page_links = paginate_links([
    'base' => add_query_arg('pagenum','%#%'),
    'total' => $total_pages,
    'current' => $page
]);
?>

<style>
    .pagination > *{
        background: #dfdfdf;
        width: 30px;
        display: inline-flex;
        height: 30px;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        text-decoration: none;
        color: black;
    }

    .pagination{
        margin: 10px 0;
    }
    .pagination .current{
        background: no-repeat;
    }
</style>

<div class="wrap">
    <div class="pagination">
        <?php echo $page_links; ?>
    </div>
    <table class="widefat">
        <thead>
            <th>#</th>
            <th>نام و نام خانوادگی</th>
            <th>تعداد ماموریت</th>
            <th>وزن</th>
            <th>تاریح نولد</th>
            <th>تاریخ ثبت</th>
        </thead>
        <tbody>
        <?php if( $employees) :?>
            <?php foreach( $employees as $employee): ?>
                <tr>
                    <td><?php echo $employee->ID;?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=jdme_employees_create&employee_id='.$employee->ID);?>">
                        <?php echo $employee->first_name.' '.$employee->last_name;?>
                        </a>
                    </td>
                    <td><?php echo $employee->mission;?></td>
                    <td><?php echo $employee->weight;?>KG</td>
                    <td><?php echo $employee->birthdate;?></td>
                    <td><?php echo $employee->created_at;?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">
                    هیچ کاربری ثبت نشده است
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <th>#</th>
            <th>نام و نام خانوادگی</th>
            <th>تعداد ماموریت</th>
            <th>وزن</th>
            <th>تاریح نولد</th>
            <th>تاریخ ثبت</th>
        </tfoot>
    </table>
    <div class="pagination">
        <?php echo $page_links; ?>
    </div>
</div>