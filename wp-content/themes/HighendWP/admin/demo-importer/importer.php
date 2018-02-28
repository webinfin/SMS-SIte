<?php
/**
 * @package WordPress
 * @subpackage Highend
 */

if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

function hb_import_demo( $demo_id, $demo_name, $content, $sliders, $widgets, $media, $highend_options, $essential_grid ) {
    global $wpdb;
    
    if ( current_user_can( 'manage_options' ) ) {
        if ( !defined( 'WP_LOAD_IMPORTERS' ) )
            define( 'WP_LOAD_IMPORTERS', true );
        
        if ( !class_exists( 'WP_Importer' ) ) {
            $wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            include $wp_importer;
        }
        
        if ( !class_exists( 'WP_Import' ) ) {
            $wp_import = get_template_directory() . '/admin/demo-importer/wordpress-importer.php';
            include $wp_import;
        }
        
        if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {
            
            $importer  = new WP_Import();
            set_time_limit(0);
            $theme_xml = "";
            
            /* Delete menus to prevent menu duplication */
            if ( $content == "true" ) {
                wp_delete_nav_menu( 'Main Menu' );
                wp_delete_nav_menu( 'Footer Menu' );
                wp_delete_nav_menu( 'One Page Menu' );
                wp_delete_nav_menu( 'Shortcodes Menu' );
                wp_delete_nav_menu( 'Sidebar Navigation1' );
            }

            // Reset widgets to prevent the duplication            
            if ( $widgets == "true" ){
                update_option( 'sidebars_widgets', null );
            }
            
            // **** Different demos
            $theme_xml        = get_template_directory() . '/admin/demo-importer/' . $demo_id . '/highend.xml.gz'; // main xml file gzipped
            $widgets_json_url = get_template_directory_uri() . '/admin/demo-importer/' . $demo_id . '/widgets.json'; // widgets
            $rev_directory    = get_template_directory() . '/admin/demo-importer/' . $demo_id . '/revsliders/'; // revslider folder
            

            // Fetch Attachments
            if ( $media == "true" ) {
                $importer->fetch_attachments = true;
            }
            
            // Import the demo
            if ( $content == "true" ) {
                ob_start();
                $importer->import( $theme_xml );
                ob_end_clean();
            }
            
            $locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
            $menus     = wp_get_nav_menus(); // registered menus
            
            // assign menus to theme locations
            if ( $menus ) {
                foreach ( $menus as $menu ) {
                    if ( $menu->name == 'Main Menu' ) {
                        $locations['main-menu']   = $menu->term_id;
                        $locations['mobile-menu'] = $menu->term_id;
                    } else if ( $menu->name == 'Footer Menu' ) {
                        $locations['footer-menu'] = $menu->term_id;
                    } else if ( $menu->name == 'One Page Menu' ) {
                        $locations['one-page-menu'] = $menu->term_id;
                    }
                }
                // set menus to locations
                set_theme_mod( 'nav_menu_locations', $locations );
            }
            
            // Set the front page
            if ( $demo_id == 'cafe' ){
                $homepage = get_page_by_title( 'Splash Page' );
            } else {
                $homepage = get_page_by_title( 'Home' );
            }
            if ( $homepage != null && $homepage->ID ) {
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', $homepage->ID );
            }

            // Set shop page
            $shop_page = get_page_by_title('Shop');
            if(!empty($shop_page->ID)) {
                update_option('woocommerce_shop_page_id', $shop_page->ID);
            }


            $sidebars = null;
            if ( $demo_id == 'main-demo' ) {
                if ( class_exists( 'Woocommerce' ) ) {
                    // Add sidebar widget areas
                    $sidebars = array(
                        'ShopWidget1' => 'Shop Widget 1',
                        'ShopWidget2' => 'Shop Widget 2',
                        'ShopWidget3' => 'Shop Widget 3',
                        'ShopWidget4' => 'Shop Widget 4',
                        'DefaultPageSidebar' => 'Default Page Sidebar',
                        'SidebarNavigation1' => 'Sidebar Navigation 1',
                        'PortfolioSingleSidebar' => 'Portfolio Single Sidebar',
                        'ShortcodesSidebar' => 'Shortcodes Sidebar',
                        'ShopSidebar' => 'Shop Sidebar' 
                    );
                } else {
                    // Add sidebar widget areas
                    $sidebars = array(
                        'DefaultPageSidebar' => 'Default Page Sidebar',
                        'SidebarNavigation1' => 'Sidebar Navigation 1',
                        'PortfolioSingleSidebar' => 'Portfolio Single Sidebar',
                        'ShortcodesSidebar' => 'Shortcodes Sidebar' 
                    );
                }
                    
                update_option( 'sbg_sidebars', $sidebars );
            }

            if ( $demo_id == 'online-shop' ) {
                if ( class_exists( 'Woocommerce' ) ) {
                    // Add sidebar widget areas
                    $sidebars = array(
                        'ShopSidebar' => 'Shop Sidebar'
                    );
                }
                    
                update_option( 'sbg_sidebars', $sidebars );
            }


                // Simple Blog Sidebars
            if ( $demo_id == 'simple-blog' ){
                $sidebars = array(
                    'ContactSidebar' => 'Contact Sidebar',
                    'AboutSidebar' => 'About Sidebar'
                );

                update_option( 'sbg_sidebars', $sidebars );
            }
            

            // Import Sidebars
            if ( $sidebars != null && is_array( $sidebars ) ) {
                foreach ( $sidebars as $sidebar ) {
                    $sidebar_class = hb_name_to_class( $sidebar );
                    register_sidebar( array(
                         'name' => $sidebar,
                        'id' => 'hb-custom-sidebar-' . strtolower( $sidebar_class ),
                        'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h4>',
                        'after_title' => '</h4>' 
                    ) );
                }
            }


            // Import Color Customizer
            if ( $demo_id == 'photography' ) {
                set_theme_mod( 'hb_focus_color_setting', '#f2c100' );
                set_theme_mod( 'hb_side_nav_bg_setting', '#252525' );
                set_theme_mod( 'hb_footer_bg_setting', '#222222' );
                set_theme_mod( 'hb_footer_text_setting', '#999999' );
                set_theme_mod( 'hb_footer_link_setting', '#FFFFFF' );
                set_theme_mod( 'hb_content_bg_setting', '#444444' );
                set_theme_mod( 'hb_content_border_setting', '#F4F4F4' );
                set_theme_mod( 'hb_content_c_bg_setting', '#F4F4F4' );
            }

            if ( $demo_id == 'simple-blog' ) {
                set_theme_mod( 'hb_focus_color_setting', '#f09e88' );
                set_theme_mod( 'hb_nav_bar_bg_setting', '#ffffff');
                set_theme_mod( 'hb_nav_bar_text_setting', '#444444');
                set_theme_mod( 'hb_nav_bar_border_setting', '#e1e1e1');
                set_theme_mod( 'hb_copyright_bg_setting', '#ffffff');
                set_theme_mod( 'hb_copyright_text_setting', '#999999');
                set_theme_mod( 'hb_copyright_link_setting', '#777777');
                set_theme_mod( 'hb_content_bg_setting', '#f2f3f5');
                set_theme_mod( 'hb_side_section_bg_setting', '#454545');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_text_color_setting', '#666666');
                set_theme_mod( 'hb_content_link_color_setting', '#f09e88');
                set_theme_mod( 'hb_content_border_setting', '#f0f1f2');
            }

            if ( $demo_id == 'presentation' ) {
                set_theme_mod( 'hb_focus_color_setting', '#ed6162' );
                set_theme_mod( 'hb_nav_bar_bg_setting', '#ffffff');
                //set_theme_mod( 'hb_nav_bar_text_setting', '#444444');
                set_theme_mod( 'hb_nav_bar_border_setting', '#e1e1e1');
                set_theme_mod( 'hb_nav_bar_stuck_bg_setting', '#ffffff');
                set_theme_mod( 'hb_nav_bar_stuck_text_setting', '#444444');
                set_theme_mod( 'hb_nav_bar_stuck_border_setting', '#f0f0f0');
                set_theme_mod( 'hb_pfooter_bg_setting', '#ececec');
                set_theme_mod( 'hb_pfooter_text_setting', '#323436');
                set_theme_mod( 'hb_footer_bg_setting', '#3d4045');
                set_theme_mod( 'hb_footer_text_setting', '#999999');
                set_theme_mod( 'hb_footer_link_setting', '#ffffff');
                set_theme_mod( 'hb_copyright_bg_setting', '#43474d');
                set_theme_mod( 'hb_copyright_text_setting', '#999999');
                set_theme_mod( 'hb_copyright_link_setting', '#ffffff');
                set_theme_mod( 'hb_content_bg_setting', '#222222');
                set_theme_mod( 'hb_side_section_bg_setting', '#ed6162');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_text_color_setting', '#666666');
                set_theme_mod( 'hb_content_link_color_setting', '#252525');
                set_theme_mod( 'hb_content_border_setting', '#f0f0f0');
                set_theme_mod( 'hb_spec_head_color_sticky_setting', '#ffffff');
            }

            if ( $demo_id == 'minimalistic' ){
                set_theme_mod( 'hb_focus_color_setting', '#222222' );
                set_theme_mod( 'hb_nav_bar_bg_setting', '#ffffff');
                set_theme_mod( 'hb_nav_bar_text_setting', '#999999');
                set_theme_mod( 'hb_nav_bar_border_setting', '#ffffff');
                set_theme_mod( 'hb_footer_bg_setting', '#ffffff');
                set_theme_mod( 'hb_footer_text_setting', '#999999');
                set_theme_mod( 'hb_footer_link_setting', '#999999');
                set_theme_mod( 'hb_copyright_bg_setting', '#e8e8e8');
                set_theme_mod( 'hb_copyright_text_setting', '#999999');
                set_theme_mod( 'hb_copyright_link_setting', '#999999');
                set_theme_mod( 'hb_content_bg_setting', '#e8e8e8');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_text_color_setting', '#666666');
                set_theme_mod( 'hb_content_link_color_setting', '#222222');
                set_theme_mod( 'hb_content_border_setting', '#ffffff');
            }

            if ( $demo_id == 'cafe' ){
                set_theme_mod( 'hb_focus_color_setting', '#c39a6e' );
                set_theme_mod( 'hb_side_nav_bg_setting', '#303030' );
                set_theme_mod( 'hb_content_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_text_color_setting', '#444444');
                set_theme_mod( 'hb_content_link_color_setting', '#c39a6e');             
            }

            if ( $demo_id == 'jasper' ){
                set_theme_mod( 'hb_focus_color_setting', '#3299bb' );
                set_theme_mod( 'hb_side_nav_bg_setting', '#f3f3f3' );
                set_theme_mod( 'hb_content_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
            }

            if ( $demo_id == 'church' ){
                set_theme_mod( 'hb_focus_color_setting', '#7e927d' );
                set_theme_mod( 'hb_top_bar_bg_setting', '#ffffff' );
                set_theme_mod( 'hb_top_bar_border_setting', '#f6f6f6' );
                set_theme_mod( 'hb_top_bar_text_color_setting', '#777777' );
                set_theme_mod( 'hb_top_bar_link_color_setting', '#999999' );
                set_theme_mod( 'hb_footer_bg_setting', '#292929');
                set_theme_mod( 'hb_footer_text_setting', '#999999');
                set_theme_mod( 'hb_footer_link_setting', '#ffffff');
                set_theme_mod( 'hb_copyright_bg_setting', '#292929');
                set_theme_mod( 'hb_copyright_text_setting', '#999999');
                set_theme_mod( 'hb_copyright_link_setting', '#ffffff');
                set_theme_mod( 'hb_content_bg_setting', '#444444');
                set_theme_mod( 'hb_content_c_bg_setting', '#f9f9f9');
                set_theme_mod( 'hb_content_text_color_setting', '#454545');
                set_theme_mod( 'hb_content_link_color_setting', '#7e927d');
                set_theme_mod( 'hb_content_h1_setting', '#111111');
                set_theme_mod( 'hb_content_h2_setting', '#111111');
                set_theme_mod( 'hb_content_h3_setting', '#111111');
                set_theme_mod( 'hb_content_h4_setting', '#111111');
                set_theme_mod( 'hb_content_h5_setting', '#111111');
                set_theme_mod( 'hb_content_h6_setting', '#111111');
            }

            if ( $demo_id == 'landing' ){
                set_theme_mod( 'hb_focus_color_setting', '#6c90d0' );
                set_theme_mod( 'hb_copyright_bg_setting', '#353c42');
                set_theme_mod( 'hb_copyright_text_setting', '#999999');
                set_theme_mod( 'hb_copyright_link_setting', '#999999');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_text_color_setting', '#666666');
                set_theme_mod( 'hb_content_link_color_setting', '#222');
            }
            
            if ( $demo_id == 'life-coach' ){
                set_theme_mod( 'hb_focus_color_setting', '#7c940c' );
                set_theme_mod( 'hb_top_bar_bg_setting', '#708316' );
                set_theme_mod( 'hb_top_bar_border_setting', '#7c940c' );
                set_theme_mod( 'hb_top_bar_text_color_setting', '#ffffff' );
                set_theme_mod( 'hb_top_bar_link_color_setting', '#ffffff' );
                set_theme_mod( 'hb_copyright_link_setting', '#999999');
                set_theme_mod( 'hb_content_bg_setting', '#272b2f');
                set_theme_mod( 'hb_content_text_color_setting', '#454545');
            }

            if ( $demo_id == 'bloggera' ){
                set_theme_mod( 'hb_focus_color_setting', '#697a64' );
                set_theme_mod( 'hb_top_bar_bg_setting', '#292929' );
                set_theme_mod( 'hb_top_bar_border_setting', '#444444' );
                set_theme_mod( 'hb_top_bar_text_color_setting', '#eeeeee' );
                set_theme_mod( 'hb_top_bar_link_color_setting', '#bbbbbb' );
                set_theme_mod( 'hb_nav_bar_bg_setting', '#ffffff');
                set_theme_mod( 'hb_nav_bar_text_setting', '#000000');
                set_theme_mod( 'hb_nav_bar_border_setting', '#ffffff');
                set_theme_mod( 'hb_nav_bar_stuck_bg_setting', '#ffffff');
                set_theme_mod( 'hb_nav_bar_stuck_text_setting', '#000000');
                set_theme_mod( 'hb_footer_bg_setting', '#ffffff');
                set_theme_mod( 'hb_footer_text_setting', '#242424');
                set_theme_mod( 'hb_footer_link_setting', '#242424');
                set_theme_mod( 'hb_copyright_bg_setting', '#292929');
                set_theme_mod( 'hb_copyright_text_setting', '#eeeeee');
                set_theme_mod( 'hb_copyright_link_setting', '#eeeeee');
                set_theme_mod( 'hb_content_bg_setting', '#292929');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
                set_theme_mod( 'hb_content_text_color_setting', '#565656');
                set_theme_mod( 'hb_content_link_color_setting', '#697a64');
                set_theme_mod( 'hb_content_h1_setting', '#000000');
                set_theme_mod( 'hb_content_h2_setting', '#000000');
                set_theme_mod( 'hb_content_h3_setting', '#000000');
                set_theme_mod( 'hb_content_h4_setting', '#000000');
                set_theme_mod( 'hb_content_h5_setting', '#000000');
                set_theme_mod( 'hb_content_h6_setting', '#000000');
            }

            if ( $demo_id == 'online-shop' ){
                set_theme_mod( 'hb_focus_color_setting', '#000000' );
                set_theme_mod( 'hb_nav_bar_border_setting', '#ffffff');
                set_theme_mod( 'hb_footer_bg_setting', '#000000');
                set_theme_mod( 'hb_footer_text_setting', '#999999');
                set_theme_mod( 'hb_footer_link_setting', '#000000');
                set_theme_mod( 'hb_copyright_bg_setting', '#000000');
                set_theme_mod( 'hb_copyright_text_setting', '#ffffff');
                set_theme_mod( 'hb_copyright_link_setting', '#b9b9b9');
                set_theme_mod( 'hb_content_c_bg_setting', '#ffffff');
            }
            
            // Import Revolution Sliders
            if ($sliders == "true") {

                if (class_exists('RevSliderSlider')){
                    if ($demo_id == 'main-demo') {
                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/jobs-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/jobs-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            if ($src != false && $dest != false) {
                                stream_copy_to_stream($src, $dest);
                                fclose($src);
                                fclose($dest);
                            }
                        }

                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/boxed-corporate-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/boxed-corporate-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/corporate-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/corporate-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/home-classic-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/home-classic-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/home-default-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/home-default-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/home-special-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/home-special-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/one-page-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/one-page-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/main-demo/revsliders/shop-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/revsliders/shop-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'photography') {
                        $url = get_template_directory() . '/admin/demo-importer/photography/revsliders/home-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/photography/home-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/photography/revsliders/blog-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/photography/blog-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'presentation') {
                        $url = get_template_directory() . '/admin/demo-importer/presentation/revsliders/about-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/presentation/about-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'minimalistic') {
                        $url = get_template_directory() . '/admin/demo-importer/minimalistic/revsliders/about_slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/minimalistic/about_slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }

                        $url = get_template_directory() . '/admin/demo-importer/minimalistic/revsliders/home_slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/minimalistic/home_slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'cafe') {
                        $url = get_template_directory() . '/admin/demo-importer/cafe/revsliders/splash_slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/cafe/splash_slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'church') {
                        $url = get_template_directory() . '/admin/demo-importer/church/revsliders/home_hero.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/church/home_hero.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'life-coach') {
                        $url = get_template_directory() . '/admin/demo-importer/life-coach/revsliders/homeslider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/life-coach/homeslider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'bloggera') {
                        $url = get_template_directory() . '/admin/demo-importer/bloggera/revsliders/home_slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/bloggera/home_slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }
                    else
                    if ($demo_id == 'online-shop') {
                        $url = get_template_directory() . '/admin/demo-importer/online-shop/revsliders/shop-home-slider.zip';
                        if (!file_exists($url)) {
                            $d_url = 'http://hb-themes.com/repository/import/highend/online-shop/shop-home-slider.zip';
                            $src = fopen($d_url, 'r');
                            $dest = fopen($url, 'w');
                            stream_copy_to_stream($src, $dest);
                            fclose($src);
                            fclose($dest);
                        }
                    }

                    $rev_directory = get_template_directory() . '/admin/demo-importer/' . $demo_id . '/revsliders/'; // revslider data dir
                    foreach(glob($rev_directory . '*.zip') as $filename) // get all files from revsliders data dir
                    {
                        $filename = basename($filename);
                        $rev_files[] = get_template_directory() . '/admin/demo-importer/' . $demo_id . '/revsliders/' . $filename;
                    }

                    foreach($rev_files as $rev_file) // finally import rev slider data files
                    {
                        $filepath = $rev_file;
                        $slider = new RevSlider();
                        $slider->importSliderFromPost(true, true, $filepath);
                    }
                }
            } // end rev slider import

            // Import Essential Grid
            if ( $essential_grid == "true" ) { 
                if ( defined( 'EG_PLUGIN_PATH' ) && class_exists( 'Essential_Grid_Import' ) ) {
                    try {
                        $json_url = get_template_directory() . '/admin/demo-importer/' . $demo_id . '/ess_grid.json';
                        
                        if ( file_exists( $json_url ) ) {
                            $tp_grid_meta_fonts = file_get_contents( $json_url, true );
                            
                            //insert meta, grids & punchfonts
                            $im = new Essential_Grid_Import();
                            $im->set_overwrite_data( $tp_grid_meta_fonts ); //set overwrite data global to class
                            
                            if ( isset( $tp_grid_meta_fonts ) ) {
                                $tp_grid_meta_fonts = json_decode( $tp_grid_meta_fonts, true );
                                
                                /* Skins */
                                $skins     = @$tp_grid_meta_fonts['skins'];
                                $skins_ids = array();
                                
                                foreach ( $skins as $skin ) {
                                    $skins_ids[] = $skin['id'];
                                }
                                if ( !empty( $skins ) && is_array( $skins ) ) {
                                    $skins_imported = $im->import_skins( $skins, $skins_ids );
                                }
                                
                                /* Navigation Skins */
                                $navigation_skins     = @$tp_grid_meta_fonts['navigation-skins'];
                                $navigation_skins_ids = array();
                                
                                foreach ( $navigation_skins as $nav_skin ) {
                                    $navigation_skins_ids[] = $nav_skin['id'];
                                }
                                
                                if ( !empty( $navigation_skins ) && is_array( $navigation_skins ) ) {
                                    $navigation_skins_imported = $im->import_navigation_skins( $navigation_skins, $navigation_skins_ids );
                                }
                                
                                /* Grids */
                                $grids     = @$tp_grid_meta_fonts['grids'];
                                $grids_ids = array();
                                
                                foreach ( $grids as $grid ) {
                                    $grids_ids[] = $grid['id'];
                                }
                                
                                if ( !empty( $grids ) && is_array( $grids ) ) {
                                    $grids_imported = $im->import_grids( $grids, $grids_ids );
                                }
                                
                                /* Custom Metas */
                                $custom_metas        = @$tp_grid_meta_fonts['custom-meta'];
                                $custom_meta_handles = array();
                                
                                foreach ( $custom_metas as $custom_meta ) {
                                    $custom_meta_handles[] = $custom_meta['handle'];
                                }
                                
                                if ( !empty( $custom_metas ) && is_array( $custom_metas ) ) {
                                    $custom_metas_imported = $im->import_custom_meta( $custom_metas, $custom_meta_handles );
                                }
                                
                                /* Custom Fonts */
                                $custom_fonts        = @$tp_grid_meta_fonts['punch-fonts'];
                                $custom_font_handles = array();
                                
                                foreach ( $custom_fonts as $custom_font ) {
                                    $custom_font_handles[] = $custom_font['handle'];
                                }
                                
                                if ( !empty( $custom_fonts ) && is_array( $custom_fonts ) ) {
                                    $custom_fonts_imported = $im->import_punch_fonts( $custom_fonts, $custom_font_handles );
                                }
                                
                                // Global CSS
                                $global_css = @$tp_grid_meta_fonts['global-css'];
                                if ( !empty( $global_css ) && is_array( $global_css ) ) {
                                    $global_css_imported = $im->import_global_styles( $global_css );
                                }
                                
                            }
                        }
                        
                    }
                    catch ( Exception $d ) {
                    }
                }

            } // END Essential Grid Import
            
            
            if ( $widgets == "true" ) {
                // Add data to widgets
                $widgets_json   = $widgets_json_url;
                $widgets_json   = wp_remote_get( $widgets_json );
                $widget_data    = $widgets_json['body'];
                $import_widgets = hb_import_widget_data( $widget_data );
            }

            // ********************************************************
            
            // finally return status
            $home_url = home_url();
            if ( $demo_id == 'cafe' ){
                return "<strong>" . $demo_name . "</strong>" . __( " demo template has been imported successfully! <a href='" . $home_url . "'>View your website.</a><br/><strong>Cafe Demo Notice:</strong> You'll need to edit 'Splash Slider' in Revolution Slider section to change the 'Enter the site' button link to your own home page URL.", "hbthemes" ) . "<a href='http://hb-themes.com/images/cafe-notice-screenshot.png' target='_blank'>View screenshot.</a>";
            } else {
                return "<strong>" . $demo_name . "</strong>" . __( " demo template has been imported successfully! <a href='" . $home_url . "'>View your website.</a>", "hbthemes" );
            }

            
        }
    }
    
    return "Something went wrong. Please try again.";
}

/* HELPER FUNCTIONS
 *******************************************************************/

// Parsing Widgets Function
function hb_import_widget_data( $widget_data ) {
    
    $json_data = $widget_data;
    $json_data = json_decode( $json_data, true );
    
    $sidebar_data = $json_data[0];
    $widget_data  = $json_data[1];
    
    
    foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
        $widgets[$widget_data_title] = '';
        foreach ( $widget_data_value as $widget_data_key => $widget_data_array ) {
            if ( is_int( $widget_data_key ) ) {
                $widgets[$widget_data_title][$widget_data_key] = 'on';
            }
        }
    }
    unset( $widgets[""] );
    
    foreach ( $sidebar_data as $title => $sidebar ) {
        $count = count( $sidebar );
        for ( $i = 0; $i < $count; $i++ ) {
            $widget               = array();
            $widget['type']       = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
            $widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
            if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
                unset( $sidebar_data[$title][$i] );
            }
        }
        $sidebar_data[$title] = array_values( $sidebar_data[$title] );
    }
    
    foreach ( $widgets as $widget_title => $widget_value ) {
        foreach ( $widget_value as $widget_key => $widget_value ) {
            $widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
        }
    }
    
    $sidebar_data = array(
         array_filter( $sidebar_data ),
        $widgets 
    );
    
    hb_parse_import_data( $sidebar_data );
}

