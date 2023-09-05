<?php
add_action('after_setup_theme', 'uncode_language_setup');
function uncode_language_setup()
{
	load_child_theme_textdomain('uncode', get_stylesheet_directory() . '/languages');
}

function theme_enqueue_styles()
{
	$production_mode = ot_get_option('_uncode_production');
	$resources_version = ($production_mode === 'on') ? null : rand();
	if ( function_exists('get_rocket_option') && ( get_rocket_option( 'remove_query_strings' ) || get_rocket_option( 'minify_css' ) || get_rocket_option( 'minify_js' ) ) ) {
		$resources_version = null;
	}
	$parent_style = 'uncode-style';
	$child_style = array('uncode-style');
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/library/css/style.css', array(), $resources_version);
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', $child_style, $resources_version);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles', 100);
function iconic_format_price_range( $price, $from, $to ) {
    return sprintf( '%s %s', __( 'À partir de ', 'iconic' ), wc_price( $from ) );
}
add_filter( 'woocommerce_format_price_range', 'iconic_format_price_range', 10, 3 );


// keep users logged in for longer in wordpress
function wcs_users_logged_in_longer( $expirein ) {
    // 6 month in seconds
    return 15552000;
}
add_filter( 'auth_cookie_expiration', 'wcs_users_logged_in_longer' );

