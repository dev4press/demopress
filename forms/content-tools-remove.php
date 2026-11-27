<?php

use function Dev4Press\v56\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-content">
    <div class="d4p-group d4p-group-information">
        <h3><?php esc_html_e( "Important Information", "demopress" ); ?></h3>
        <div class="d4p-group-inner">
			<?php esc_html_e( "This tool can remove plugin settings saved in the WordPress options table added by the plugin and you can remove polls votes table and all logged data.", "demopress" ); ?>
            <br/><br/>
			<?php esc_html_e( "Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.", "demopress" ); ?>
			<?php esc_html_e( "If you choose to remove plugin settings, once that is done, all settings will be reinitialized to default values if you choose to leave plugin active.", "demopress" ); ?>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( "Remove plugin settings", "demopress" ); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="demopresstools[remove][settings]" value="on"/> <?php esc_html_e( "Main Settings", "demopress" ); ?>
            </label>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( "Disable Plugin", "demopress" ); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="demopresstools[remove][disable]" value="on"/> <?php esc_html_e( "Disable plugin", "demopress" ); ?>
            </label>
        </div>
    </div>

	<?php panel()->include_accessibility_control(); ?>
</div>
