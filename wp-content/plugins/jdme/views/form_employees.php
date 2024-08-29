<?php

defined('ABSPATH') || exit;
global $title;
global $custom_jd_notice;

$ID = 0 ;
$first_name ='';
$last_name ='';
$mission ='';
$weight ='';
$birthdate ='';
$avatar ='';

if($employees){
    $ID = $employees->ID;
    $first_name = $employees->first_name;
    $last_name = $employees->last_name;
    $mission = $employees->mission;
    $weight = $employees->weight;
    $birthdate = $employees->birthdate;
    $avatar = $employees->avatar;
}
?>
<h1><?php echo $title ;?></h1>
<form method="post" action="" >
    <?php if($custom_jd_notice): ?>
    <div class="notice notice-<?php echo $custom_jd_notice['type']; ?>">
        <p>
            <?php echo $custom_jd_notice['message']; ?>
        </p>
    </div>
    <?php endif;?>
    <table class="form-table">

        <tr>
            <th scope="row">
                <label for="first_name">نام</label>
            </th>
            <td>
                <input type="text" name="first_name" id="first_name"  value="<?php echo esc_attr($employees->first_name); ?>"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="lastـname">نام خانوادگی</label>
            </th>
            <td>
                <input type="text" name="lastـname" id="lastـname" value="<?php echo esc_attr($employees->last_name); ?>"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="mission">تعداد ماموریت</label>
            </th>
            <td>
                <input type="number" name="mission" id="mission" value="<?php echo esc_attr($employees->mission); ?>"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="weight">وزن</label>
            </th>
            <td>
                <input type="number" name="weight" step="0.1" id="weight" value="<?php echo esc_attr($employees->weight); ?>"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="birthdate">تاریخ تولد</label>
            </th>
            <td>
                <input type="date" name="birthdate" id="birthdate" value="<?php echo esc_attr($employees->birthdate); ?>"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="avatar">تصویر کارمند</label>
            </th>
            <td>
                <input type="text" name="avatar" id="avatar" value="<?php echo esc_attr($employees->avatar); ?>"></input>
                <button type="button" class="button button-secondary" id="employee_avatr_select">انتخواب تصویر کارمند</button>
            </td>
        </tr>

    </table>
    <p class="submit">
        <input type="hidden" name="ID"  value="<?php echo esc_attr($employees->ID); ?>">
        <button class="button button-primary" name="save_employee" value="1"><?php echo $employees ? 'ویرایش' : 'ثبت' ?></button>
    </p>
</form>