<?php
function display_slider_shortcode() {
	ob_start();

	$query = new WP_Query(
		array(
			'post_type'      => 'slider',
			'posts_per_page' => -1,
		)
	);

	if ( $query->have_posts() ) :
		?>
	<div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper">

		<div class="swiper-wrapper">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<div class="swiper-slide">
					<?php
					// Output the slider image.
					$image_id = get_post_meta( get_the_ID(), '_custom_image_id', true );
					if ( $image_id ) {
						echo wp_get_attachment_image( $image_id, 'full' );
					}
					?>
				</div>
			<?php endwhile; ?>
		</div>
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
		<div class="swiper-pagination"></div>
	</div>
		<?php
	endif;

	wp_reset_postdata();

	$output = ob_get_clean();

	return $output;
}
add_shortcode( 'display_slider', 'display_slider_shortcode' );
