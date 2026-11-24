<?php

use Dev4Press\v56\Core\Quick\KSES;
use function Dev4Press\v56\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$panel = panel();

foreach ( $panel->subpanels() as $subpanel => $generator ) {
	if ( in_array( $subpanel, array( 'index', 'full' ), true ) ) {
		continue;
	}

	$url = $generator['url'] ?? $panel->a()->subpanel_url( $subpanel );

	if ( isset( $generator['break'] ) ) {
		echo KSES::standard( $panel->r()->settings_break( $generator['break'], $generator['break-icon'] ?? '', $generator['break-info'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
    <div class="d4p-feature-box settings-<?php echo esc_attr( sanitize_html_class( $subpanel ) ); ?> _is-settings">
        <div class="_info">
            <div class="_icon"><i class="d4p-icon d4p-<?php echo esc_attr( $generator['icon'] ); ?>"></i></div>
            <h4 class="_title"><?php echo esc_html( $generator['title'] ); ?></h4>
            <p class="_description"><?php echo esc_html( $generator['info'] ); ?></p>
        </div>
        <div class="_ctrl">
            <div class="_open">
                <a class="button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Open', 'd4plib' ); ?></a>
            </div>
        </div>
    </div>
	<?php
}
