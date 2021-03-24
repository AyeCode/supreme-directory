<?php get_header(); ?>

<?php do_action('dt_single_before_main_content'); ?>

<?php
$dt_enable_gd_sidebar = esc_attr(get_theme_mod('dt_enable_gd_sidebar', DT_ENABLE_GD_SIDEBAR));
$dt_gd_sidebar_position = esc_attr(get_theme_mod('dt_gd_sidebar_position', DT_GD_SIDEBAR_POSITION));
$dt_gd_sidebar_position_mobile = esc_attr(get_theme_mod('dt_gd_sidebar_position_mobile', DT_GD_SIDEBAR_POSITION_MOBILE));

if ( $dt_enable_gd_sidebar ) {
    $content_class = 'col-lg-8 col-md-9 pt-3';
    $content_class .= $dt_gd_sidebar_position == 'right' ? ' border-right' : ' border-left';
    $content_class .= $dt_gd_sidebar_position == 'right' ? ' order-md-first' : ' order-md-last';
    $content_class .= $dt_gd_sidebar_position_mobile == 'bottom' ? ' order-first' : ' order-last';
} else {
    $content_class = 'col-lg-12';
}

?>
<?php get_template_part('content-featured-area');



?>

    <div class="fullwidth-sidebar-container">
        <div class="sidebar top-sidebar">
            <?php dynamic_sidebar('sidebar-gd-top'); ?>
        </div>
    </div>

    <div class="fullwidth-sidebar-container sd-details-top-section container-fluid border-bottom">
        <div class="sidebar top-sidebar container">
            <?php get_template_part( 'template-parts/header/header', 'single-top' ); ?>
        </div>
    </div>

    <div class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> py-0">
        <div class="row">
            <section class="<?php echo $content_class; ?>">
                <div class="content-single">
                    <?php if (!have_posts()) : ?>
                        <div class="alert-error">
                            <p><?php _e('Sorry, no results were found.', 'directory-starter'); ?></p>
                        </div>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                    <?php
                    while ( have_posts() ) : the_post();

                        // Include the page content template.
                        get_template_part('content-geodirectory');

                        // End the loop.
                    endwhile;
                    ?>
                </div>
            </section>
            <?php if ( $dt_enable_gd_sidebar ) { ?>
                <aside class="col-lg-4 col-md-3 pt-3">
                    <div class="sidebar blog-sidebar page-sidebar">
                        <?php dynamic_sidebar('sidebar-gd');?>
                    </div>
                </aside>
            <?php } ?>
        </div>
    </div>

    <div class="fullwidth-sidebar-container">
        <div class="sidebar bottom-sidebar">
            <?php dynamic_sidebar('sidebar-gd-bottom'); ?>
        </div>
    </div>


<?php do_action('dt_single_after_main_content'); ?>

<?php get_footer(); ?>