<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Nummerize
	function hb_nummerize( $size ) {
		$let = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );
		switch( strtoupper( $let ) ) {
		case 'P':
		$ret *= 1024;
		case 'T':
		$ret *= 1024;
		case 'G':
		$ret *= 1024;
		case 'M':
		$ret *= 1024;
		case 'K':
		$ret *= 1024;
		}	
		return $ret;
	}
	
	$theme_data = "";
	$item_uri = "";
	$theme_name = "";
	$theme_author = "";
	$version = "";

	if (function_exists('wp_get_theme')){
        $theme_data = wp_get_theme();
        $item_uri = $theme_data->get('ThemeURI');
        $theme_name = $theme_data->get('Name');
        $version = $theme_data->get('Version');
        $theme_author = $theme_data->get('Author');
    }
?>

<div class="wrap">
  <h2><?php _e('Highend System Diagnostics', 'hbthemes'); ?></h2>
  <p class="about-p"><?php _e('Below information is useful to diagnose some of the possible reasons to malfunctions, performance issues or any errors.<br/>You can faciliate the process of support by providing below information to our support staff.', 'hbthemes'); ?></p>
 

 <table class="hb_diagnostics_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2"><strong><?php _e("Enviroment", "hbthemes"); ?></strong></th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td class="first"><?php _e( 'WP Multisite Enabled', 'hbthemes' ); ?>:</td>
			<td><?php if ( is_multisite() ) echo __( 'Yes', 'hbthemes' ); else echo __( 'No', 'hbthemes' ); ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'Web Server Info', 'hbthemes' ); ?>:</td>
			<td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'PHP Version', 'hbthemes' ); ?>:</td>
			<td><?php if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() ); ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'MySQL Version', 'hbthemes' ); ?>:</td>
			<td>
				<?php
				/** @global wpdb $wpdb */
				global $wpdb;
				echo $wpdb->db_version();
				?>
			</td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'WP Active Plugins', 'hbthemes' ); ?>:</td>
			<td><?php echo count( (array) get_option( 'active_plugins' ) ); ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'WP Memory Limit', 'woocommerce' ); ?>:</td>
			<td><?php
				$memory = hb_nummerize( WP_MEMORY_LIMIT );

				if ( $memory < 67108864 ) {
					echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 64MB. See: <a href="%s">Increasing memory allocated to PHP</a>', 'hbthemes' ), size_format( $memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
				} else {
					echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
				} if ( $memory > 67108864 ) { ?> <span class="info">If Highend Options are not saving changes - you need to increase your WP_MEMORY_LIMIT. Please follow <a href="http://hb-themes.com/forum/all/topic/theme-options-not-saving-changes-resolved-fix/" target="_blank">these steps.</a></span> <?php } ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'WP Debug Mode', 'hbthemes' ); ?>:</td>
			<td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . __( 'Yes', 'hbthemes' ) . '</mark>'; else echo '<mark class="no">' . __( 'No', 'hbthemes' ) . '</mark>'; ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'WP Language', 'hbthemes' ); ?>:</td>
			<td><?php echo get_locale(); ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e( 'WP Max Upload Size', 'hbthemes' ); ?>:</td>
			<td><?php echo size_format( wp_max_upload_size() ); ?></td>
		</tr>
		<?php if ( function_exists( 'ini_get' ) ) : ?>
			<tr>
				<td class="first"><?php _e('PHP Post Max Size', 'hbthemes' ); ?>:</td>
				<td><?php echo size_format( hb_nummerize( ini_get('post_max_size') ) ); ?></td>
			</tr>
			<tr>
				<td class="first"><?php _e('PHP Time Limit', 'hbthemes' ); ?>:</td>
				<td><?php echo ini_get('max_execution_time'); ?><span class="info"><?php _e('Recommended max_execution_time should be at least 400 seconds.','hbthemes'); ?></td>
			</tr>
			<tr>
				<td class="first"><?php _e( 'PHP Max Input Time', 'hbthemes' ); ?>:</td>
				<td><?php echo ini_get('max_input_time'); ?><span class="info"><?php _e('Recommended max_input_time should be at least 300 seconds.','hbthemes'); ?></span></td>
			</tr>
			<tr>
				<td class="first"><?php _e( 'PHP Max Input Vars', 'hbthemes' ); ?>:</td>
				<td><?php echo ini_get('max_input_vars'); ?></td>
			</tr>
			<tr>
				<td class="first"><?php _e( 'SUHOSIN Installed', 'hbthemes' ); ?>:</td>
				<td><?php echo extension_loaded( 'suhosin' ) ? __( 'Yes', 'woocommerce' ) : __( 'No', 'woocommerce' ); ?></td>
			</tr>
		<?php endif; ?>
	</tbody>
 </table>

<table class="hb_diagnostics_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2"><strong><?php _e("Theme", "hbthemes"); ?></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="first"><?php _e("Theme Name", "hbthemes") ?>: </td>
			<td><?php echo $theme_name; ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e("Theme Version", "hbthemes") ?>: </td>
			<td><?php echo $version; ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e("Theme Author", "hbthemes") ?>: </td>
			<td><?php echo $theme_author; ?></td>
		</tr>
		<tr>
			<td class="first"><?php _e("Theme Demo", "hbthemes") ?>: </td>
			<td><?php echo $item_uri ?></td>
		</tr>
		<tr>
				<td class="first"><?php _e( 'Is Child Theme', 'hbthemes' ); ?>:</td>
				<td><?php echo is_child_theme() ? '<span>'.__( 'Yes', 'hbthemes' ).'</span>' : '<span>'.__( 'No', 'hbthemes' ).'</span>'; ?></td>
			</tr>
	</tbody>

  </table>

	<p class="about-description"><a href="http://forum.hb-themes.com" target="_blank" class="button button-hero button-primary"><?php _e('Visit Support Forum','hbthemes'); ?></a></p>

 </div>