<?php
/**
 * @package WordPress
 * @subpackage Highend
 */

add_action('wp_enqueue_scripts', 'hb_styles_setup');
if ( !function_exists('hb_styles_setup') ) {
	function hb_styles_setup () {
		//wp_enqueue_style( 'hb_styles', get_stylesheet_uri(), false, HB_THEME_VERSION, 'all' );

		if ( hb_options('hb_responsive') ) {
			wp_enqueue_style( 'hb_responsive', get_template_directory_uri() . '/css/responsive.css', false, HB_THEME_VERSION, 'all' );
		}

		wp_enqueue_style( 'hb_icomoon', get_template_directory_uri() . '/css/icomoon.css', false, HB_THEME_VERSION, 'all' );
	}
}
?>