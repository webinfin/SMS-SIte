<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Testimonials with rotator widget
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Wptww_Script {
	
	function __construct() {
		
		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wptww_front_style') );
		
		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wptww_front_script') );
		
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package WP Testimonials with rotator widget
	 * @since 1.0.0
	 */
	function wptww_front_style() {
			
		// Registring font awesome style
			wp_register_style( 'wtwp-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', null, WTWP_VERSION );
			wp_enqueue_style( 'wtwp-font-awesome' );
			
		// Registring and enqueing slick css
		if( !wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WTWP_URL.'assets/css/slick.css', array(), WTWP_VERSION );
			wp_enqueue_style( 'wpos-slick-style');			}
		
		// Registring and enqueing public css
		wp_register_style( 'wptww-public-css', WTWP_URL.'assets/css/testimonials-style.css', null, WTWP_VERSION );
		wp_enqueue_style( 'wptww-public-css' );
	}	
	/**
	 * Function to add script at front side
	 * 
	 * @package WP Testimonials with rotator widget
	 * @since 1.0.0
	 */
	function wptww_front_script() {		
		// Registring slick slider script
		if( !wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WTWP_URL.'assets/js/slick.min.js', array('jquery'), WTWP_VERSION, true );
		}
	}
}

$wptww_script = new Wptww_Script();