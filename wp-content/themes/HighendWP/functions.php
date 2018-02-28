<?php
    /* DEFINE
    ================================================== */
    define ( 'HBTHEMES_ROOT' , get_template_directory() );
    define ( 'HBTHEMES_INCLUDES' , get_template_directory() . '/includes' );
    define ( 'HBTHEMES_ADMIN' , get_template_directory() . '/admin' );
    define ( 'HBTHEMES_FUNCTIONS' , get_template_directory() . '/functions' );
    define ( 'HBTHEMES_URI' , get_template_directory_uri() );
    define ( 'HBTHEMES_ADMIN_URI' , get_template_directory_uri() . '/admin' );
    define ( 'HB_THEME_VERSION', wp_get_theme('HighendWP')->get( 'Version' ) );

    define('SHORTNAME', 'hb');
    define('THEMENAME', 'Highend');
    define('ADMIN_URL', get_admin_url());

    $theme_focus_color = get_theme_mod( 'hb_focus_color_setting');
    $shortname = SHORTNAME;
    $themename = THEMENAME;
    $themepath = get_template_directory_uri();


    /* THEME SETUP
    ================================================== */
    add_action('after_setup_theme', 'hb_theme_setup');
    if ( !function_exists('hb_theme_setup') ) {
        function hb_theme_setup() {
            global $shortname;
            global $themename;
            global $themepath;
            global $themeoptions;
            require_once(HBTHEMES_FUNCTIONS . '/theme-styles.php');
            require_once(HBTHEMES_FUNCTIONS . '/theme-scripts.php');

            if (defined('WP_ADMIN') && WP_ADMIN) {
                require_once('includes/tinymce/shortcode-popup.php');
            }

        }
    }


    /* CUSTOM ADMIN STYLES
    ================================================== */
    add_action('admin_enqueue_scripts', 'hb_admin_theme_style');
    if ( !function_exists('hb_admin_theme_style') ) {
        function hb_admin_theme_style() {
            wp_enqueue_style('my-admin-theme', get_template_directory_uri() . '/admin/assets/css/custom-admin.css', false, HB_THEME_VERSION, 'all' );
        }
    }


    /* RETRIEVE FROM THEME OPTIONS
    ================================================== */
    function hb_options($name) {
        if ( function_exists('vp_option') ) {
            return vp_option("hb_highend_option." . $name);
        }
        return;
    }


    /* MODULE ENABLED
    ================================================== */
    if ( !function_exists('hb_module_enabled') ) {
        function hb_module_enabled( $module_name ) {
            if ( !hb_options('hb_control_modules') || ( hb_options('hb_control_modules') && hb_options( $module_name ) ) ) {
                return true;
            }
            return false;
        }
    } 

    /* ADMIN HB DASHBOARD WIDGET
    ================================================== */
    if ( !function_exists('hb_dashboard_widget') ) {
        function hb_dashboard_widget() {
            $my_theme          = wp_get_theme();
            $menus_url         = ADMIN_URL . 'nav-menus.php';
            $front_page_url    = ADMIN_URL . 'options-reading.php';
            $theme_options_url = ADMIN_URL . 'themes.php?page=highend_options#_hb_general_settings'; 
            $demos_url         = ADMIN_URL . 'admin.php?page=hb-import-demos';

            // Fetch RSS news
            $hb_feed = array();
            /*$hb_rss = new DOMDocument();
            $hb_rss->load('http://hb-themes.com/home/feed/');
            $limit = 1;

            foreach ($hb_rss->getElementsByTagName('item') as $node) {
                $item = array ( 
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                    'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                );
                array_push($hb_feed, $item);
            }*/

            echo '
                <div class="main clearfix" id="highend_widget_box">
                <p class="nbm">' . __('You are using', 'hbthemes') .' <strong>'. $my_theme->get( 'Name' ) . '</strong> ' . __('WordPress theme. Version','hbthemes') . ' ' . ' <strong>' . $my_theme->get('Version') . '.</strong>
                </p>

                <hr/>

                <p>' . __('First Steps', 'hbthemes') . '</p>
                <ul id="highend_links">
                    <li><a href="' . $demos_url . '">' . __('Import demo template', 'hbthemes') . '</a></li>
                    <li><a href="' . $theme_options_url . '">' . __('Highend Options', 'hbthemes' ) . '</a></li>
                    <li><a href="' . $front_page_url . '">'. __('Choose your front page', 'hbthemes') .'</a></li>
                    <li><a href="' . $menus_url . '">'. __('Manage menus', 'hbthemes') . '</a></li>
                </ul>

                <hr/>

                <p>Need help?</p>
                <ul id="highend_widget">
                    <li id="highend_docs"><a href="http://documentation.hb-themes.com/highend/index.html" target="_blank">' . __('Read the documentation', 'hbthemes') . '</a></li>
                    <li id="highend_videos"><a href="http://documentation.hb-themes.com/highend/index.html#video-tutorials" target="_blank">' . __('Watch video tutorials', 'hbthemes') . '</a></li>
                    <li id="highend_forum"><a href="http://forum.hb-themes.com" target="_blank">' . __('Open a support topic', 'hbthemes') . '</a></li>
                    <li id="highend_facebook"><a href="http://facebook.com/hbthemes" target="_blank">' . __('Find us on Facebook', 'hbthemes') . '</a></li>
                    <li id="highend_twitter"><a href="http://twitter.com/hbthemes" target="_blank">' . __('Follow us on Twitter', 'hbthemes') . '</a></li>
                    <li id="highend_customization"><a href="http://hb-themes.com/hire/" target="_blank">' . __('Hire HB-Themes experts to build your website', 'hbthemes') . '</a></li>
                </ul>';

                if ( !empty($hb_feed) ){
                    echo '
                    <div class="hb-latest-news-section rss-widget">
                        <hr/>
                        <p>' . __('HB-Themes News', 'hbthemes') . '</p>';

                        for($x=0;$x<$limit;$x++) {
                            $title = str_replace(' & ', ' &amp; ', $hb_feed[$x]['title']);
                            $link = $hb_feed[$x]['link'];
                            $description = $hb_feed[$x]['desc'];
                            //$date = date('F d, Y', strtotime($hb_feed[$x]['date']));
                            echo '<a class="rsswidget" href="'.$link.'" title="'.$title.'" target="_blank">'.$title.'</a><br/>';
                            //echo '<small class="rss-date">'.$date.'</small>';
                            echo '<p class="rssSummary">'.$description.'</p>';
                        }

                    echo '</div>';
                }

                echo '<div class="clear"></div></div>';
        }

        function hb_add_dashboard_widgets() {
            if ( !hb_module_enabled('hb_module_dashboard_widget') ) return;
            add_meta_box( 'elevate_dashboard_widget', 'Welcome to Highend', 'hb_dashboard_widget', 'dashboard', 'side', 'high' );
        }

        if ( current_user_can( 'manage_options' ) ){
            add_action('wp_dashboard_setup', 'hb_add_dashboard_widgets');
        }
    }

    remove_filter('nav_menu_description', 'strip_tags');
    
    /* INCLUDES
    ================================================== */
    include( 'admin/theme-custom-post-types.php');
    include( 'admin/theme-custom-taxonomies.php');
    if ( is_admin() ) { include_once( 'admin/custom-walker/mega-menu.php'); }
    include( 'admin/custom-walker/sweet-custom-menu.php');
    include( 'admin/theme-customizer.php');
    include ( 'admin/hb-panel/hb-panel.php');
    include ( 'includes/shortcodes.php');
    include ( 'includes/scheme.php');
    include ( 'includes/class-tgm-plugin-activation.php');
    include ( 'options-framework/bootstrap.php');
    include ( 'admin/theme-options-dependency.php');
    include ( 'admin/plugins/multiple-sidebars.php');
    include ( 'admin/metaboxes/metabox-dependency.php');
    if ( !defined('RWMB_VER') ) {
        include ( 'admin/metaboxes/meta-box-master/meta-box.php');
    }
    include ( 'admin/metaboxes/gallery-multiupload.php');
    include ( 'admin/author-meta.php');
    include ( 'functions/breadcrumbs.php');
    include ( 'functions/testimonial-box.php');
    include ( 'functions/testimonial-quote.php');
    include ( 'functions/team-member-box.php');
    include ( 'functions/image_dimensions.php');
    include ( 'functions/theme-likes.php');
    include ( 'functions/theme-thumbnails-resize.php');
    include ( 'functions/pagination-standard.php');
    include ( 'functions/pagination-ajax.php');


    /* CHECK IF WOOCOMMERCE IS INSTALLED AND ACTIVE
    ================================================== */
    if ( !function_exists('hb_woocommerce_active') ) {
        function hb_woocommerce_active() {
            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                return true;
            }
            return false;
        }
    }
    
    
    /* WOOCOMMERCE STUFF
    ================================================== */
    if ( !is_multisite() ) {
        if ( hb_woocommerce_active() ) {
            require_once(HBTHEMES_INCLUDES . '/hb-woocommerce.php');
        }
    } else {
        require_once(HBTHEMES_INCLUDES . '/hb-woocommerce.php');
    }


    /* AUTOMATIC THEME UPDATES
    ================================================== */
    require_once('includes/theme-update-checker.php');
    if (class_exists("ThemeUpdateChecker")) {
        $MyThemeUpdateChecker = new ThemeUpdateChecker(
            'HighendWP',
            'http://hb-themes.com/update/?action=get_metadata&slug=HighendWP'
        );
    }


    /* TGMPA
    ================================================== */
    add_action('tgmpa_register', 'hb_register_required_plugins');
    if ( !function_exists('hb_register_required_plugins') ) {
        function hb_register_required_plugins() {
            if ( !hb_module_enabled('hb_module_plugin_recommender') ) return;
            $plugins = array(
                array(
                    'name'               => 'LayerSlider WP',
                    'slug'               => 'LayerSlider',
                    'source'             => 'http://hb-themes.com/repository/plugins/layerslider.zip',
                    'required'           => false,
                    'version'            => '6.5.1',
                    'force_activation'   => false,
                    'force_deactivation' => false
                ),
                array(
                    'name'               => 'Revolution Slider',
                    'slug'               => 'revslider',
                    'source'             => 'http://hb-themes.com/repository/plugins/revslider.zip',
                    'required'           => false,
                    'version'            => '5.4.5.1',
                    'force_activation'   => false,
                    'force_deactivation' => false
                ),
                array(
                    'name'               => 'Essential Grid',
                    'slug'               => 'essential-grid',
                    'source'             => 'http://hb-themes.com/repository/plugins/essential-grid.zip',
                    'required'           => false,
                    'version'            => '2.1.5.1',
                    'force_activation'   => false,
                    'force_deactivation' => false
                ),
                array(
                    'name'               => 'Contact Form 7',
                    'slug'               => 'contact-form-7',
                    'required'           => false,
                    'force_activation'   => false,
                    'force_deactivation' => false,
                ),
                array(
                    'name'               => 'Visual Composer - Live Drag & Drop Page Builder',
                    'slug'               => 'js_composer',
                    'source'             => 'http://hb-themes.com/repository/plugins/js_composer.zip',
                    'required'           => true,
                    'version'            => '5.2',
                    'force_activation'   => false,
                    'force_deactivation' => false
                ),
                array(
                    'name'               => 'WP Instagram Widget',
                    'slug'               => 'wp-instagram-widget',
                    'required'           => false,
                    'force_activation'   => false,
                    'force_deactivation' => false,
                )
            );
            // Change this to your theme text domain, used for internationalising strings
            $theme_text_domain = 'hbthemes';
            $config            = array(
                'domain' => $theme_text_domain, // Text domain - likely want to be the same as your theme.
                'default_path' => '', // Default absolute path to pre-packaged plugins
                'menu' => 'install-required-plugins', // Menu slug
                'has_notices' => true, // Show admin notices or not
                'is_automatic' => true, // Automatically activate plugins after installation or not
                'message' => '<p>To make your website load faster only activate plugins you need.<br/>Highend theme requires only Visual Composer. Revolution Slider is recommended.</p>', // Message to output right before the plugins table
                'strings' => array(
                    'page_title' => __('Install Required Plugins', $theme_text_domain),
                    'menu_title' => __('Install Plugins', $theme_text_domain),
                    'installing' => __('Installing Plugin: %s', $theme_text_domain), // %1$s = plugin name
                    'oops' => __('Something went wrong with the plugin API.', $theme_text_domain),
                    'notice_can_install_required' => _n_noop('Highend theme requires the following plugin: %1$s.<br/>Please install this plugin.', 'Highend theme requires the following plugins: %1$s.'), // %1$s = plugin name(s)
                    'notice_can_install_recommended' => _n_noop('Highend theme recommends the following plugin: %1$s.', 'Highend theme recommends the following plugins: %1$s.'), // %1$s = plugin name(s)
                    'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.'), // %1$s = plugin name(s)
                    'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
                    'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
                    'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'), // %1$s = plugin name(s)
                    'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'), // %1$s = plugin name(s)
                    'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'), // %1$s = plugin name(s)
                    'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins'),
                    'activate_link' => _n_noop('Activate installed plugin', 'Activate installed plugins'),
                    'return' => __('Return to Required Plugins Installer', $theme_text_domain),
                    'plugin_activated' => __('Plugin activated successfully.', $theme_text_domain),
                    'complete' => __('All plugins installed and activated successfully. %s', $theme_text_domain), // %1$s = dashboard link
                    'nag_type' => 'updated'
                    // Determines admin notice type - can only be 'updated' or 'error'
                )
            );
            tgmpa($plugins, $config);
        }
    }


    /* LAYER AND REVOLUTION SLIDER
    ================================================== */
    add_action('layerslider_ready', 'my_layerslider_overrides');
    function my_layerslider_overrides() {
        $GLOBALS['lsAutoUpdateBox'] = false;
    }
    function get_all_layer_sliders() {
        if (!is_layer_slider_activated())
            return;
        $sliders = array();
        if (function_exists('lsSliders')) {
            $all_sliders = lsSliders(1000000, true, true);
            if (!empty($all_sliders)) {
                foreach ($all_sliders as $slider) {
                    $sliders[$slider['id']] = $slider['name'];
                }
            }
        }
        return $sliders;
    }
    function is_layer_slider_activated() {
        $layerslider = ABSPATH . 'wp-content/plugins/LayerSlider/layerslider.php';
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (!is_plugin_active('LayerSlider/layerslider.php'))
            return false;
        return true;
    }
    function is_layer_slider_installed() {
        $layerslider = ABSPATH . 'wp-content/plugins/LayerSlider/layerslider.php';
        if (!file_exists($layerslider))
            return false;
        return true;
    }
    function get_all_revolution_sliders() {
        $revolutionslider     = array();
        $revolutionslider[''] = 'No Slider';
        if (class_exists('RevSlider')) {
            $slider     = new RevSlider();
            $arrSliders = $slider->getArrSliders();
            foreach ($arrSliders as $revSlider) {
                $revolutionslider[$revSlider->getAlias()] = $revSlider->getTitle();
            }
        }
        return $revolutionslider;
    }
    // Change VC Class Names
    function custom_vc_class($class_string, $tag) {
        if ($tag == 'vc_row' || $tag == 'vc_row_inner' || $tag == 'wpb_row') {
            $class_string = str_replace('vc_row-fluid', 'row', $class_string);
            $class_string = str_replace('wpb_row ', 'element-row ', $class_string);
        }

        if ( defined('WPB_VC_VERSION') && version_compare(WPB_VC_VERSION, "4.3.0") >= 0 ) {
            // good version
        } else {
            if ($tag == 'vc_column' || $tag == 'vc_column_inner') {
                $class_string = preg_replace('/vc_span(\d{1,2})/', 'col-$1', $class_string);
            }
        }
        return $class_string;
    }


    /* DISABLE PLUGIN SETTINGS
    ================================================== */
    add_action('admin_head', 'hb_remove_actions');
    if ( !function_exists('hb_remove_actions') ) {
        function hb_remove_actions() {
            if ( class_exists('RevSliderAdmin') ) {
                remove_action('admin_notices', array( 'RevSliderAdmin', 'add_plugins_page_notices' ));
                remove_action('admin_notices', array( 'RevSliderAdmin', 'addActivateNotification'));
            }

            if ( defined('LS_PLUGIN_VERSION') ) {
                remove_action('admin_notices', 'layerslider_unauthorized_update_notice' );
                remove_action('admin_notices', 'layerslider_premium_support' );
                remove_action('after_plugin_row_' . LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice' );
                add_action('in_plugin_update_message-' . LS_PLUGIN_BASE, 'hb_update_link');
            }

            if ( class_exists('Vc_Manager') && function_exists('vc_plugin_name') ) {
                hb_remove_anonymous_object_filter(
                    'in_plugin_update_message-' . vc_plugin_name(),
                    'Vc_Updating_Manager',
                    'addUpgradeMessageLink'
                );
                add_action('in_plugin_update_message-' . vc_plugin_name(), 'hb_update_link');
            }
        }

        if ( function_exists('set_revslider_as_theme') ){
            set_revslider_as_theme();
        }

        if ( function_exists('set_ess_grid_as_theme') ) {
            set_ess_grid_as_theme();
        }
    }

    add_action( 'vc_before_init', 'hb_vcSetAsTheme' );
    function hb_vcSetAsTheme() {
        vc_set_as_theme();
    }

    if ( !function_exists( 'hb_remove_anonymous_object_filter' ) ) {
        function hb_remove_anonymous_object_filter( $tag, $class, $method ) {
            $filters = $GLOBALS['wp_filter'][ $tag ];

            if ( empty ( $filters ) ) {
                return;
            }

            foreach ( $filters as $priority => $filter ) {
                foreach ( $filter as $identifier => $function ) {
                    if ( is_array( $function)
                        and is_a( $function['function'][0], $class )
                        and $method === $function['function'][1]
                    ) {
                        remove_filter(
                            $tag,
                            array ( $function['function'][0], $method ),
                            $priority
                        );
                    }
                }
            }
        }
    }


    /* DE-REGISTER VISUAL COMPOSER SHORTCODES
    ================================================== */
    if ( function_exists('vc_remove_element') ) {
        vc_remove_element("vc_wp_categories");
        vc_remove_element("vc_wp_custommenu");
        vc_remove_element("vc_wp_links");
        vc_remove_element("vc_wp_meta");
        vc_remove_element("vc_wp_pages");
        vc_remove_element("vc_wp_posts");
        vc_remove_element("vc_wp_recentcomments");
        vc_remove_element("vc_wp_rss");
        vc_remove_element("vc_wp_search");
        vc_remove_element("vc_wp_tagcloud");
        vc_remove_element("vc_wp_text");
        vc_remove_element("vc_wp_calendar");
        vc_remove_element("vc_wp_archives");
        vc_remove_element("vc_gmaps");
        add_filter('vc_shortcodes_css_class', 'custom_vc_class', 10, 2);
    }


    /* REGISTER MENUS
    ================================================== */
    function hb_register_menu() {
        register_nav_menu('main-menu', __('Main Menu', 'hbthemes'));
        register_nav_menu('footer-menu', __('Footer Menu', 'hbthemes'));
        register_nav_menu('mobile-menu', __('Mobile Menu', 'hbthemes'));
        register_nav_menu('one-page-menu', __('One Page Menu', 'hbthemes'));
    }
    add_action('init', 'hb_register_menu');


    /* THEME SUPPORT
    ================================================== */
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('post-formats', array(
        'gallery',
        'image',
        'quote',
        'video',
        'audio',
        'link'
    ));
    add_theme_support('automatic-feed-links');
    add_theme_support('woocommerce');
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_filter('widget_text', 'do_shortcode');
    add_filter('widget_text', 'shortcode_unautop');


    /* LANGUAGE SETUP
    ================================================== */
    add_action('after_setup_theme', 'hb_language_setup');
    if ( !function_exists('hb_language_setup') ) {
        function hb_language_setup() {
            load_theme_textdomain('hbthemes', HBTHEMES_ROOT . '/languages');
        }
    }


    /* SET CONTENT WIDTH
    ================================================== */
    if (!isset($content_width)) {
        if (hb_options('hb_content_width') == '940px') {
            $content_width = 940;
        } else {
            $content_width = 1140;
        }
    }


    /* THEME OPTIONS
    ================================================== */
    add_action('after_setup_theme', 'hb_init_options');
    if ( !function_exists('hb_init_options') ) {
        function hb_init_options() {
            global $themeoptions;
            $tmpl_opt      = HBTHEMES_ADMIN . '/theme-options.php';
            $themeoptions = new VP_Option(array(
                'is_dev_mode' => false,
                'option_key' => 'hb_highend_option',
                'page_slug' => 'highend_options',
                'template' => $tmpl_opt,
                'menu_page' => 'themes.php',
                'use_auto_group_naming' => true,
                'use_exim_menu' => false,
                'minimum_role' => 'edit_theme_options',
                'layout' => 'fixed',
                'page_title' => __('Highend Options', 'hbthemes'),
                'menu_label' => __('Highend Options', 'hbthemes')
            ));
        }
    }


    /* METABOXES
    ================================================== */
    function hb_init_metaboxes() {
        
        if ( hb_module_enabled('hb_module_portfolio') ) {
            $mb_path_standard_portfolio_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-standard-page-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'portfolio_standard_page_settings',
                'types' => array(
                    'page'
                ),
                'title' => __('Portfolio Template Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_standard_portfolio_page_template_settings
            ));

            $mb_path_portfolio_settings                        = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'portfolio_settings',
                'types' => array(
                    'portfolio'
                ),
                'title' => __('Portfolio Page Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_portfolio_settings
            ));

            $mb_path_portfolio_layout_settings                 = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-layout-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'portfolio_layout_settings',
                'types' => array(
                    'portfolio'
                ),
                'title' => __('Portfolio Layout Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'context' => 'side',
                'template' => $mb_path_portfolio_layout_settings
            ));
        
        }

        if ( hb_module_enabled('hb_module_pricing_tables') ) {
            $mb_path_pricing_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-pricing-table-settings.php';  
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'pricing_settings',
                'types' => array(
                    'hb_pricing_table'
                ),
                'title' => __('Pricing Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_pricing_settings
            ));
        }

        if ( hb_module_enabled('hb_module_gallery') ) {
            $mb_path_fw_gallery_page_template_settings         = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-fw-page-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'gallery_fw_page_settings',
                'types' => array(
                    'page'
                ),
                'title' => __('Fullwidth Gallery Template Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_fw_gallery_page_template_settings
            ));

            $mb_path_standard_gallery_page_template_settings   = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-standard-page-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'gallery_standard_page_settings',
                'types' => array(
                    'page'
                ),
                'title' => __('Standard Gallery Template Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_standard_gallery_page_template_settings
            ));  

            $mb_path_gallery_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-settings.php';
            $mb_gallery_settings = new VP_Metabox(array(
                'id' => 'gallery_settings',
                'types' => array(
                    'gallery',
                ),
                'title' => __('Gallery Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_gallery_settings
            ));
           
        }

        if ( hb_module_enabled('hb_module_testimonials') ) {
            $mb_path_testimonials_settings                     = HBTHEMES_ADMIN . '/metaboxes/meta-testimonials.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'testimonial_type_settings',
                'types' => array(
                    'hb_testimonials'
                ),
                'title' => __('Testimonial Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_testimonials_settings
            ));
        }

        if ( hb_module_enabled('hb_module_team_members') ) {
            $mb_path_team_layout_settings                      = HBTHEMES_ADMIN . '/metaboxes/meta-team-layout-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'team_layout_settings',
                'types' => array(
                    'team'
                ),
                'title' => __('Team Layout Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'context' => 'side',
                'template' => $mb_path_team_layout_settings
            ));

            $mb_path_team_member_settings                      = HBTHEMES_ADMIN . '/metaboxes/meta-team-member-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'team_member_settings',
                'types' => array(
                    'team'
                ),
                'title' => __('Team Member Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_team_member_settings
            ));
        }

        if ( hb_module_enabled('hb_module_clients') ) {
            $mb_path_clients_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-clients-settings.php';
            $mb_post_settings = new VP_Metabox(array(
                'id' => 'clients_settings',
                'types' => array(
                    'clients'
                ),
                'title' => __('Clients Settings', 'hbthemes'),
                'priority' => 'low',
                'is_dev_mode' => false,
                'template' => $mb_path_clients_settings
            ));
        }

        $mb_path_presentation_settings                     = HBTHEMES_ADMIN . '/metaboxes/meta-presentation-settings.php';
        $mb_presentation_settings = new VP_Metabox(array(
            'id' => 'presentation_settings',
            'types' => array(
                'page',
            ),
            'title' => __('Presentation Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_presentation_settings
        ));

        $mb_path_featured_section_settings                 = HBTHEMES_ADMIN . '/metaboxes/meta-featured-page-section.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'featured_section',
            'types' => array(
                'page',
                'team'
            ),
            'title' => __('Featured Section Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_featured_section_settings
        ));
        

        $mb_path_contact_page_template_settings            = HBTHEMES_ADMIN . '/metaboxes/meta-contact-page-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'contact_page_settings',
            'types' => array(
                'page'
            ),
            'title' => __('Contact Template Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_contact_page_template_settings
        ));

        $mb_path_post_format_settings                      = HBTHEMES_ADMIN . '/metaboxes/meta-post-format-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'post_format_settings',
            'types' => array(
                'post'
            ),
            'title' => __('Post Format Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_post_format_settings
        ));
        $mb_path_blog_page_template_settings               = HBTHEMES_ADMIN . '/metaboxes/meta-blog-page-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'blog_page_settings',
            'types' => array(
                'page'
            ),
            'title' => __('Classic Blog Template Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_blog_page_template_settings
        ));

        $mb_path_blog_page_minimal_template_settings       = HBTHEMES_ADMIN . '/metaboxes/meta-blog-page-minimal-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'blog_page_minimal_settings',
            'types' => array(
                'page'
            ),
            'title' => __('Minimal Blog Template Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_blog_page_minimal_template_settings
        ));

        $mb_path_grid_blog_page_template_settings          = HBTHEMES_ADMIN . '/metaboxes/meta-blog-grid-page-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'blog_grid_page_settings',
            'types' => array(
                'page'
            ),
            'title' => __('Grid Blog Template Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_grid_blog_page_template_settings
        ));

        $mb_path_fw_blog_page_template_settings            = HBTHEMES_ADMIN . '/metaboxes/meta-blog-fw-page-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'blog_fw_page_settings',
            'types' => array(
                'page'
            ),
            'title' => __('Fullwidth Blog Template Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_fw_blog_page_template_settings
        ));

        $mb_path_general_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-general-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'general_settings',
            'types' => array(
                'post',
                'page',
                'team',
                'portfolio',
                'faq'
            ),
            'title' => __('General Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_general_settings
        ));

        $mb_path_layout_settings                           = HBTHEMES_ADMIN . '/metaboxes/meta-layout-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'layout_settings',
            'types' => array(
                'post',
                'page'
            ),
            'title' => __('Layout Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'context' => 'side',
            'template' => $mb_path_layout_settings
        ));

        $mb_path_background_settings                       = HBTHEMES_ADMIN . '/metaboxes/meta-background-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'background_settings',
            'types' => array(
                'post',
                'page',
                'team',
                'portfolio',
                'faq'
            ),
            'title' => __('Background Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_background_settings
        ));

        $mb_path_misc_settings                             = HBTHEMES_ADMIN . '/metaboxes/meta-misc-settings.php';
        $mb_post_settings = new VP_Metabox(array(
            'id' => 'misc_settings',
            'types' => array(
                'post',
                'page',
                'team',
                'portfolio',
                'faq'
            ),
            'title' => __('Misc Settings', 'hbthemes'),
            'priority' => 'low',
            'is_dev_mode' => false,
            'template' => $mb_path_misc_settings
        ));
    }
    add_action('after_setup_theme', 'hb_init_metaboxes');


    /* SEARCH FILTER
    ================================================== */
    add_action('pre_get_posts','hb_search_filter');
    if (!function_exists('hb_search_filter')) {
        function hb_search_filter($query) {
            if ( !is_admin() && $query->is_main_query() ) {
                if ($query->is_search) {
                    $query->set( 's', rtrim(get_search_query()) );
                }
            }
        }
    }


    /* CUSTOM WORDPRESS LOGIN LOGO
    ================================================== */
    add_action('login_head', 'hb_custom_login_logo');
    function hb_custom_login_logo() {
        if (hb_options('hb_wordpress_logo')) {
            echo '<style type="text/css">
                h1 a { background-image:url(' . hb_options('hb_wordpress_logo') . ') !important; background-size:contain !important; width:274px !important; height: 63px !important; }
            </style>';
        }
    }

    add_filter( 'login_headerurl', 'hb_custom_login_logo_url' );
    function hb_custom_login_logo_url($url) {
        return get_site_url();
    }



    /* REGISTER DEFAULT SIDEBAR
    ================================================== */
    register_sidebar( array(
        'name'         => __( 'Default Sidebar', 'hbthemes' ),
        'id'           => 'hb-default-sidebar',
        'description'  => __( 'This is a default sidebar for widgets. You can create unlimited sidebars in Appearance > Sidebar Manager. You need to select this sidebar in page meta settings to display it.','hbthemes' ),
        'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>'
    ));


    /* REGISTER SIDE PANEL SIDEBAR
    ================================================== */
    register_sidebar( array(
        'name'         => __( 'Side Panel Section', 'hbthemes' ),
        'id'           => 'hb-side-section-sidebar',
        'description'  => __( 'Add your widgets for the side panel section here. Make sure you have enabled the offset side panel section option in Highend Options > Layout Settings > Header Settings.','hbthemes' ),
        'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>'
    ));


    /* GET SIDEBARS
    ================================================== */
    function hb_get_sidebars() {
        return get_option('sbg_sidebars');
    }


    /* FOOTER WIDGET AREAS
    ================================================== */
    if (function_exists('register_sidebar')) {
        // default sidebar array
        $sidebar_attr = array(
            'name' => '',
            'description' => __('This is an area for widgets. Drag and drop your widgets here.', 'hbthemes'),
            'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        );
        $sidebar_id   = 0;
        $hb_sidebar   = array(
            "Footer 1",
            "Footer 2",
            "Footer 3",
            "Footer 4",
			"Footer social"
        );
		
		  $hb_sidebar1   = array(
            "Mobile Footer 1",
            "Mobile Footer 2",
            "Mobile Footer 3",
            "Mobile Footer 4",
			"Mobile Footer social"
        );
        foreach ($hb_sidebar as $sidebar_name) {
            $sidebar_attr['name'] = $sidebar_name;
            $sidebar_attr['id']   = 'custom-sidebar' . $sidebar_id++;
            register_sidebar($sidebar_attr);
        } 
		foreach ($hb_sidebar1 as $sidebar_name) {
            $sidebar_attr['name'] = $sidebar_name;
            $sidebar_attr['id']   = 'custom-sidebar-mobile' . $sidebar_id++;
            register_sidebar($sidebar_attr);
        }
    }


    /*  THEME WIDGETS
    ================================================== */
    include(HBTHEMES_INCLUDES . '/widgets/widget-most-commented-posts.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-latest-posts.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-latest-posts-simple.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-most-liked-posts.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-recent-comments.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-testimonials.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-video.php');
    //include(HBTHEMES_INCLUDES . '/widgets/widget-instagram.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-pinterest.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-flickr.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-dribbble.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-google.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-facebook.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-contact-info.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-social-icons.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-gmap.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-twitter.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-portfolio.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-gallery.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-portfolio-random.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-most-liked-portfolio.php');
    include(HBTHEMES_INCLUDES . '/widgets/widget-ads-300x250.php');


    /* UNREGISTER THEME WIDGETS
    ================================================== */
    function hb_unregister_widgets() {
        $widgets_to_unreg = array();

        if ( !hb_module_enabled('hb_module_portfolio') ) {
            $widgets_to_unreg[] = 'HB_Liked_Portfolio_Widget';
            $widgets_to_unreg[] = 'HB_Portfolio_Widget_Rand';
            $widgets_to_unreg[] = 'HB_Portfolio_Widget';
        }

        if ( !hb_module_enabled('hb_module_gallery') ) {
            $widgets_to_unreg[] = 'HB_Gallery_Widget';
        }

        if ( !hb_module_enabled('hb_module_testimonials') ) {
            $widgets_to_unreg[] = 'HB_Testimonials_Widget';
        }

        foreach ($widgets_to_unreg as $widget) {
            unregister_widget( $widget );
        }
    }

    add_action( 'widgets_init', 'hb_unregister_widgets' );


    /* LOAD MORE
    ================================================== */
    function wp_infinitepaginate() {
        $loopFile       = $_POST['loop_file'];
        $paged          = $_POST['page_no'];
        $category       = $_POST['category'];

        if ($category != '' && $category != ' '){
            $category = explode("+", $category);
        } else {
            $category = array();
        }

        $col_count = "";
        $posts_per_page = get_option('posts_per_page');

        if ( isset($_POST['col_count'] ))
            $col_count      = $_POST['col_count'];

        query_posts(array(
            'paged' => $paged,
            'category__in' => $category,
            'post_status' => array('publish')
        ));

        get_template_part($loopFile);
        exit;
    }
    add_action('wp_ajax_infinite_scroll', 'wp_infinitepaginate');
    add_action('wp_ajax_nopriv_infinite_scroll', 'wp_infinitepaginate');



    /* IMPORT DEMO
    ================================================== */
    add_action('wp_ajax_hb_ajax_import_options', 'hb_ajax_import_options');
    function hb_ajax_import_options() {
        include_once(HBTHEMES_ADMIN . '/demo-importer/importer.php');

        parse_str($_POST["options"], $options);
        if (!empty($options['id'])) {
            $demo_name = $options['name'];
            $demo_id = $options['id'];

            $content = $options['content'];
            $sliders = $options['sliders'];
            $widgets = $options['widgets'];
            $media = $options['media'];
            $highend_options = $options['highend_options'];
            $essential_grid = $options['essential_grid'];

            echo hb_import_demo($demo_id, $demo_name, $content, $sliders, $widgets, $media, $highend_options, $essential_grid);
        }
        
        exit;
    }

    add_action('wp_ajax_hb_ajax_get_to_json', 'hb_ajax_get_to_json');
    function hb_ajax_get_to_json() {
        parse_str($_POST["options"], $options);
        if (!empty($options['id'])) {
            $demo_id = $options['id'];

            $json_object_uri = HBTHEMES_ADMIN_URI . '/demo-importer/' . $demo_id . "/theme_options.txt";
            $json_object_string = file_get_contents($json_object_uri);
            
            echo $json_object_string;
            
        }
        
        exit;
    }


    /* MAINTENANCE MODE
    ================================================== */
    function maintenace_mode() {
        $hidden_param = "";

        if ( !hb_module_enabled('hb_module_coming_soon_mode') ) return; 

        if (isset($_GET['hb_maintenance'])){
            $hidden_param = $_GET['hb_maintenance'];
        }
        
        if ((!current_user_can('edit_themes') && hb_options('hb_enable_maintenance')) || (!is_user_logged_in() && hb_options('hb_enable_maintenance')) || ($hidden_param == 'yes') ) {
            get_template_part('hb-maintenance');
            exit;
        }
    }
    add_action('get_header', 'maintenace_mode');
    

    /* REDIRECT TO THEME OPTIONS AFTER ACTIVATE THEME
    ================================================== */
    if (is_admin() && isset($_GET['activated']) && $pagenow == "themes.php") {
        header('Location: ' . admin_url() . 'themes.php?page=highend_options#_hb_general_settings');
    }


    /* CUSTOM WP ADMIN BAR LINKS
    ================================================== */
    if ( current_user_can( 'manage_options' ) ){
        add_action('admin_bar_menu', 'hb_toolbar_link_to', 140);
    }
    function hb_toolbar_link_to($wp_admin_bar) {
        $theme_options_url = admin_url() . 'themes.php?page=highend_options#_hb_general_settings';
        $args              = array(
            'id' => 'highend_theme_options_link',
            'title' => 'Highend Options',
            'href' => $theme_options_url,
            'meta' => array(
                'class' => 'highend_theme_options_link'
            )
        );
        $wp_admin_bar->add_node($args);
    }


    /* CUSTOM POST THUMBNAILS IN ADMIN
    ================================================== */
    add_filter('manage_posts_columns', 'tcb_add_post_thumbnail_column', 5);
    add_filter('manage_pages_columns', 'tcb_add_post_thumbnail_column', 5);
    function tcb_add_post_thumbnail_column($cols) {
        $cols['tcb_post_thumb'] = __('Featured Image', 'hbthemes');
        return $cols;
    }
    add_action('manage_posts_custom_column', 'tcb_display_post_thumbnail_column', 5, 2);
    add_action('manage_pages_custom_column', 'tcb_display_post_thumbnail_column', 5, 2);
    function tcb_display_post_thumbnail_column($col, $id) {
        switch ($col) {
            case 'tcb_post_thumb':
                if (function_exists('the_post_thumbnail'))
                    echo the_post_thumbnail('thumbnail');
                else
                    echo 'Not supported in theme';
                break;
        }
    }


    /* HIGHLIGHT SEARCH TERMS
    * Disabled in 3.3.4 because of img tag issue
    ================================================== */
    /*add_filter('the_excerpt', 'hb_search_excerpt_highlight');
    function hb_search_excerpt_highlight($excerpt) {
        if (!is_search()) {
            return $excerpt;
        }
        if (!is_admin()) {
            if ( get_search_query() != '' ){
                $keys    = implode('|', explode(' ', get_search_query()));
                $excerpt = preg_replace('/(' . $keys . ')/iu', '<ins class="search-ins">\0</ins>', $excerpt);
            }
        }
        return $excerpt;
    }*/


    /* AJAX LIBRARY
    ================================================== */
    function hb_add_ajax_library() {
        $html = '<script type="text/javascript">';
        $html .= 'var ajaxurl = "' . admin_url('admin-ajax.php') . '"';
        $html .= '</script>';
        echo $html;
    }
    add_action('wp_head', 'hb_add_ajax_library');


    /* SHORTCODES IN TEXT WIDGET
    ================================================== */
    function theme_widget_text_shortcode($content) {
        $content          = do_shortcode($content);
        $new_content      = '';
        $pattern_full     = '{(\[raw\].*?\[/raw\])}is';
        $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
        $pieces           = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($pieces as $piece) {
            if (preg_match($pattern_contents, $piece, $matches)) {
                $new_content .= $matches[1];
            } else {
                $new_content .= do_shortcode($piece);
            }
        }
        return $new_content;
    }
    add_filter('widget_text', 'theme_widget_text_shortcode');
    add_filter('widget_text', 'do_shortcode');


    /* HEX TO RGBA
    ================================================== */
    function hb_color($color, $alpha) {
        if (!empty($color)) {
            if ($alpha >= 0.95) {
                return $color;
            } else {
                if ($color[0] == '#') {
                    $color = substr($color, 1);
                }
                if (strlen($color) == 6) {
                    list($r, $g, $b) = array(
                        $color[0] . $color[1],
                        $color[2] . $color[3],
                        $color[4] . $color[5]
                    );
                } elseif (strlen($color) == 3) {
                    list($r, $g, $b) = array(
                        $color[0] . $color[0],
                        $color[1] . $color[1],
                        $color[2] . $color[2]
                    );
                } else {
                    return false;
                }
                $r      = hexdec($r);
                $g      = hexdec($g);
                $b      = hexdec($b);
                $output = array(
                    'red' => $r,
                    'green' => $g,
                    'blue' => $b
                );
                return 'rgba(' . implode($output, ',') . ',' . $alpha . ')';
            }
        }
    }


    // retrieves the attachment ID from the file URL
    function hb_get_image_id($image_url) {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
            return $attachment[0]; 
    }


    /* CHECK IF YOAST SEO PLUGIN IS INSTALLED
    ================================================== */
    if( !function_exists('hb_seo_plugin_installed') ) {
        function hb_seo_plugin_installed() {
            if( defined('WPSEO_VERSION') ) {
                return true;
            }
            return false;
        }
    }   


    /* ADJUST COLOR BRIGHTNESS
    ================================================== */
    function hb_darken_color($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Format the hex color string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Get decimal values
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));

        // Adjust number of steps and keep it inside 0 to 255
        $r = max(0,min(255,$r + $steps));
        $g = max(0,min(255,$g + $steps));  
        $b = max(0,min(255,$b + $steps));

        $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
        $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
        $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

        return '#'.$r_hex.$g_hex.$b_hex;
    }


    /* MAINTENANCE PAGE CHECK
    ================================================== */
    function hb_is_maintenance() {
        if ((!current_user_can('edit_themes') && hb_options('hb_enable_maintenance')) || (!is_user_logged_in() && hb_options('hb_enable_maintenance'))) {
            return true;
        }
        return false;
    }


    /* AJAX SEARCH
    ================================================== */
    add_action('init', 'hb_ajax_search_init');
    function hb_ajax_search_init() {
        add_action('wp_ajax_hb_ajax_search', 'hb_ajax_search');
        add_action('wp_ajax_nopriv_hb_ajax_search', 'hb_ajax_search');
    }
    function hb_ajax_search() {
        $search_term  = $_REQUEST['term'];
        $search_term  = apply_filters('get_search_query', $search_term);
        $search_array = array(
            's' => $search_term,
            'showposts' => 5,
            'post_type' => 'any',
            'post_status' => 'publish',
            'post_password' => '',
            'suppress_filters' => true
        );
        $query        = http_build_query($search_array);
        $posts        = get_posts($query);
        $suggestions  = array();
        global $post;
        foreach ($posts as $post):
            setup_postdata($post);
            $suggestion  = array();
            $format      = get_post_format(get_the_ID());
            $icon_to_use = 'hb-moon-file-3';
            if ($format == 'video') {
                $icon_to_use = 'hb-moon-play-2';
            } else if ($format == 'status' || $format == 'standard') {
                $icon_to_use = 'hb-moon-pencil';
            } else if ($format == 'gallery' || $format == 'image') {
                $icon_to_use = 'hb-moon-image-3';
            } else if ($format == 'audio') {
                $icon_to_use = 'hb-moon-music-2';
            } else if ($format == 'quote') {
                $icon_to_use = 'hb-moon-quotes-right';
            } else if ($format == 'link') {
                $icon_to_use = 'hb-moon-link-5';
            }
            $suggestion['label'] = esc_html($post->post_title);
            $suggestion['link']  = get_permalink();
            $suggestion['date']  = get_the_time('F j Y');
            $suggestion['image'] = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'thumbnail', array(
                'title' => ''
            )) : '<i class="' . $icon_to_use . '"></i>';
            $suggestions[]       = $suggestion;
        endforeach;
        // JSON encode and echo
        $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
        echo $response;
        exit;
    }


    /* AJAX MAIL
    ================================================== */
    add_action('wp_ajax_mail_action', 'sending_mail');
    add_action('wp_ajax_nopriv_mail_action', 'sending_mail');
    function sending_mail() {
        $site     = get_site_url();
        $subject  = __('New Message!', 'hbthemes');
        $email    = $_POST['contact_email'];
        $email_s  = filter_var($email, FILTER_SANITIZE_EMAIL);
        $comments = stripslashes($_POST['contact_comments']);
        $name     = stripslashes($_POST['contact_name']);
        $to       = hb_options('hb_contact_settings_email');
        $message  = "Name: $name \n\nEmail: $email \n\nMessage: $comments \n\nThis email was sent from $site";
        $headers  = 'From: ' . $name . ' <' . $email_s . '>' . "\r\n" . 'Reply-To: ' . $email_s;
        wp_mail($to, $subject, $message, $headers);
        exit();
    }


    /* TIME AGO
    ================================================== */
    function hb_time_ago($time) {
        $periods    = array(
            "second",
            "minute",
            "hour",
            "day",
            "week",
            "month",
            "year",
            "decade"
        );
        $lengths    = array(
            "60",
            "60",
            "24",
            "7",
            "4.35",
            "12",
            "10"
        );
        $now        = time();
        $difference = $now - $time;
        $tense      = __('ago', 'hbthemes');
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "s";
        }
        return "$difference $periods[$j] ago ";
    }


    /* GET EXCERPT
    ================================================== */
    if ( !function_exists('hb_get_excerpt') ) {
        function hb_get_excerpt($text, $len) {
            if (strlen($text) < $len) {
                return $text;
            }
            $text_words = explode(' ', $text);
            $out        = null;
            foreach ($text_words as $word) {
                if ((strlen($word) > $len) && $out == null) {
                    return substr($word, 0, $len) . "...";
                }
                if ((strlen($out) + strlen($word)) > $len) {
                    return $out . "...";
                }
                $out .= " " . $word;
            }
            return $out;
        }
    }


    /* GET COMMENT EXCERPT
    ================================================== */
    function hb_get_comment_excerpt($comment_ID = 0, $num_words = 20) {
        $comment      = get_comment($comment_ID);
        $comment_text = strip_tags($comment->comment_content);
        $blah         = explode(' ', $comment_text);
        if (count($blah) > $num_words) {
            $k             = $num_words;
            $use_dotdotdot = 1;
        } else {
            $k             = count($blah);
            $use_dotdotdot = 0;
        }
        $excerpt = '';
        for ($i = 0; $i < $k; $i++) {
            $excerpt .= $blah[$i] . ' ';
        }
        $excerpt = trim($excerpt, '');
        $excerpt = trim($excerpt, ',');
        $excerpt .= ($use_dotdotdot) ? '...' : '';
        return apply_filters('get_comment_excerpt', $excerpt);
    }


    /* WIDGET UPLOAD SCRIPT
    ================================================== */
    add_action('admin_print_scripts-widgets.php', 'wp_ss_image_admin_scripts');
    function wp_ss_image_admin_scripts() {
        wp_enqueue_script('wp-ss-image-upload', get_template_directory_uri() . '/scripts/widget_upload.js', array(
            'jquery',
            'media-upload',
            'thickbox'
        ));
    }


    /* HIDE META
    ================================================== */
    add_action('admin_print_scripts-post-new.php', 'hb_hide_meta_admin_scripts');
    add_action('admin_print_scripts-post.php', 'hb_hide_meta_admin_scripts');
    function hb_hide_meta_admin_scripts() {
        wp_enqueue_script('hb-hide-meta', get_template_directory_uri() . '/admin/metaboxes/hide-meta.js', array(
            'jquery'
        ));
    }

    
    /* SHORTCODE PARAGRAPH FIX
    ================================================== */
    function shortcode_empty_paragraph_fix($content) {
        $array   = array(
            '<p>[' => '[',
            ']</p>' => ']',
            '<br/>[' => '[',
            ']<br/>' => ']',
            ']<br />' => ']',
            '<br />[' => '['
        );
        $content = strtr($content, $array);
        return $content;
    }
    add_filter('the_content', 'shortcode_empty_paragraph_fix');


    /* QUICK SHORTCODES
    ================================================== */
    add_shortcode('wp-link', 'wp_link_shortcode');
    function wp_link_shortcode() {
        return '<a href="http://wordpress.org" target="_blank">WordPress</a>';
    }

    add_shortcode('the-year', 'the_year_shortcode');
    function the_year_shortcode() {
        return date('Y');
    }


    /* NEW LINE TO P ELEMENT
    ================================================== */
    function hb_nl2p($string, $line_breaks = false, $xml = true){
        // Remove existing HTML formatting to avoid double-wrapping things
        $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);
     
        // It is conceivable that people might still want single line-breaks
        // without breaking into a new paragraph.
        if ($line_breaks == true)
            return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '<br'.($xml == true ? ' /' : '').'>'), trim($string)).'</p>';
        else 
            return '<p>'.preg_replace("/([\n]{1,})/i", "</p>\n<p>", trim($string)).'</p>';
    }


    /* MOBILE MENU
    ================================================== */
    function hb_mobile_menu(){       

        global $woocommerce;
        $cart_url = "";
        $cart_total = "";
        if ( class_exists('Woocommerce') && hb_options('hb_enable_sticky_shop_button') ) {
            $cart_total = $woocommerce->cart->get_cart_total();
            $cart_url = '<a class="mobile-menu-shop" href="'.$woocommerce->cart->get_cart_url().'"><i class="hb-icon-cart"></i><span class="hb-cart-total-header">'.$cart_total.'</span></a>'. "\n";
        }

        if ( vp_metabox('misc_settings.hb_onepage_also') ){
            $mobile_menu_args = array(
                'echo'                  => false,
                'theme_location'        => 'one-page-menu',
                'menu_class'        => 'menu-main-menu-container',
                'fallback_cb'           => ''
            );   
        } else {
            if ( has_nav_menu ('mobile-menu') ) {
                $mobile_menu_args = array(
                    'echo'              => false,
                    'theme_location'    => 'mobile-menu',
                    'menu_class'        => 'menu-main-menu-container',
                    'fallback_cb'       => ''
                );
            } else {
                $mobile_menu_args = array(
                    'echo'              => false,
                    'theme_location'    => 'main-menu',
                    'menu_class'        => 'menu-main-menu-container',
                    'fallback_cb'       => ''
                );
            }
        }
                                    
        $mobile_menu_output  = "";
        $mobile_menu_class   = "";

        if ( hb_options('hb_interactive_mobile_dropdown') ){
            $mobile_menu_class   = " interactive";
        }

        $mobile_menu_output .= '<div id="mobile-menu-wrap">'. "\n";   

        if ( hb_options('hb_search_in_menu') ) {
            $mobile_menu_output .= '<form method="get" class="mobile-search-form" action="'.home_url().'/"><input type="text" placeholder="'.__("Search", "hbthemes").'" name="s" autocomplete="off" /></form>'. "\n";
        } else {
            $mobile_menu_output .= '<div class="hb-top-holder"></div>';
        }
        $mobile_menu_output .= '<a class="mobile-menu-close"><i class="hb-icon-x"></i></a>'. "\n";
        $mobile_menu_output .= $cart_url;
        $mobile_menu_output .= '<nav id="mobile-menu" class="clearfix'.$mobile_menu_class.'">'. "\n";     
                                    
        if(function_exists('wp_nav_menu')) {
            $mobile_menu_output .= wp_nav_menu( $mobile_menu_args );
        }
        $mobile_menu_output .= '</nav>'. "\n";
        $mobile_menu_output .= '</div>'. "\n";
                                    
        return $mobile_menu_output;
    }


    /* REMOVE PAGE TEMPLATES
    ================================================== */
    function hb_remove_page_templates( $templates ) {
        if ( !hb_module_enabled('hb_module_portfolio') ) {
            unset( $templates['page-portfolio-simple.php'] );
            unset( $templates['page-portfolio-standard.php'] );
        }

        if ( !hb_module_enabled('hb_module_gallery') ) {
            unset( $templates['page-gallery-fullwidth.php'] );
            unset( $templates['page-gallery-standard.php'] );
        }

        return $templates;
    }
    add_filter( 'theme_page_templates', 'hb_remove_page_templates' );


    /* REMOVE SHORTCODES
    ================================================== */
    function hb_remove_shortcodes() {

        // PORTFOLIO
        if ( !hb_module_enabled('hb_module_portfolio')) {
            remove_shortcode( 'portfolio_fullwidth' );
            remove_shortcode( 'portfolio_carousel' );

            if ( function_exists('vc_remove_element') ) {
                vc_remove_element("portfolio_fullwidth");
                vc_remove_element("portfolio_carousel");
            }
        }

        // GALLERY
        if ( !hb_module_enabled('hb_module_gallery')) {
            remove_shortcode( 'gallery_fullwidth' );
            remove_shortcode( 'gallery_carousel' );

            if ( function_exists('vc_remove_element') ) {
                vc_remove_element("gallery_fullwidth");
                vc_remove_element("gallery_carousel");
            }
        }

        // PRICING TABLES
        if ( !hb_module_enabled('hb_module_pricing_tables')) {
            remove_shortcode( 'menu_pricing_item' );
            remove_shortcode( 'pricing_table' );

            if ( function_exists('vc_remove_element') ) {
                vc_remove_element("menu_pricing_item");
                vc_remove_element("pricing_table");
            }
        }

        // FAQ
        if ( !hb_module_enabled('hb_module_faq')) {
            remove_shortcode( 'faq' );

            if ( function_exists('vc_remove_element') ) {
                vc_remove_element("faq");
            }
        }

        // TESTIMONIALS
        if ( !hb_module_enabled('hb_module_testimonials')) {
            remove_shortcode( 'testimonial_box' );
            remove_shortcode( 'testimonial_slider' );

            if ( function_exists('vc_remove_element') ) {
                vc_remove_element("testimonial_box");
                vc_remove_element("testimonial_slider");
            }
        }

        // TEAM MEMBERS
        if ( !hb_module_enabled('hb_module_team_members')) {
            remove_shortcode( 'team_carousel' );
            remove_shortcode( 'team_member_box' );

            if ( function_exists('vc_remove_element') ) {
                vc_remove_element("team_carousel");
                vc_remove_element("team_member_box");
            }
        }

        // CLIENTS
        if ( !hb_module_enabled('hb_module_clients')) {
            remove_shortcode( 'client_carousel' );

            if ( function_exists('vc_remove_element') ) {
                vc_remove_element("client_carousel");
            }
        }
        
    }
    add_action( 'init', 'hb_remove_shortcodes' );

    function hb_buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '' ) {

        $has_image = false;
        $style = '';
        if ( (int) $bg_image > 0 && false !== ( $image_url = wp_get_attachment_url( $bg_image, 'large' ) ) ) {
            $has_image = true;
            $style .= 'background-image: url(' . $image_url . ');';
        }
        if ( ! empty( $bg_color ) ) {
            $style .= hb_get_css_color( 'background-color', $bg_color );
        }
        if ( ! empty( $bg_image_repeat ) && $has_image ) {
            if ( 'cover' === $bg_image_repeat ) {
                $style .= 'background-repeat:no-repeat;background-size: cover;';
            } elseif ( 'contain' === $bg_image_repeat ) {
                $style .= 'background-repeat:no-repeat;background-size: contain;';
            } elseif ( 'no-repeat' === $bg_image_repeat ) {
                $style .= 'background-repeat: no-repeat;';
            }
        }
        if ( ! empty( $font_color ) ) {
            $style .= hb_get_css_color( 'color', $font_color );
        }
        if ( '' !== $padding ) {
            $style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
        }
        if ( '' !== $margin_bottom ) {
            $style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
        }

        return empty( $style ) ? '' : ' style="' . esc_attr( $style ) . '"';
    }

    function hb_get_css_color( $prefix, $color ) {
        $rgb_color = preg_match( '/rgba/', $color ) ? preg_replace( array(
            '/\s+/',
            '/^rgba\((\d+)\,(\d+)\,(\d+)\,([\d\.]+)\)$/',
        ), array( '', 'rgb($1,$2,$3)' ), $color ) : $color;
        $string = $prefix . ':' . $rgb_color . ';';
        if ( $rgb_color !== $color ) {
            $string .= $prefix . ':' . $color . ';';
        }

        return $string;
    }