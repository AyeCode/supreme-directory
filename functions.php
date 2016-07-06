<?php
/**
 * Functions for the Supreme Directory child theme
 *
 * This file includes functions for the Supreme Directory child theme.
 *
 * @since 0.0.1
 * @package Supreme_Directory
 */


/*#############################################
SUPREME DIRECTORY CODE STARTS
#############################################*/

/*
 * Define some constants for later use.
 */
if (!defined('SD_DEFAULT_FEATURED_IMAGE')) define('SD_DEFAULT_FEATURED_IMAGE', get_stylesheet_directory_uri() . "/images/featured.jpg");
if (!defined('SD_VERSION')) define('SD_VERSION', "1.0.2");
if (!defined('SD_CHILD')) define('SD_CHILD', 'supreme-directory');

/**
 * Adds GeoDirectory plugin required admin notice.
 *
 * @since 1.0.0
 */
function geodir_sd_force_update_remove_notice()
{

    $screen = get_current_screen();
    if(isset($screen->id) && $screen->id=='themes'){
    $action = 'install-plugin';
    $slug = 'geodirectory';
    $install_url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => $action,
                'plugin' => $slug
            ),
            admin_url( 'update.php' )
        ),
        $action.'_'.$slug
    );
    $class = 'notice notice-error is-dismissible';

    $message = sprintf( __("This theme was designed to work with the GeoDirectory plugin! <a href='%s' >Click here to install it.</a>", 'supreme-directory'), $install_url ) ;

    printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
    }
    
}


/**
 * Add body classes to the HTML where needed.
 *
 * @since 0.0.1
 * @param array $classes The array of body classes.
 * @return array The array of body classes.
 */
function sd_custom_body_class($classes)
{
    // add 'sd' to the default autogenerated classes, for this we need to modify the $classes array.
    $classes[] = 'sd';
   
    // return the modified $classes array
    return $classes;
}
add_filter('body_class', 'sd_custom_body_class');

/*
 * Throw admin notice and stop loading the theme if GeoDirectory plugin is not installed.
 */
if (!defined('GEODIRECTORY_VERSION')) {
    add_action('admin_notices', 'geodir_sd_force_update_remove_notice');
   // return;
}else{
    include_once('inc/geodirectory-compatibility.php'); 
}

/**
 * Adds the CSS and JS for the theme.
 *
 * @since 1.0.0
 */
function sd_enqueue_styles()
{
    wp_enqueue_script('supreme', get_stylesheet_directory_uri() . '/js/supreme.js', array(), SD_VERSION, true);
    wp_enqueue_style('directory-theme-child-style', get_stylesheet_uri(), array('directory-theme-style', 'directory-theme-style-responsive'));

}

add_action('wp_enqueue_scripts', 'sd_enqueue_styles');


/**
 * Loads the translation files for wordpress.
 *
 * @since 1.0.0
 */
function sd_theme_setup()
{
    load_child_theme_textdomain( SD_CHILD, get_stylesheet_directory() . '/languages' );
}

add_action('after_setup_theme', 'sd_theme_setup');



/*################################
      BLOG FUNCTONS
##################################*/

/**
 * Redesign entry metas for blog entries template.
 *
 * @since 1.0.0
 */
function supreme_entry_meta()
{
    if (is_sticky() && is_home() && !is_paged()) {
        printf('<span class="sticky-post">%s</span>', __('Featured', 'supreme-directory'));
    }

    $format = get_post_format();
    if (current_theme_supports('post-formats', $format)) {
        printf('<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
            sprintf('<span class="screen-reader-text">%s </span>', _x('Format', 'Used before post format.', 'supreme-directory')),
            esc_url(get_post_format_link($format)),
            get_post_format_string($format)
        );
    }

    if (in_array(get_post_type(), array('post', 'attachment'))) {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        $time_string = sprintf($time_string,
            esc_attr(get_the_date('c')),
            get_the_date(),
            esc_attr(get_the_modified_date('c')),
            get_the_modified_date()
        );

        printf('<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
            _x('Posted on', 'Used before publish date.', 'supreme-directory'),
            esc_url(get_permalink()),
            $time_string
        );
    }

    if ('post' == get_post_type()) {
        if (is_singular() || is_multi_author()) {
            printf('<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
                _x('Author', 'Used before post author name.', 'supreme-directory'),
                esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                get_the_author()
            );
        }

        $categories_list = get_the_category_list(_x(', ', 'Used between list items, there is a space after the comma.', 'supreme-directory'));
        if ($categories_list) {
            printf('<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x('Categories', 'Used before category names.', 'supreme-directory'),
                $categories_list
            );
        }

        $tags_list = get_the_tag_list('', _x(', ', 'Used between list items, there is a space after the comma.', 'supreme-directory'));
        if ($tags_list) {
            printf('<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x('Tags', 'Used before tag names.', 'supreme-directory'),
                $tags_list
            );
        }
    }

}