function hb_parse_import_data( $import_array ) {
    global $wp_registered_sidebars;
    $sidebars_data    = $import_array[0];
    $widget_data      = $import_array[1];
    $current_sidebars = get_option( 'sidebars_widgets' );
    $new_widgets      = array();
    
    foreach ( $sidebars_data as $import_sidebar => $import_widgets ):
        foreach ( $import_widgets as $import_widget ):
        //if the sidebar exists
            if ( isset( $wp_registered_sidebars[$import_sidebar] ) ):
                $title               = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
                $index               = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
                $current_widget_data = get_option( 'widget_' . $title );
                $new_widget_name     = hb_get_new_widget_name( $title, $index );
                $new_index           = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );
                if ( !empty( $new_widgets[$title] ) && is_array( $new_widgets[$title] ) ) {
                    while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
                        $new_index++;
                    }
                }
                $current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
                if ( array_key_exists( $title, $new_widgets ) ) {
                    $new_widgets[$title][$new_index] = $widget_data[$title][$index];
                    $multiwidget                     = $new_widgets[$title]['_multiwidget'];
                    unset( $new_widgets[$title]['_multiwidget'] );
                    $new_widgets[$title]['_multiwidget'] = $multiwidget;
                } else {
                    $current_widget_data[$new_index] = $widget_data[$title][$index];
                    $current_multiwidget             = $current_widget_data['_multiwidget'];
                    $new_multiwidget                 = isset( $widget_data[$title]['_multiwidget'] ) ? $widget_data[$title]['_multiwidget'] : false;
                    $multiwidget                     = ( $current_multiwidget != $new_multiwidget ) ? $current_multiwidget : 1;
                    unset( $current_widget_data['_multiwidget'] );
                    $current_widget_data['_multiwidget'] = $multiwidget;
                    $new_widgets[$title]                 = $current_widget_data;
                }
            endif;
        endforeach;
    endforeach;
    
    if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
        update_option( 'sidebars_widgets', $current_sidebars );
        
        foreach ( $new_widgets as $title => $content )
            update_option( 'widget_' . $title, $content );
        
        return true;
    }
    
    return false;
}

