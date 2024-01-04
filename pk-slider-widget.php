<?php
class Elementor_pk_slider_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pk_slider';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Pk Slider', 'pk-slider' );
	}

	// Widgets Icon.
	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	// Categories.
	public function get_categories() {
		return array( 'basic' );
	}

	// Keywords.
	public function get_keywords() {
		return array( 'pk-slider', 'pk', 'slider' );
	}

	protected function render() {

		$shortcode = do_shortcode( shortcode_unautop( '[display_slider]' ) );
		?>
			<div class="elementor-shortcode"><?php echo $shortcode; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?></div>
		<?php
	}
}




