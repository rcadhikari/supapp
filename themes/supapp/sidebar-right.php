
<?php if ( is_active_sidebar( 'sidebar-right' ) ) { ?>
<div id="sidebar-right" class="<?php wp_basic_bootstrap_sidebar_right_classes(); ?>" role="complementary">
    <div class="vertical-nav block">
    <?php dynamic_sidebar( 'sidebar-right' ); ?>
    </div>
</div>
<?php } ?>