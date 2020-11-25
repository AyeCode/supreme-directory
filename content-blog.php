<?php
if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
	$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
} else {
	$full_image_url[0] = SD_DEFAULT_FEATURED_IMAGE;
}
?>
<article <?php post_class( 'col-md-4 p-3' ); ?>>
	<div class="card bg-white border-light p-3 rounded">
		<a href="<?php the_permalink(); ?>"><img class="card-img" src="<?php echo esc_url( $full_image_url[0] ); ?>" alt="<?php the_title(); ?>"></a>
		<div class="card-body p-0 pt-3">
			<a href="<?php the_permalink(); ?>" class="card-title h4"><?php the_title(); ?></a>
			<p class="card-text mt-3">
				<?php directory_theme_entry_meta( 'text-dark' ); //supreme_entry_meta(); ?>
			</p>
			<a href="<?php the_permalink(); ?>" class="btn btn-dark btn-round btn-sm"><?php _e("View Post","supreme-directory"); ?></a>
		</div>
	</div>
</article>