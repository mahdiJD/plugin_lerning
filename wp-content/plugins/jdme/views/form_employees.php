<?php

defined('ABSPATH') || exit;
global $title;

global $custom_jd_notice;
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
                <input type="text" name="first_name" id="first_name" ></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="lastـname">نام خانوادگی</label>
            </th>
            <td>
                <input type="text" name="lastـname" id="lastـname"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="mission">تعداد ماموریت</label>
            </th>
            <td>
                <input type="number" name="mission" id="mission"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="weight">وزن</label>
            </th>
            <td>
                <input type="number" name="weight" step="0.1" id="weight"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="birthdate">تاریخ تولد</label>
            </th>
            <td>
                <input type="date" name="birthdate" id="birthdate"></input>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="avatar">تصویر کارمند</label>
            </th>
            <td>
                <input type="text" name="avatar" id="avatar"></input>
                <button type="button" class="button button-secondary" id="employee_avatr_select">انتخواب تصویر کارمند</button>
            </td>
        </tr>

    </table>
    <p class="submit">
        <button class="button button-primary" name="save_employee" value="1">save</button>
    </p>
</form>