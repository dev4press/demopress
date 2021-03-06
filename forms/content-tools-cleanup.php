<div class="d4p-content">

	<?php

	use function Dev4Press\v35\Functions\sanitize_basic;

	if ( isset( $_GET['results'] ) ) {
		$results = urldecode( $_GET['results'] );
		$results = stripslashes( $results );
		$results = json_decode( $results );

		?>

        <div class="d4p-group d4p-group-information">
            <h3><?php _e( "Cleanup Results", "demopress" ); ?></h3>
            <div class="d4p-group-inner">
				<?php

				if ( is_object( $results ) ) {
					$list = array();

					foreach ( $results as $gen => $data ) {
						$gen_label = demopress()->get_generator_label( $gen );

						foreach ( $data as $type => $value ) {
							$list[] = '<strong>' . $gen_label . '</strong>: ' . sprintf( __( "Removed %s items for %s.", "demopress" ), '<strong>' . absint( $value ) . '</strong>', '<strong>' . str_replace( '::', ' / ', sanitize_basic( $type ) ) . '</strong>' );
						}
					}

					echo join( '<br/>', $list );
				} else {
					_e( "Results are not valid.", "demopress" );
				}

				?>
            </div>
        </div>

		<?php

	} else {

		?>


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
			$obj    = demopress()->get_generator( $code );
			$notice = $obj->get_cleanup_notice();
			$types  = $obj->get_cleanup_types();

			?>

            <div class="d4p-group d4p-group-tools demopress-group-cleanup">
                <h3>
                    <i style="float: left; margin-right: 10px;" aria-hidden="true" class="<?php echo $generator['settings']['icon']; ?> d4p-icon-fw"></i><?php echo $generator['label']; ?>
                </h3>
                <div class="d4p-group-inner">
					<?php if ( ! empty( $notice ) ) { ?>
                        <p><?php echo join( '</p><p>', $notice ); ?></p>
					<?php } ?>

					<?php

					foreach ( $types as $name => $label ) {
						$count = $obj->get_cleanup_count( $name );

						?>

                        <label>
                            <input<?php echo $count == 0 ? ' disabled="disabled"' : ''; ?> type="checkbox" class="widefat" name="demopresstools[cleanup][<?php echo $obj->name; ?>][<?php echo $name; ?>]" value="on"/> <?php echo $label . ' (' . sprintf( _n( "%s item", "%s items", $count, "demopress" ) . ')', '<strong>' . $count . '</strong>' ); ?>
                        </label>

						<?php

						if ( $obj->attached_images ) {
							$images = $obj->get_attached_images_count( $name );

							if ( $images ) {

								?>

                                <label style="margin-left: 25px">
                                    <input type="checkbox" class="widefat" name="demopresstools[cleanup][<?php echo $obj->name; ?>][attached-images::<?php echo $name; ?>]" value="on"/> <?php echo __( "Attached Images", "demopress" ) . ' (' . sprintf( _n( "%s item", "%s items", $images, "demopress" ) . ')', '<strong>' . $images . '</strong>' ); ?>
                                </label>

								<?php

							}
						}
					}

					?>
                </div>
            </div>

			<?php

		}
	}

	?>

</div>