function hb_get_new_widget_name( $widget_name, $widget_index ) {
    $current_sidebars = get_option( 'sidebars_widgets' );
    $all_widget_array = array();
    
    if ( is_array( $current_sidebars ) ) {
        foreach ( $current_sidebars as $sidebar => $widgets ) {
            if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
                foreach ( $widgets as $widget ) {
                    $all_widget_array[] = $widget;
                }
            }
        }
    }
    while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
        $widget_index++;
    }
    $new_widget_name = $widget_name . '-' . $widget_index;
    return $new_widget_name;
}

if ( function_exists( 'layerslider_import_sample_slider' ) ) {
    function hb_import_sample_slider( $layerslider_data ) {
        // Base64 encoded, serialized slider export code
        $sample_slider = $layerslider_data;
        
        // Iterate over the sliders
        foreach ( $sample_slider as $sliderkey => $slider ) {
            
            // Iterate over the layers
            foreach ( $sample_slider[$sliderkey]['layers'] as $layerkey => $layer ) {
                
                // Change background images if any
                if ( !empty( $sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'] ) ) {
                    $sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'] = $GLOBALS['lsPluginPath'] . 'sampleslider/' . basename( $layer['properties']['background'] );
                }
                
                // Change thumbnail images if any
                if ( !empty( $sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'] ) ) {
                    $sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'] = $GLOBALS['lsPluginPath'] . 'sampleslider/' . basename( $layer['properties']['thumbnail'] );
                }
                
                // Iterate over the sublayers
                if ( isset( $layer['sublayers'] ) && !empty( $layer['sublayers'] ) ) {
                    foreach ( $layer['sublayers'] as $sublayerkey => $sublayer ) {
                        
                        // Only IMG sublayers
                        if ( $sublayer['type'] == 'img' ) {
                            $sample_slider[$sliderkey]['layers'][$layerkey]['sublayers'][$sublayerkey]['image'] = $GLOBALS['lsPluginPath'] . 'sampleslider/' . basename( $sublayer['image'] );
                        }
                    }
                }
            }
        }
        
        // Get WPDB Object
        global $wpdb;
        
        // Table name
        $table_name = $wpdb->prefix . "layerslider";
        
        // Append duplicate
        foreach ( $sample_slider as $key => $val ) {
            // Insert the duplicate
            $wpdb->query( $wpdb->prepare( "INSERT INTO $table_name (name, data, date_c, date_m) VALUES (%s, %s, %d, %d)", $val['properties']['title'], json_encode( $val ), time(), time() ) );
        }
    }
}

// Rename sidebar
function hb_name_to_class( $name ) {
    $class = str_replace( array(
        ' ',
        ',',
        '.',
        '"',
        "'",
        '/',
        "\\",
        '+',
        '=',
        ')',
        '(',
        '*',
        '&',
        '^',
        '%',
        '$',
        '#',
        '@',
        '!',
        '~',
        '`',
        '<',
        '>',
        '?',
        '[',
        ']',
        '{',
        '}',
        '|',
        ':' 
    ), '', $name );
    return $class;
}
?>