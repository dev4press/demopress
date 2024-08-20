<?php

use Dev4Press\v50\Core\Quick\KSES;
use Dev4Press\v50\Library;
use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

?>
<div class="d4p-sidebar">
    <?php if ( demopress_admin()->subpanel == 'index' && demopress_gen()->is_idle() ) { ?>
        <div class="d4p-dashboard-badge" style="background-color: <?php echo esc_attr( panel()->a()->settings()->i()->color() ); ?>;">
            <div class="_icon">
			    <?php echo KSES::strong( panel()->r()->icon( 'plugin-' . panel()->a()->plugin, '9x' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <h3>
			    <?php echo esc_html( panel()->a()->title() ); ?>
            </h3>
            <div class="_version-wrapper">
                <span class="_edition"><?php echo esc_html( ucfirst( panel()->a()->settings()->i()->edition ) ); ?></span>
                <span class="_version"><?php

				    /* translators: Plugin version label. %s: Version number. */
				    echo KSES::strong( sprintf( __( 'Version: %s', 'd4plib' ), '<strong>' . esc_html( panel()->a()->settings()->i()->version_full() ) . '</strong>' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				    ?></span>
            </div>
        </div>

		<?php

	    if ( panel()->a()->buy_me_a_coffee ) {
		    ?>

            <div class="d4p-links-group buy-me-a-coffee">
                <a href="https://www.buymeacoffee.com/millan" target="_blank" rel="noopener">
                    <img alt="BuyMeACoffee" src="<?php echo esc_url( panel()->a()->url . Library::instance()->base_path() . '/resources/gfx/buy_me_a_coffee.png' ); ?>"/>
                </a>
                <a href="https://ko-fi.com/milanpetrovic" target="_blank" rel="noopener">
                    <img alt="KoFi" src="<?php echo esc_url( panel()->a()->url . Library::instance()->base_path() . '/resources/gfx/ko_fi.png' ); ?>"/>
                </a>
            </div>

		    <?php
	    }

	    foreach ( panel()->sidebar_links as $group ) {
		    if ( ! empty( $group ) ) {
			    echo '<div class="d4p-links-group">';

			    foreach ( $group as $_link ) {
				    echo '<a class="' . esc_attr( $_link['class'] ) . '" href="' . esc_url( $_link['url'] ) . '">' . KSES::strong( panel()->r()->icon( $_link['icon'] ) ) . '<span>' . $_link['label'] . '</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			    }

			    echo '</div>';
		    }
	    }
	} else if ( demopress_gen()->is_idle() ) { ?>
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
				<?php echo panel()->r()->icon( 'ui-sun' ); ?>
                <h3><?php _e( "Generator", "demopress" ); ?></h3>
				<?php echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . $_subpanels[ $_subpanel ]['title'] . '</h4>'; ?>
            </div>
            <div class="d4p-panel-info">
				<?php echo $_subpanels[ $_subpanel ]['info']; ?>
            </div>
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php _e( "Run Generator", "demopress" ); ?>" class="button-primary"/>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e( "Return to top", "demopress" ); ?></a>
            </div>
        </div>
	<?php } else { ?>
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
				<?php echo panel()->r()->icon( 'ui-sun' ); ?>
                <h3><?php _e( "Generator", "demopress" ); ?></h3>
                <h4><?php echo panel()->r()->icon( 'ui-play' ); ?><?php _e( "Status", "demopress" ) ?></h4>
            </div>
			<?php if ( demopress_gen()->is_running() ) { ?>
                <div class="d4p-panel-info">
					<?php _e( "The generator is currently running. You can use the button below to stop it. If you choose to stop it, you must know that the stop is not immediate, it can take up to 15 seconds for the running process to get the stop message.", "demopress" ) ?>
                </div>
                <div class="d4p-panel-buttons">
                    <a href="<?php echo wp_nonce_url( admin_url( 'options-general.php?page=demopress&panel=dashboard&demopress_handler=getback&single-action=stoptask' ), 'demopress-task-stop' ); ?>" style="text-align: center" class="button-secondary"><?php _e( "Stop Generator", "demopress" ); ?></a>
                </div>
			<?php } else if ( demopress_gen()->is_finished() || demopress_gen()->is_error() ) { ?>
                <div class="d4p-panel-info">
					<?php _e( "The generator is has finished the last task. Use the button below to reset the last task data.", "demopress" ) ?>
                </div>
                <div class="d4p-panel-buttons">
                    <a href="<?php echo wp_nonce_url( admin_url( 'options-general.php?page=demopress&panel=dashboard&demopress_handler=getback&single-action=resettask' ), 'demopress-task-reset' ); ?>" style="text-align: center" class="button-secondary"><?php _e( "Reset Generator", "demopress" ); ?></a>
                </div>
			<?php } ?>
        </div>
	<?php } ?>
</div>
