<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package wp-testimonial-with-widget
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Action to add menu
add_action('admin_menu', 'wptww_register_design_page');

/**
 * Register plugin design page in admin menu
 * 
 * @package wp-testimonial-with-widget
 * @since 1.0.0
 */
function wptww_register_design_page() {
	add_submenu_page( 'edit.php?post_type='.WTWP_POST_TYPE, __('How it works, our plugins and offers', 'wp-testimonial-with-widget'), __('How It Works', 'wp-testimonial-with-widget'), 'manage_options', 'wptww-designs', 'wptww_designs_page' );
}

/**
 * Function to display plugin design HTML
 * 
 * @package wp-testimonial-with-widget
 * @since 1.0.0
 */
function wptww_designs_page() {

	$wpos_feed_tabs = wptww_help_tabs();
	$active_tab 	= isset($_GET['tab']) ? $_GET['tab'] : 'how-it-work';
?>
		
	<div class="wrap wptww-wrap">

		<h2 class="nav-tab-wrapper">
			<?php
			foreach ($wpos_feed_tabs as $tab_key => $tab_val) {
				$tab_name	= $tab_val['name'];
				$active_cls = ($tab_key == $active_tab) ? 'nav-tab-active' : '';
				$tab_link 	= add_query_arg( array( 'post_type' => WTWP_POST_TYPE, 'page' => 'wptww-designs', 'tab' => $tab_key), admin_url('edit.php') );
			?>

			<a class="nav-tab <?php echo $active_cls; ?>" href="<?php echo $tab_link; ?>"><?php echo $tab_name; ?></a>

			<?php } ?>
		</h2>
		
		<div class="wptww-tab-cnt-wrp">
		<?php
			if( isset($active_tab) && $active_tab == 'how-it-work' ) {
				wptww_howitwork_page();
			}
			else if( isset($active_tab) && $active_tab == 'plugins-feed' ) {
				echo wptww_get_plugin_design( 'plugins-feed' );
			} else {
				echo wptww_get_plugin_design( 'offers-feed' );
			}
		?>
		</div><!-- end .wptww-tab-cnt-wrp -->

	</div><!-- end .wptww-wrap -->

<?php
}

/**
 * Gets the plugin design part feed
 *
 * @package wp-testimonial-with-widget
 * @since 1.0.0
 */
function wptww_get_plugin_design( $feed_type = '' ) {
	
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';
	
	// If tab is not set then return
	if( empty($active_tab) ) {
		return false;
	}

	// Taking some variables
	$wpos_feed_tabs = wptww_help_tabs();
	$transient_key 	= isset($wpos_feed_tabs[$active_tab]['transient_key']) 	? $wpos_feed_tabs[$active_tab]['transient_key'] 	: 'wptww_' . $active_tab;
	$url 			= isset($wpos_feed_tabs[$active_tab]['url']) 			? $wpos_feed_tabs[$active_tab]['url'] 				: '';
	$transient_time = isset($wpos_feed_tabs[$active_tab]['transient_time']) ? $wpos_feed_tabs[$active_tab]['transient_time'] 	: 172800;
	$cache 			= get_transient( $transient_key );
	
	if ( false === $cache ) {
		
		$feed 			= wp_remote_get( esc_url_raw( $url ), array( 'timeout' => 120, 'sslverify' => false ) );
		$response_code 	= wp_remote_retrieve_response_code( $feed );
		
		if ( ! is_wp_error( $feed ) && $response_code == 200 ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( $transient_key, $cache, $transient_time );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the data from the server. Please try again later.', 'wp-testimonial-with-widget' ) . '</div>';
		}
	}
	return $cache;	
}

/**
 * Function to get plugin feed tabs
 *
 * @package wp-testimonial-with-widget
 * @since 1.0.0
 */
function wptww_help_tabs() {
	$wpos_feed_tabs = array(
						'how-it-work' 	=> array(
													'name' => __('How It Works', 'wp-testimonial-with-widget'),
												),
						'plugins-feed' 	=> array(
													'name' 				=> __('Our Plugins', 'wp-testimonial-with-widget'),
													'url'				=> 'https://wponlinesupport.com/plugin-data-api/plugins-data.php',
													'transient_key'		=> 'wpos_plugins_feed',
													'transient_time'	=> 172800
												),
						'offers-feed' 	=> array(
													'name'				=> __('WPOS Offers', 'wp-testimonial-with-widget'),
													'url'				=> 'https://wponlinesupport.com/plugin-data-api/wpos-offers.php',
													'transient_key'		=> 'wpos_offers_feed',
													'transient_time'	=> 86400,
												)
					);
	return $wpos_feed_tabs;
}

/**
 * Function to get 'How It Works' HTML
 *
 * @package wp-testimonial-with-widget
 * @since 1.0.0
 */
