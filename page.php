<?php get_header();

do_action('dt_page_before_main_content');
$sidebar = 'pages';
$dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));
$dt_blog_sidebar_position_mobile = esc_attr(get_theme_mod('dt_blog_sidebar_position_mobile', DT_BLOG_SIDEBAR_POSITION_MOBILE));

$content_class = 'col-lg-8 col-md-9';
$content_class .= $dt_blog_sidebar_position == 'right' ? ' order-md-first' : ' order-md-last';
$content_class .= $dt_blog_sidebar_position_mobile == 'bottom' ? ' order-first' : ' order-last';
?>
<?php get_template_part('content-featured-area');?>
<div class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> py-0">
    <div class="row">
        <section class="<?php echo $content_class; ?> pt-3">
            <div class="content-single">
                <?php if (!have_posts()) : ?>
                    <div class="alert alert-warning">
                        <?php _e('Sorry, no results were found.', 'directory-starter'); ?>
                    </div>
                    <?php get_search_form(); ?>
                <?php endif; ?>
                <?php
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'template-parts/content/content' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                    // End the loop.
                endwhile;
                ?>
            </div>
        </section>
        <aside class="col-lg-4 col-md-3 pt-3">
            <div class="sidebar blog-sidebar page-sidebar">
                <?php dynamic_sidebar($sidebar); ?>
            </div>
        </aside>
    </div>
</div>


<?php do_action('dt_page_after_main_content'); ?>

<?php get_footer(); ?>