<?php 
defined('ABSPATH') || exit;
?>
<h1><?php echo get_admin_page_title() ?></h1>
<form action="options.php" method="POST">

    <?php settings_fields( 'jd-options' );
          do_settings_fields('jd-options', 'default');
          do_settings_sections('jd-options')
    ?>
    <?php submit_button() ?>
</form>