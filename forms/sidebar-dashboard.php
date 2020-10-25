<?php

$_subpanel = d4p_panel()->a()->subpanel;
$_subpanels = d4p_panel()->subpanels();

?>
<div class="d4p-sidebar">
	<?php if (demopress_admin()->subpanel == 'index' && demopress_gen()->is_idle()) { ?>
        <div class="d4p-dashboard-badge" style="background-color: <?php echo d4p_panel()->a()->settings()->i()->color(); ?>;">
            <?php echo d4p_panel()->r()->icon('plugin-icon-'.d4p_panel()->a()->plugin, '9x'); ?>
            <h3>
                <?php echo d4p_panel()->a()->title(); ?>
            </h3>
            <h5>
                <?php printf(__("Version: %s", "d4plib"), d4p_panel()->a()->settings()->i()->version_full()); ?>
            </h5>
        </div>

        <?php

        foreach (d4p_panel()->sidebar_links as $group) {
            if (!empty($group)) {
                echo '<div class="d4p-links-group">';

                foreach ($group as $link) {
                    echo '<a class="'.$link['class'].'" href="'.$link['url'].'">'.d4p_panel()->r()->icon($link['icon']).'<span>'.$link['label'].'</span></a>';
                }

                echo '</div>';
            }
        }
    } else if (demopress_gen()->is_idle()) { ?>
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
	            <?php echo d4p_panel()->r()->icon('ui-sun'); ?>
                <h3><?php _e("Generator", "demopress"); ?></h3>
                <?php echo '<h4>'.d4p_panel()->r()->icon($_subpanels[$_subpanel]['icon']).$_subpanels[$_subpanel]['title'].'</h4>'; ?>
            </div>
            <div class="d4p-panel-info">
		        <?php echo $_subpanels[$_subpanel]['info']; ?>
            </div>
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php _e("Run Generator", "d4plib"); ?>" class="button-primary"/>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e("Return to top", "d4plib"); ?></a>
            </div>
        </div>
    <?php } else {

    }

    ?>
</div>
