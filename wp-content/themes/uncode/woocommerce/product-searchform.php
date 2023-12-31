<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dwls_live_search = get_option('daves-wordpress-live-search_uncode_activate_widget');
?>

<form method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<div class="search-container-inner">
		<label class="screen-reader-text" for="s"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
		<input type="search" class="search-field<?php echo esc_attr( $dwls_live_search ? '' : ' no-livesearch' ); ?> <?php echo esc_attr( uncode_wc_wp_theme_get_element_class_name( 'button' ) ); ?>" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_html_x( 'Search', 'submit button', 'woocommerce' ); ?>" />
		<i class="fa fa-search3"></i>
		<input type="hidden" name="post_type" value="product" />
	</div>
</form>
