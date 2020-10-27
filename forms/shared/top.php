<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $panels ) ) {
	if ( $_task === false || empty( $_task ) ) {
		$_task = 'index';
	}

	$_available = array_keys( $panels );

	if ( ! in_array( $_task, $_available ) ) {
		$_task                  = 'index';
		demopress_admin()->task = false;
	}
}

$_classes = array( 'd4p-wrap', 'wpv-' . DEMOPRESS_WPV, 'd4p-panel-' . $_panel );

if ( $_task !== false ) {
	$_classes[] = 'd4p-task';
	$_classes[] = 'd4p-task-' . $_task;
}

$_message = '';

if ( isset( $_GET['message'] ) && $_GET['message'] != '' ) {
	$msg = d4p_sanitize_slug( $_GET['message'] );

	switch ( $msg ) {
		case 'gen-working':
			$_message = __( "Generator is already working.", "demopress" );
			break;
		case 'gen-error':
			$_message = __( "Creating Generator task has failed.", "demopress" );
			break;
		case 'gen-added':
			$_message = __( "New Generator has been created.", "demopress" );
			break;
		case 'gen-stopped':
			$_message = __( "Generator stop flag has been set. Please wait...", "demopress" );
			break;
		case 'gen-removed':
			$_message = __( "Generator task process data have been removed.", "demopress" );
			break;
		case 'saved':
			$_message = __( "Settings are saved.", "demopress" );
			break;
		case 'removed':
			$_message = __( "Removal operation completed.", "demopress" );
			break;
		case 'imported':
			$_message = __( "Import operation completed.", "demopress" );
			break;
		case 'nothing':
			$_message = __( "Nothing to do.", "demopress" );
			break;
	}
}

?>
<div class="<?php echo join( ' ', $_classes ); ?>">
    <div class="d4p-header">
        <div class="d4p-navigator">
            <ul>
                <li class="d4p-nav-button">
                    <a href="#"><i aria-hidden="true" class="<?php echo d4p_get_icon_class( $pages[ $_panel ]['icon'] ); ?>"></i> <?php echo $pages[ $_panel ]['title']; ?>
                    </a>
                    <ul>
						<?php

						foreach ( $pages as $page => $obj ) {
							$url = 'options-general.php?page=' . demopress_admin()->plugin . '&panel=' . $page;

							if ( $page != $_panel ) {
								echo '<li><a href="' . $url . '"><i aria-hidden="true" class="' . ( d4p_get_icon_class( $obj['icon'], 'fw' ) ) . '"></i> ' . $obj['title'] . '</a></li>';
							} else {
								echo '<li class="d4p-nav-current"><i aria-hidden="true" class="' . ( d4p_get_icon_class( $obj['icon'], 'fw' ) ) . '"></i> ' . $obj['title'] . '</li>';
							}
						}

						?>
                    </ul>
                </li>
				<?php if ( ! empty( $panels ) ) { ?>
                    <li class="d4p-nav-button">
                        <a href="#"><i aria-hidden="true" class="<?php echo d4p_get_icon_class( $panels[ $_task ]['icon'] ); ?>"></i> <?php echo $panels[ $_task ]['title']; ?>
                        </a>
                        <ul>
							<?php

							foreach ( $panels as $panel => $obj ) {
								if ( $panel != $_task ) {
									echo '<li><a href="options-general.php?page=' . demopress_admin()->plugin . '&panel=' . $_panel . '&task=' . $panel . '"><i aria-hidden="true" class="' . ( d4p_get_icon_class( $obj['icon'], 'fw' ) ) . '"></i> ' . $obj['title'] . '</a></li>';
								} else {
									echo '<li class="d4p-nav-current"><i aria-hidden="true" class="' . ( d4p_get_icon_class( $obj['icon'], 'fw' ) ) . '"></i> ' . $obj['title'] . '</li>';
								}
							}

							?>
                        </ul>
                    </li>
				<?php } ?>
            </ul>
        </div>
        <div class="d4p-plugin">
			<?php echo demopress_admin()->title(); ?>
        </div>
    </div>
	<?php

	if ( $_message != '' ) {
		echo '<div class="updated">' . $_message . '</div>';
	}

	?>
    <div class="d4p-content">
