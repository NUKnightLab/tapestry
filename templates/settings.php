<div class="wrap">
    <h2>WP Plugin Template</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('tapestry2-group'); ?>
        <?php @do_settings_fields('tapestry2-group'); ?>

        <?php do_settings_sections('tapestry2'); ?>

        <?php @submit_button(); ?>
    </form>
</div>