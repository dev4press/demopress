<div class="d4p-content">
    <div class="d4p-group d4p-group-information">
        <h3><?php _e("Important Information", "gd-topic-polls"); ?></h3>
        <div class="d4p-group-inner">
            <?php _e("This tool can remove plugin settings saved in the WordPress options table added by the plugin and you can remove polls votes table and all logged data.", "gd-topic-polls"); ?><br/><br/>
            <?php _e("Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.", "gd-topic-polls"); ?>
            <?php _e("If you choose to remove plugin settings, once that is done, all settings will be reinitialized to default values if you choose to leave plugin active.", "gd-topic-polls"); ?>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php _e("Remove plugin settings", "gd-topic-polls"); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="demopresstools[remove][settings]" value="on" /> <?php _e("Main Settings", "gd-topic-polls"); ?>
            </label>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php _e("Disable Plugin", "gd-topic-polls"); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="demopresstools[remove][disable]" value="on" /> <?php _e("Disable plugin", "gd-topic-polls"); ?>
            </label>
        </div>
    </div>

    <?php d4p_panel()->include_accessibility_control(); ?>
</div>
