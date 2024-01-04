<?php
/**
 * Plugin Name:       Pk Slider
 * Description:       This is a simple image slider.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Palash
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pk-slider
 *
 * @package           create-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Register Post Type
 */
require plugin_dir_path( __FILE__ ) . 'includes/register-post.php';

/**
 * Function For Image Meta Box
 */
function custom_image_meta_box() {
	add_meta_box(
		'custom_image_meta_box',
		'Image Details',
		'custom_image_meta_box_callback',
		'slider',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'custom_image_meta_box' );

/**
 * Callback function
 */
function custom_image_meta_box_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'custom_image_meta_box_nonce' );
	$image_id = get_post_meta( $post->ID, '_custom_image_id', true );
	if ( ! $image_id ) {
		$image_id = '';     }
	?>
	Upload Image:
	<br />
	<input type="text" id="custom_image_upload" name="custom_image_upload" value="<?php echo esc_attr( $image_id ); ?>" style="width: 70%;" readonly />
	<button type="button" class="button" id="custom_image_upload_button">Upload Image</button>

	<script>
		jQuery(document).ready(function ($) {
			var custom_image_frame;

			$('#custom_image_upload_button').on('click', function (e) {
				e.preventDefault();
				if (custom_image_frame) {
					custom_image_frame.open();
					return;
				}

				custom_image_frame = wp.media.frames.custom_image_frame = wp.media({
					title: 'Choose or Upload an Image',
					button: {
						text: 'Use this image',
					},
					multiple: false,
				});

				custom_image_frame.on('select', function () {
					var attachment = custom_image_frame.state().get('selection').first().toJSON();
					$('#custom_image_upload').val(attachment.id);
				});

				custom_image_frame.open();
			});
		});
	</script>
	<?php
}

/**
 * Enqueue Scripts
 */
function custom_image_enqueue_scripts() {
	wp_enqueue_media();
	wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array( 'jquery' ) );
	wp_enqueue_style( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array() );

	// Enqueue styles.
	wp_enqueue_style( 'pk-slider-styles', plugin_dir_url( __FILE__ ) . 'assets/css/pk-slider-style.css', array() );
	// Enqueue scripts.
	wp_enqueue_script( 'pk-slider-scripts', plugin_dir_url( __FILE__ ) . 'assets/scripts/pk-slider-script.js', array( 'jquery' ), '1.8.1', true );
	// Create and localize nonce for security checks
	$pk_slider_nonce = wp_create_nonce( 'wp_rest' );
	wp_localize_script(
		'pk-slider-scripts',
		'pk_slider',
		array(
			'nonce' => $pk_slider_nonce,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'custom_image_enqueue_scripts' );

/**
 * Save Meta Box data
 */
function custom_image_save_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['custom_image_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['custom_image_meta_box_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['custom_image_upload'] ) ) {
		update_post_meta( $post_id, '_custom_image_id', sanitize_text_field( $_POST['custom_image_upload'] ) );
	}
}

add_action( 'save_post', 'custom_image_save_meta_box_data' );


/**
 *  Include shortcode.php for frontend
 */
require plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';



/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function pk_slider_pk_slider_block_init() {
	register_block_type(
		__DIR__ . '/build',
		array(
			'render_callback' => 'render_on_frontend',
			
		)
	);
}
add_action( 'init', 'pk_slider_pk_slider_block_init' );



function render_on_frontend() {
	$shortcode = do_shortcode( shortcode_unautop( '[display_slider]' ) );
	return $shortcode;
}

// Add a new (custom) block category and show that category at the top
function create_block_custom_cat( $block_categories, $block_editor_context ) {

	if ( $block_editor_context->post ) {

		array_push(
			$block_categories,
			array(
				'slug'  => 'pk-slider',
				'title' => __( 'Pk Slider', 'pk-slider' ),
				'icon'  => '',
			)
		);

	}

	return $block_categories;
}

add_filter( 'block_categories_all', 'create_block_custom_cat', 999, 2 );



function custom_pkslider_api_endpoint() {
	register_rest_route(
		'custom/v1',
		'/pkslider/',
		array(
			'methods'             => 'GET',
			'callback'            => 'get_pkslider_list',
			'permission_callback' => '__return_true',
		)
	);
}

function get_pkslider_list() {
	$args = array(
		'post_type'      => 'slider',
		'posts_per_page' => -1,
	);

	$slider_posts = get_posts( $args );
	$slider_data  = array();

	foreach ( $slider_posts as $post ) {
		$image_id      = get_post_meta( $post->ID, '_custom_image_id', true );
		$image_url     = wp_get_attachment_url( $image_id );
		$slider_data[] = array(
			'ID'         => $post->ID,
			'post_title' => $post->post_title,
			'image_id'   => $image_id ,
			'image_url'  => $image_url,

		);
	}
	return $slider_data;
}

add_action( 'rest_api_init', 'custom_pkslider_api_endpoint' );


