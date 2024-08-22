<?php

defined('ABSPATH') || exit;

global $custom_jd_notice;
?>

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
                <label for="custom-style">استایل سفارشی</label>
            </th>
            <td>
                <textarea name="custom-style" id="custom-style" placeholder="استایل شما ..." class="large-text code" rows="10" ><?php echo $customStyle ?></textarea></td>
        </tr>

        <tr>
            <th scope="row">
                <label for="custom-script">اسکریپت سفارشی </label>
            </th>
            <td>
            <textarea name="custom-script" id="custom-script" placeholder="اسکریپت شما ..." class="large-text code" rows="10" ><?php echo $customScript ?></textarea></td>
        </tr>

    </table>
    <p class="submit">
        <button class="button button-primary">save</button>
    </p>
</form>