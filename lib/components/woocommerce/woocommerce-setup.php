<?php
/**
 * Adds the required WooCommerce setup functions.
 *
 * @package     RuyFialho\Developers\WooCommerce
 * @since       1.0.0
 * @author      ruyfialho
 * @link        https://ruyfialho.com
 * @license     GNU General Public License 2.0+
 */
namespace RuyFialho\Developers\WooCommerce;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\match_products_height', 99 );
/**
 * Print an inline script to the footer to keep products the same height.
 *
 * @since 1.0.0
 */
function match_products_height() {

	// If Woocommerce is not activated, or a product page isn't showing, exit early.
	if ( ! class_exists( 'WooCommerce' ) || ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}

	wp_enqueue_script( 'developers-match-height', CHILD_THEME_URL . '/assets/js/jquery.matchHeight.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_add_inline_script( 'developers-match-height', "jQuery(document).ready( function() { jQuery( '.product .woocommerce-LoopProduct-link').matchHeight(); });" );

}

add_filter( 'woocommerce_style_smallscreen_breakpoint', __NAMESPACE__ . '\modify_woocommerce_breakpoints' );
/**
 * Modify the WooCommerce breakpoints.
 *
 * @since 1.0.0
 *
 * @return string Pixel width of the theme's breakpoint.
 */
function modify_woocommerce_breakpoints() {

	$current = genesis_site_layout();
	$layouts = array(
		'one-sidebar' => array(
			'content-sidebar',
			'sidebar-content',
		),
		'two-sidebar' => array(
			'content-sidebar-sidebar',
			'sidebar-content-sidebar',
			'sidebar-sidebar-content',
		),
	);

	if ( in_array( $current, $layouts['two-sidebar'] ) ) {
		return '2000px'; // Show mobile styles immediately.
	}
	elseif ( in_array( $current, $layouts['one-sidebar'] ) ) {
		return '1200px';
	}
	else {
		return '860px';
	}

}

add_filter( 'genesiswooc_products_per_page', __NAMESPACE__ . '\set_default_products_per_page' );
/**
 * Set the default products per page.
 *
 * @since 1.0.0
 *
 * @return int Number of products to show per page.
 */
function set_default_products_per_page() {
	return 8;
}

add_filter( 'woocommerce_pagination_args', __NAMESPACE__ . '\update_woocommerce_pagination_to_default_genesis_style' );
/**
 * Update the next and previous arrows to the default Genesis style.
 *
 * @since 1.0.0
 *
 * @return string New next and previous text string.
 */
function update_woocommerce_pagination_to_default_genesis_style( $args ) {

	$args['prev_text'] = sprintf( '&laquo; %s', __( 'Previous Page', CHILD_TEXT_DOMAIN ) );
	$args['next_text'] = sprintf( '%s &raquo;', __( 'Next Page', CHILD_TEXT_DOMAIN ) );

	return $args;

}

add_action( 'after_switch_theme', __NAMESPACE__ . '\define_woocommerce_image_sizes_on_theme_activation', 1 );
/**
* Define WooCommerce image sizes on theme activation.
*
* @since 1.0.0
*/
function define_woocommerce_image_sizes_on_theme_activation() {

	global $pagenow;

	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	update_woocommerce_image_sizes();

}

add_action( 'activated_plugin', __NAMESPACE__ . '\define_woocommerce_image_sizes_on_woocommerce_activation', 10, 2 );
/**
 * Define the WooCommerce image sizes on WooCommerce activation.
 *
 * @since 1.0.0
 */
function define_woocommerce_image_sizes_on_woocommerce_activation( $plugin ) {

	// Check to see if WooCommerce is being activated.
	if ( $plugin !== 'woocommerce/woocommerce.php' ) {
		return;
	}

	update_woocommerce_image_sizes();

}

/**
 * Update WooCommerce image dimensions.
 *
 * @since 1.0.0
 */
function update_woocommerce_image_sizes() {

	$catalog = array(
		'width'  => '500', // px
		'height' => '500', // px
		'crop'   => 1,     // true
	);
	$single = array(
		'width'  => '655', // px
		'height' => '655', // px
		'crop'   => 1,     // true
	);
	$thumbnail = array(
		'width'  => '180', // px
		'height' => '180', // px
		'crop'   => 1,     // true
	);

	// Image sizes.
	update_option( 'shop_catalog_image_size', $catalog );     // Product category thumbs.
	update_option( 'shop_single_image_size', $single );       // Single product image.
	update_option( 'shop_thumbnail_image_size', $thumbnail ); // Image gallery thumbs.

}
