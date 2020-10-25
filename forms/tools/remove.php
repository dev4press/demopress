<div class="d4p-group d4p-group-reset d4p-group-important">
    <h3><?php _e("Important", "demopress"); ?></h3>
    <div class="d4p-group-inner">
        <?php _e("This tool can remove plugin settings saved in the WordPress options table.", "demopress"); ?><br/><br/>
        <?php _e("Deletion operations are not reversible, and it is recommended to create database backup before proceeding with this tool.", "demopress"); ?>
        <?php _e("If you choose to remove plugin settings, that will also reinitialize all plugin settings to default values.", "demopress"); ?>
    </div>
</div>
<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Choose what you want to delete", "demopress"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="demopresstools[remove][settings]" value="on" /> <?php _e("All Plugin Settings", "demopress"); ?>
        </label>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Disable Plugin", "demopress"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="demopresstools[remove][disable]" value="on" /> <?php _e("Disable plugin", "demopress"); ?>
        </label>
    </div>
</div>