/**
 * Add My Account link to the main menu.
 *
 * @param string $items The HTML list content for the menu items.
 * @param object $args An object containing wp_nav_menu() arguments.
 * @return string The menu items HTML.
 * @since 1.0.0
 */
function sd_add_my_account_link($items, $args)
{
    if ($args->theme_location == 'primary-menu') {
        ob_start();
        ?>
        <li  class="sd-my-account menu-item">
            <?php
        if (is_user_logged_in()) {

            $current_user = wp_get_current_user();
            $avatar = get_avatar($current_user->ID, 60);
            ?>
            <a class="sd-my-account-link" href="">
                <?php
                echo $avatar;
                if (class_exists('BuddyPress')) {
                    $user_link = bp_get_loggedin_user_link();
                } else {
                    $user_link = get_author_posts_url($current_user->ID);
                }
                ?>
                <?php echo __('My Account', 'supreme-directory'); ?>
                <i class="fa fa-caret-down"></i>
            </a>
            <div id="sd-my-account" class="Panel">
            <div class="mm-subtitle"><a class="mm-subclose" href="#mm-menu-sd-menu"><?php _e('<  Back','supreme-directory');?></a></div>
            <div class="sd-my-account-dd">
                <div class="sd-my-account-dd-inner">
                    <div class="sd-dd-avatar-wrap">
                        <a href="<?php echo $user_link; ?>" rel="nofollow"><?php echo $avatar; ?></a>
                        <h4>
                            <a href="<?php echo $user_link; ?>"
                               rel="nofollow"><?php echo $current_user->display_name; ?></a>
                        </h4>
                    </div>
                    <?php if (class_exists('BuddyPress')) { ?>
                        <ul class="sd-my-account-dd-menu-group sd-my-account-dd-menu-bp-group">
                            <li class="sd-my-account-dd-menu-link">
                                <a href="<?php echo $user_link; ?>">
                                    <i class="fa fa-user"></i> <?php echo __('About Me', 'supreme-directory'); ?>
                                </a>
                            </li>
                            <li class="sd-my-account-dd-menu-link">
                                <a href="<?php echo $user_link . 'settings/'; ?>">
                                    <i class="fa fa-cog"></i> <?php echo __('Account Settings', 'supreme-directory'); ?>
                                </a>
                            </li>
                        </ul>
                    <?php } ?>
                    <ul class="sd-my-account-dd-menu-group">
                        <li class="sd-my-account-dd-menu-link">
                            <a href="<?php echo wp_logout_url(home_url()); ?>">
                                <i class="fa fa-sign-out"></i> <?php echo __('Log Out', 'supreme-directory'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            </div>

        <?php } else {
            if (isset($_GET['redirect_to']) && $_GET['redirect_to'] != '') {
                $redirect_to = $_GET['redirect_to'];
            } else {
                //echo $_SERVER['HTTP_HOST'] ;
                $redirect_to = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                if (strpos($redirect_to, $_SERVER['HTTP_HOST']) === false) {
                    $redirect_to = home_url();
                }

            }
            ?>
            <a class="sd-my-account-link" href="">
                <?php echo __('My Account', 'supreme-directory'); ?>
                <i class="fa fa-caret-down"></i>
            </a>
            <div id="sd-my-account" class="Panel">
            <div class="mm-subtitle"><a class="mm-subclose" href="#mm-menu-sd-menu"><?php _e('<  Back','supreme-directory');?></a></div>
            <div class="sd-my-account-dd">
                <div class="sd-my-account-dd-inner">
                    <h4 class="sd-my-account-title"><?php echo __('Sign In', 'supreme-directory'); ?></h4>
                    <?php
                    if (isset($_REQUEST['emsg']) && $_REQUEST['emsg'] == 'fw') {
                        echo "<p class=\"error_msg\"> " . __('Invalid Email, Please check', 'supreme-directory') . " </p>";
                    } elseif (isset($_REQUEST['logemsg']) && $_REQUEST['logemsg'] == 1) {
                        echo "<p class=\"error_msg\"> " . INVALID_USER_PW_MSG . " </p>";
                    }

                    if (isset($_REQUEST['checkemail']) && $_REQUEST['checkemail'] == 'confirm')
                        echo '<p class="sucess_msg">' . __('Invalid Username/Password.', 'supreme-directory') . '</p>';

                    ?>
                    <form name="cus_loginform" method="post">

                        <div class="form_row clearfix">
                            <input placeholder='<?php _e('Email', 'supreme-directory'); ?>' type="text" name="log" id="user_login"
                                   value="<?php global $user_login;
                                   if (!isset($user_login)) {
                                       $user_login = '';
                                   }
                                   echo esc_attr($user_login); ?>" size="20" class="textfield"/>
                            <span class="user_loginInfo"></span>
                        </div>

                        <div class="form_row clearfix">
                            <input placeholder='<?php _e('Password', 'supreme-directory'); ?>' type="password" name="pwd"
                                   id="user_pass"
                                   class="textfield input-text" value="" size="20"/>
                            <span class="user_passInfo"></span>
                        </div>

                        <p class="rember">
                            <input name="rememberme" type="checkbox" id="rememberme" value="forever" class="fl"/>
                            <?php _e('Remember me on this computer', 'supreme-directory'); ?>
                        </p>

                        <input class="geodir_button" type="submit" value="<?php _e('Sign In', 'supreme-directory'); ?>"
                               name="submit"/>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>"/>
                        <input type="hidden" name="testcookie" value="1"/>

                        <p class="sd-register">
                        <a href="<?php echo sd_login_url(array('signup' => true)); ?>"
                           class="goedir-newuser-link"><?php _e('Register', 'supreme-directory'); ?></a>
                           <a href="<?php echo sd_login_url(array('forgot' => true)); ?>"
                       class="goedir-forgot-link"><?php _e('Forgot Password?', 'supreme-directory'); ?></a>
                        </p>
                        <?php do_action('login_form'); ?>
                    </form>
                </div>
            </div>
            </div>
        <?php
        }
        ?>
        </li>
        <?php
        $html = ob_get_clean();
        $items .= $html;
    }
    return $items;
}

add_filter('wp_nav_menu_items', 'sd_add_my_account_link', 1001, 2);


function sd_login_url($params){
if(function_exists('geodir_login_url')){
    return geodir_login_url($params);
}else{
    return wp_login_url();
}

}

/**
 * Runs on theme activation.
 *
 * @since 1.0.0
 */
function sd_theme_activation()
{
    //set the theme mod heights/settings
    sd_set_theme_mods();
    // add some page and set them if default settings set
    sd_activation_install();
}

add_action('after_switch_theme', 'sd_theme_activation');

/**
 * Sets the default setting for the theme.
 *
 * @since 1.0.0
 */
function sd_set_theme_mods()
{

    $ds_theme_mods = get_theme_mods();
    if (!empty($ds_theme_mods) && !get_option('ds_theme_mod_backup')) {
        update_option('ds_theme_mod_backup', $ds_theme_mods);
    }

    $sd_theme_mods = array(
        "dt_header_height" => "61px",
        "dt_p_nav_height" => "61px",
        "dt_p_nav_line_height" => "61px",
        "logo" => get_stylesheet_directory_uri() . "/images/logo.png"
    );

    /**
     * Parse incoming $args into an array and merge it with defaults
     */
    $sd_theme_mods = wp_parse_args($ds_theme_mods, $sd_theme_mods);

    foreach ($sd_theme_mods as $key => $val) {
        set_theme_mod($key, $val);
    }

}

/**
 * Sets the default settings if they are not already set.
 *
 * @since 1.0.0
 */
function sd_activation_install()
{
    // if Hello World post is not published then we bail
    if (get_post_status(1) != 'publish' || get_the_title(1)!=__('Hello world!','supreme-directory')) {
        return;
    }

    // Use a static front page
    //delete_option('sd-installed'); // @todo remove, only for testing
    $is_installed = get_option('sd-installed');
    if (!$is_installed) {
        // install pages

        // Insert the home page into the database if not exists
        $home = get_page_by_title('Find Local Treasures!');
        if (!$home) {
            // Set the home page
            $sd_home = array(
                'post_title' => __('Find Local Treasures!', 'supreme-directory'),
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page'
            );
            $home_page_id = wp_insert_post($sd_home);
            if ($home_page_id) {
                update_post_meta($home_page_id, 'subtitle', __('Discover the best places to stay, eat, shop and events near you.', 'supreme-directory'));
                update_option('page_on_front', $home_page_id);
                update_option('show_on_front', 'page');
            }
        }

        // Insert the blog page into the database if not exists
        $blog = get_page_by_title('Blog');
        if (!$blog) {
            // Set the blog page
            $sd_blog = array(
                'post_title' => __('Blog', 'supreme-directory'),
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page'
            );
            $blog_page_id = wp_insert_post($sd_blog);
            if ($blog_page_id) {
                update_option('page_for_posts', $blog_page_id);
            }
        }


        // remove some widgets for clean look
        $sidebars_widgets = get_option('sidebars_widgets');
        if (isset($sidebars_widgets['geodir_listing_top'])) {
            $sidebars_widgets['geodir_listing_top'] = array();
        }
        if (isset($sidebars_widgets['geodir_search_top'])) {
            $sidebars_widgets['geodir_search_top'] = array();
        }
        if (isset($sidebars_widgets['geodir_detail_sidebar'])) {
            $sidebars_widgets['geodir_detail_sidebar'] = array();
        }
        update_option('sidebars_widgets', $sidebars_widgets);


        // set the menu if it doew not exist
        // Check if the menu exists
        $menu_name = 'SD Menu';
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        // If it doesn't exist, let's create it.
        if( !$menu_exists){
            $menu_id = wp_create_nav_menu($menu_name);

            // Set up default menu items
            $home_page_menu_id = (isset($home_page_id)) ? $home_page_id : $home->ID;


            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' =>  __('Home','supreme-directory'),
                'menu-item-classes' => 'home',
                'menu-item-object' => 'page',
                'menu-item-object-id' => $home_page_menu_id,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'));

            $blog_page_menu_id = (isset($blog_page_id)) ? $blog_page_id : $blog->ID;
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' =>  __('Blog','supreme-directory'),
                'menu-item-object' => 'page',
                'menu-item-object-id' => $blog_page_menu_id,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'));


            // if no primary-menu is set then set one
            if ( $menu_id && !has_nav_menu( 'primary-menu' ) ) {
                set_theme_mod( 'nav_menu_locations', array('primary-menu' => $menu_id));
                update_option('geodir_theme_location_nav',array('primary-menu'));
                set_theme_mod('dt_logo_margin_top', '20px');
            }

        }


        // set the map pin to bounce on listing hover on listings pages
        update_option('geodir_listing_hover_bounce_map_pin', 1);
        // set the advanced paging to show on listings pages
        update_option('geodir_pagination_advance_info', 'before');
        // set the details page to use list and not tabs
        update_option('geodir_disable_tabs', '1');
        // disable some details page tabs that we show in the sidebar
        update_option('geodir_detail_page_tabs_excluded', array('post_images','post_map','related_listing'));
        // Set the installed flag
        update_option('sd-installed', true);

    }

}

function sd_footer_widget_class($classes)
{
    return "col-lg-3 col-md-4";
}

add_filter('dt_footer_widget_class', 'sd_footer_widget_class');

//Remove Header Top from directory starter
function sd_dt_remove_header_top_from_customizer( $wp_customize ) {
    $wp_customize->remove_section( 'dt_header_top_section' );
}
add_action( 'customize_register', 'sd_dt_remove_header_top_from_customizer', 20);

function sd_dt_enable_header_top_return_zero() {
    return "0";
}
add_filter('theme_mod_dt_enable_header_top', 'sd_dt_enable_header_top_return_zero');


/**
 * This function fixes scroll bar issue by resizing window.
 *
 * In safari scroll bar are not working properly when the user click back button.
 * This function fixes that issue by resizing window.
 * Refer this thread https://wpgeodirectory.com/support/topic/possible-bug/
 */
function sd_safari_back_button_scroll_fix() {
    if (geodir_is_page('listing') || geodir_is_page('search')) {
    ?>
    <script type="text/javascript">
        jQuery( document ).ready(function() {
            var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
            var is_safari = navigator.userAgent.indexOf("Safari") > -1 && !is_chrome;
            if (is_safari) {
                window.onpageshow = function(event) {
                    if (event.persisted) {
                        jQuery(window).trigger('resize');
                    }
                };
            }
        });

    </script>
    <?php
    }
}
add_filter('wp_footer', 'sd_safari_back_button_scroll_fix');

