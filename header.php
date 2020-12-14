<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js bsui">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if(function_exists('wp_body_open')){wp_body_open();}
do_action('dt_before_header');

$enable_header_top = esc_attr(get_theme_mod('dt_enable_header_top', DT_ENABLE_HEADER_TOP));
if ($enable_header_top == '1') {
	$extra_class = 'dt-header-top-enabled';
} else {
	$extra_class = '';
}
?>
<header id="site-header" class="site-header <?php echo apply_filters('dt_header_extra_class', $extra_class); ?> <?php echo esc_attr( get_theme_mod('dt_header_shadow', DT_HEADER_SHADOW) ); ?>" role="banner" style="<?php echo dt_header_image(); ?>">
	<nav class="navbar navbar-expand-lg navbar-dark navbar-multi-sub-menus" style="z-index: 1025;">
		<?php
		/**
		 * This action is called before the site logo wrapper.
		 *
		 * @since 1.0.2
		 */
		do_action('dt_before_site_logo');?>

		<?php if ( get_theme_mod( 'logo', false ) ) : ?>
			<div class='navbar-brand'>
				<a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'><img src='<?php echo esc_url( get_theme_mod( 'logo', false ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>
			</div>
		<?php else : ?>
			<?php
			if ( display_header_text() )
				$style = ' style="color:#' . get_header_textcolor() . ' !important;"';
			else
				$style = ' style="display:none;"';

			if ( display_header_text() ) : ?>
				<?php
				$desc = get_bloginfo( 'description', 'display' );
				$class = '';
				if (!$desc) {
					$class = 'site-title-no-desc';
				}
				?>
				<hgroup>
					<h1 class='site-title h2 mb-0  <?php echo $class; ?>'>
						<a class="navbar-brand" <?php echo $style; ?> href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'><?php bloginfo( 'name' ); ?></a>
					</h1>
					<?php
					if ($enable_header_top != '1') { ?>
						<div class="site-description h6">
							<a <?php echo $style; ?> href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( $desc ); ?>' rel='home'><?php echo $desc; ?></a>
						</div>
					<?php } ?>
				</hgroup>
			<?php endif; ?>
		<?php endif; ?>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#primary-nav" aria-controls="primary-nav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse " id="primary-nav">
			<div class="bg-dark h-100 w-100 position-absolute d-lg-none" style="z-index: -1;left:0;top:0"></div>
			<?php if ( has_nav_menu( 'primary-menu' ) ) {
					wp_nav_menu( array(
						'container'      => false,
						'theme_location' => 'primary-menu',
						'menu_class' => 'navbar-nav ml-auto text-nowrap flex-wrap'
					) );
				}
			?>
		</div>
	</nav>
</header>
<?php do_action('dt_after_header'); ?>