<?php
/**
 * Plugin Name: WP Testimonials with rotator widget
 * Plugin URI: http://www.wponlinesupport.com/
 * Text Domain: wp-testimonial-with-widget
 * Domain Path: /languages/
 * Description: Easy to add and display client's testimonial on your website with rotator widget. 
 * Author: WP Online Support
 * Version: 2.2.6
 * Author URI: http://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author WP Online Support
 */

if( !defined( 'WTWP_VERSION' ) ) {
    define( 'WTWP_VERSION', '2.2.6' ); // Version of plugin
}
if( !defined( 'WTWP_DIR' ) ) {
    define( 'WTWP_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WTWP_URL' ) ) {
    define( 'WTWP_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WTWP_POST_TYPE' ) ) {
    define( 'WTWP_POST_TYPE', 'testimonial' ); // Plugin post type
}
/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package WP Testimonials with rotator widget
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wtwp_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package WP Testimonials with rotator widget
 * @since 1.0.0
 */
function wtwp_install() {
	// To deactivate the free version of plugin
	if( is_plugin_active('wp-testimonial-with-widget-pro/wp-testimonials.php') ){
     	add_action( 'update_option_active_plugins', 'wtwp_deactivate_version' );
    }
}

/**
 * Function to deactivate the free version plugin
 * 
 * @package WP Testimonials with rotator widget
 * @since 1.0.0
 */
function wtwp_deactivate_version(){
	deactivate_plugins( 'wp-testimonial-with-widget-pro/wp-testimonials.php', true );
}

// Action to add admin notice
add_action( 'admin_notices', 'wtwp_admin_notice');

/**
 * Admin notice
 * 
 * @package WP Testimonials with rotator widget
 * @since 1.0.0
 */
function wtwp_admin_notice() {

    $dir = ABSPATH . 'wp-content/plugins/wp-testimonial-with-widget-pro/wp-testimonials.php';
    
    if( is_plugin_active( 'wp-testimonial-with-widget/wp-testimonials.php' ) && file_exists($dir) ) {
        global $pagenow;
        if( $pagenow == 'plugins.php' ) {
            
            deactivate_plugins ( 'wp-testimonial-with-widget-pro/wp-testimonials.php',true);

            if ( current_user_can( 'install_plugins' ) ) {
                echo '<div id="message" class="updated notice is-dismissible"><p><strong>Thank you for activating WP Testimonials with rotator widget</strong>.<br /> It looks like you had PRO version <strong>(<em>WP Testimonials with rotator widget Pro</em>)</strong> of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it. </p></div>';
            }
        }
    }
}

add_action('plugins_loaded', 'wp_testimonialsandw_load_textdomain');
function wp_testimonialsandw_load_textdomain() {
	load_plugin_textdomain( 'wp-testimonial-with-widget', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
} 

/**
 * Function to get plugin image sizes array
 * 
 * @package WP Testimonials with rotator widget
 * @since 2.2.4
 */
function wtwp_get_unique() {
    static $unique = 0;
    $unique++;

    return $unique;
}

//Script file
require_once( WTWP_DIR . '/includes/class-wptww-script.php' );

//Function file file
require_once( WTWP_DIR . '/includes/testimonials-functions.php' );

//Widget Post Type file
require_once( WTWP_DIR . '/includes/wptww-post-types.php' );

//Widget file file
require_once( WTWP_DIR . '/includes/widget/wp-widget-testimonials.php' );

//Templates files file file
require_once( WTWP_DIR . '/includes/shortcodes/wp-testimonials-template.php' );
require_once( WTWP_DIR . '/includes/shortcodes/wp-testimonial-slider-template.php' );

require_once( WTWP_DIR . '/includes/testimonials_menu_function.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( WTWP_DIR . '/includes/admin/wptww-how-it-work.php' );
}