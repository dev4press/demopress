<div class="d4p-content">
    <div class="d4p-group d4p-group-information">
        <h3><?php _e( "Important Information", "demopress" ); ?></h3>
        <div class="d4p-group-inner">
			<?php _e( "This tool can remove data generated by the plugin. Any other data created by the user will not be affected. If the data is generated by the plugin, and later edited by user, it will still be eligible for deletion!", "demopress" ); ?>
            <br/><br/>
	        <?php _e( "This tool deletes data directly in the database. After the deletion, it is possible that additional orphaned draft records remain in the database.", "demopress" ); ?>
			<?php _e( "Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.", "demopress" ); ?>
        </div>
    </div>

    <?php

    foreach ( demopress()->generators as $code => $generator ) {
        $obj = demopress()->get_generator($code);
        $notice = $obj->get_cleanup_notice();
        $types = $obj->get_cleanup_types();

        ?>

        <div class="d4p-group d4p-group-tools">
            <h3><i style="float: left; margin-right: 10px;" aria-hidden="true" class="<?php echo $generator['settings']['icon']; ?> d4p-icon-fw"></i><?php echo $generator['label']; ?></h3>
            <div class="d4p-group-inner">
                <?php if (!empty($notice)) { ?>
                    <p><?php echo $notice; ?></p>
                <?php } ?>

                <?php foreach ($types as $name => $label) { ?>
                    <label>
                        <input type="checkbox" class="widefat" name="demopresstools[cleanup][<?php echo $obj->name; ?>][<?php echo $name; ?>]" value="on"/> <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <?php

    }

    ?>

</div>
