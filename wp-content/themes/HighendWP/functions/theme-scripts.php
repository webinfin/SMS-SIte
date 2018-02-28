<?php
/**
 * @package WordPress
 * @subpackage Highend
 */

add_action('wp_enqueue_scripts', 'hb_scripts_setup');
if ( !function_exists('hb_scripts_setup') ) {
	function hb_scripts_setup () {

		if(!is_admin()){
			$theme_path = get_template_directory_uri();

			wp_enqueue_script( 'hb_scripts', $theme_path . '/scripts/scripts.js', array( 'jquery' ), HB_THEME_VERSION, true );
			wp_enqueue_script( 'hb_scrollto', $theme_path . '/scripts/jquery.scrollTo.js', array( 'jquery' ), HB_THEME_VERSION, true );
			wp_enqueue_script( 'hb_countdown', $theme_path . '/scripts/jquery.countdown.js', array('jquery'), HB_THEME_VERSION, true );


			if (!hb_is_maintenance()){			
				wp_enqueue_script( 'hb_gmap', '//www.google.com/jsapi', null, HB_THEME_VERSION, true );
				wp_enqueue_script( 'hb_map', $theme_path . '/scripts/map.js', array('jquery', 'hb_gmap'), HB_THEME_VERSION, true );
				wp_enqueue_script( 'hb_mediaelement', $theme_path . '/scripts/mediaelement/mediaelement.js', array('jquery'), HB_THEME_VERSION, true );
				wp_enqueue_script( 'hb_flexslider', $theme_path . '/scripts/jquery.flexslider.js', array('jquery'), HB_THEME_VERSION, true );
				wp_enqueue_script( 'hb_validate', $theme_path . '/scripts/jquery.validate.js', array('jquery'), HB_THEME_VERSION, true );
				wp_enqueue_script( 'hb_easychart', $theme_path . '/scripts/jquery.easychart.js', array('jquery'), HB_THEME_VERSION, true );
				wp_enqueue_script( 'hb_carousel', $theme_path . '/scripts/responsivecarousel.min.js', array('jquery'), HB_THEME_VERSION, true );
				wp_enqueue_script( 'hb_owl_carousel', $theme_path . '/scripts/jquery.owl.carousel.min.js', array('jquery'), HB_THEME_VERSION, true );

				if ( vp_metabox('misc_settings.hb_onepage') ) {
					wp_enqueue_script( 'hb_nav', $theme_path . '/scripts/jquery.nav.js', array('jquery'), HB_THEME_VERSION, true );
				}

				if ( hb_options('hb_ajax_search') ) {
					wp_enqueue_script( 'jquery-ui-autocomplete');	
				}
			}

			if (hb_options('hb_queryloader') == 'ytube-like'){
				wp_enqueue_script( 'hb_pace', $theme_path . '/scripts/jquery.pace.js', array('jquery'), HB_THEME_VERSION, true );
			}

			if ( basename(get_page_template()) == 'page-presentation-fullwidth.php' ) {
				wp_enqueue_script( 'hb_fullpage', $theme_path . '/scripts/jquery.fullpage.js', array('jquery'), HB_THEME_VERSION, true );
			}

			wp_enqueue_script( 'hb_jquery_custom', $theme_path . '/scripts/jquery.custom.js', array('jquery'), HB_THEME_VERSION, true );

			if ( vp_metabox('featured_section.hb_featured_section_effect') == 'hb-bokeh-effect' && !hb_is_maintenance() ){
				wp_enqueue_script( 'hb_fs_effects', $theme_path . '/scripts/canvas-effects.js', array('jquery'), HB_THEME_VERSION, true );
			} else if ( vp_metabox('featured_section.hb_featured_section_effect') == 'hb-clines-effect' && !hb_is_maintenance() ) {
				wp_enqueue_script( 'hb_cl_effects', $theme_path . '/scripts/canvas-lines.js', array('jquery'), HB_THEME_VERSION, true );
			}

			if ( is_singular() && comments_open() ){
				wp_enqueue_script( "comment-reply" );
			}
		}
		
	}
}
?>