function wptww_howitwork_page() { ?>
	
	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box .postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wptww-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wptww-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
	</style>

	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
			
				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								
								<h3 class="hndle">
									<span><?php _e( 'How It Works - Display and shortcode', 'wp-testimonial-with-widget' ); ?></span>
								</h3>
								
								<div class="inside">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label><?php _e('Geeting Started', 'wp-testimonial-with-widget'); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1. Go to "WP Testimonials --> Add New".', 'wp-testimonial-with-widget'); ?></li>
														<li><?php _e('Step-2. Add  Testimonials title, description and images', 'wp-testimonial-with-widget'); ?></li>
														<li><?php _e('Step-3. Add Testimonial Details like Client Name, Job Title detials etc', 'wp-testimonial-with-widget'); ?></li>
														<li><?php _e('Step-4. Once added, press Publish button', 'wp-testimonial-with-widget'); ?></li>
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('How Shortcode Works', 'wp-testimonial-with-widget'); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1. Create a page like Testimonials OR What our client says.', 'wp-testimonial-with-widget'); ?></li>
														<li><?php _e('Step-2. Put below shortcode as per your need.', 'wp-testimonial-with-widget'); ?></li>
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('All Shortcodes', 'wp-testimonial-with-widget'); ?>:</label>
												</th>
												<td>
													<span class="wptww-shortcode-preview">[sp_testimonials]</span> – <?php _e('Display in grid with 4 designs', 'wp-testimonial-with-widget'); ?> <br />
													<span class="wptww-shortcode-preview">[sp_testimonials_slider]</span> – <?php _e('Display in slider with 4 designs', 'wp-testimonial-with-widget'); ?> <br />													
												</td>
											</tr>
											<tr>
												<th>
													<label><?php _e('Widget', 'wp-testimonial-with-widget'); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1. Go to Appearance --> Widget.', 'wp-testimonial-with-widget'); ?></li>
														<li><?php _e('Step-2. Use WP Testimonials Slider to display Testimonials in widget area with slider', 'wp-testimonial-with-widget'); ?></li>
													</ul>												
												</td>
											</tr>	
												
											<tr>
												<th>
													<label><?php _e('Need Support?', 'wp-testimonial-with-widget'); ?></label>
												</th>
												<td>
													<p><?php _e('Check plugin document for shortcode parameters and demo for designs.', 'wp-testimonial-with-widget'); ?></p> <br/>
													<a class="button button-primary" href="http://docs.wponlinesupport.com/wp-testimonials-with-rotator-widget/?utm_source=hp&event=doc" target="_blank"><?php _e('Documentation', 'wp-testimonial-with-widget'); ?></a>									
													<a class="button button-primary" href="http://demo.wponlinesupport.com/testimonial-demo/" target="_blank"><?php _e('Demo for Designs', 'wp-testimonial-with-widget'); ?></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-body-content -->
				
				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="metabox-holder wpos-pro-box">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox" style="">
									
								<h3 class="hndle">
									<span><?php _e( 'Upgrate to Pro', 'wp-testimonial-with-widget' ); ?></span>
								</h3>
								<div class="inside">										
									<ul class="wpos-list">
										<li>15 Designs</li>
										<li>Testimonial front-end form.</li>
										<li>Star rating</li>
										<li>Display testimonials using 15 testimonial widget designs.</li>
										<li>Display Testimonial categories wise.</li>										
										<li>Fully responsive</li>
										<li>100% Multi language</li>
									</ul>
									<a class="button button-primary wpos-button-full" href="https://www.wponlinesupport.com/wp-plugin/wp-testimonial-with-widget/?utm_source=hp&event=go_premium" target="_blank"><?php _e('Go Premium ', 'wp-testimonial-with-widget'); ?></a>	
									<p><a class="button button-primary wpos-button-full" href="http://demo.wponlinesupport.com/prodemo/pro-testimonials-with-rotator-widget/?utm_source=hp&event=pro_demo" target="_blank"><?php _e('View PRO Demo ', 'wp-testimonial-with-widget'); ?></a>	</p>								
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->

					<div class="metabox-holder wpos-pro-box">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<h3 class="hndle">
									<span><?php _e('Need PRO Support?', 'wp-testimonial-with-widget'); ?></span>
								</h3>
								<div class="inside">
									<p><?php _e('Hire our experts for WordPress website support.', 'wp-testimonial-with-widget'); ?></p>
									<p><a class="button button-primary wpos-button-full" href="https://www.wponlinesupport.com/projobs-support/?utm_source=hp&event=projobs" target="_blank"><?php _e('PRO Support', 'wp-testimonial-with-widget'); ?></a></p>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->

					<!-- Help to improve this plugin! -->
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
									<h3 class="hndle">
										<span><?php _e( 'Help to improve this plugin!', 'wp-testimonial-with-widget' ); ?></span>
									</h3>									
									<div class="inside">										
										<p><?php _e('Enjoyed this plugin? You can help by rate this plugin', 'wp-testimonial-with-widget'); ?> <a href="https://wordpress.org/support/plugin/wp-testimonial-with-widget/reviews/?filter=5" target="_blank"><?php _e('5 stars!', 'wp-testimonial-with-widget'); ?></a></p>
									</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-container-1 -->

			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div><!-- #post-box-container -->
<?php }