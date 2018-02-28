<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>

<?php
    global $post;
    if( is_object($post) ) {
        $post_id = $post->ID;
    }

    if (function_exists('is_shop') && is_shop() ){
        $alternative_url = vp_metabox('misc_settings.hb_page_alternative_logo', null, $post_id);
    } else {
        $alternative_url = vp_metabox('misc_settings.hb_page_alternative_logo');
    }

    $logo_url = hb_options('hb_logo_option');
    $retina_url = hb_options('hb_logo_option_retina');
    $site_name = get_bloginfo('name');
    $logo_max_height = hb_options('hb_logo_max_height');

    $hb_alignment_option = hb_options('hb_side_nav_align');
    $hb_alignment = ' style="text-align:center!important;"';

    if ( $hb_alignment_option == 'hb-side-alignment-left' ){
        $hb_alignment = ' style="text-align:left!important;"';
    } else if ( $hb_alignment_option == 'hb-side-alignment-center' ){
        $hb_alignment = 'style="text-align:center!important;"';
    } else {
        $hb_alignment = ' style="text-align:right!important;"';
    }

    if ( $logo_max_height && $logo_max_height != ''){
        echo '<style type="text/css">
        #logo img,.stuck #logo img {max-height:' . $logo_max_height . ';}
        </style>';
    }

    $transparent_class = "";
    if (hb_options('hb_side_nav_style') == 'hb-side-standard') {
        $transparent_class = " hb-non-transparent";
    }

    $style_class = "";
    if (hb_options('hb_side_color_style') == 'hb-side-light'){
        $style_class = " hb-light-style";
    }

    $image_bg = "";
    if (hb_options('hb_side_nav_bg')){
        $image_bg = ' style="background-image:url('.hb_options("hb_side_nav_bg").')"';
    }

?>
<!-- BEGIN #hb-side-navigation -->
        <div id="hb-side-navigation" class="<?php echo $transparent_class; echo $style_class;?>">
            <div class="hb-side-background"<?php echo $image_bg; ?>></div>
            <div class="hb-resp-bg">
                <a href="#" id="show-nav-menu"><i class="icon-bars"></i></a>
            </div>

            <!-- BEGIN .side-logo-wrapper -->
            <div class="side-logo-wrapper">
                <div class="side-logo" id="logo">
                        <?php if ( hb_options('hb_logo_option') ) { ?>
                        <a href="<?php echo get_bloginfo ('url') ?>" class="image-logo"<?php echo $hb_alignment; ?>>

                            <?php  if ($alternative_url) {
                                $retina_url = $alternative_url;
                            ?>
                                <img src="<?php echo $alternative_url ?>" class="default alternative-logo"<?php echo $hb_alignment; ?> alt="<?php echo $site_name; ?>"/>
                            <?php } else { ?>

                            <span class="hb-dark-logo hb-logo-wrap">

                                <?php if ( hb_options('hb_side_color_style')=='hb-side-light' ){ ?>
                                    <img src="<?php echo hb_options('hb_logo_light_option'); ?>" width="318" height="72"<?php echo $hb_alignment; ?> class="default" alt="<?php echo $site_name; ?>"/>
                                <?php } else { ?>
                                    <img src="<?php echo $logo_url; ?>" width="318" height="72"<?php echo $hb_alignment; ?> class="default" alt="<?php echo $site_name; ?>"/>
                                <?php }
                            } ?>

                                <?php if ( hb_options('hb_logo_option_retina') ){
                                    if ($alternative_url) { ?>
                                        <img src="<?php echo $retina_url; ?>" class="retina alternative-retina" width="636" height="144"<?php echo $hb_alignment; ?> alt="<?php echo $site_name; ?>"/>
                                    <?php } else { ?>

                                        <?php if ( hb_options('hb_side_color_style')=='hb-side-light' ){ ?>
                                            <img src="<?php echo hb_options('hb_logo_light_option_retina'); ?>" class="retina" width="636" height="144"<?php echo $hb_alignment; ?> alt="<?php echo $site_name; ?>"/>
                                        <?php } else { ?>
                                            <img src="<?php echo $retina_url; ?>" class="retina" width="636" height="144"<?php echo $hb_alignment; ?> alt="<?php echo $site_name; ?>"/>
                                        <?php } 
                                    }
                                } ?>
                            </span>

                            <?php if ( hb_options('hb_logo_light_option') && !$alternative_url ) { ?>
                                <span class = "hb-light-logo hb-logo-wrap">
                                    <img src="<?php echo hb_options('hb_logo_light_option'); ?>" width="318" height="72"<?php echo $hb_alignment; ?> class="default" alt="<?php echo $site_name; ?>"/>

                                    <?php if ( hb_options('hb_logo_light_option_retina') ){ ?>
                                    <img src="<?php echo hb_options('hb_logo_light_option_retina'); ?>" class="retina" width="636" height="144"<?php echo $hb_alignment; ?> alt="<?php echo $site_name; ?>"/>
                                    <?php } ?>
                                </span>
                            <?php } ?>
                        </a>

                        <?php } else { ?>
                        <h1><a href="<?php echo get_bloginfo ('url') ?>"<?php echo $hb_alignment; ?> class="plain-logo"><?php echo $site_name ?></a></h1>
                        <?php } ?>
                </div>
            </div>
            <!-- END .side-logo-wrapper -->

            <!-- BEGIN .side-nav-wrapper -->
            <div class="<?php echo $hb_alignment_option; ?>" <?php echo $hb_alignment;?>>
                <?php
                if ( vp_metabox('misc_settings.hb_onepage') && has_nav_menu('one-page-menu') ) {
                    wp_nav_menu( 
                        array( 
                            'theme_location'    => 'one-page-menu', 
                            'menu_class'        => 'hb-side-nav',
                            'container'         => 'div',
                            'menu_id'           => 'hb-side-menu',
                            'container_class'   => 'side-nav-wrapper',
                            'link_before'       => '<span>',
                            'link_after'        => '</span>',
                            'walker'            =>  new hb_custom_walker
                        )
                    );
                } else if ( has_nav_menu( 'main-menu' ) ) {
                    // User has assigned menu to this location
                    wp_nav_menu( 
                        array( 
                            'theme_location'    => 'main-menu', 
                            'menu_class'        => 'hb-side-nav',
                            'menu_id'           => 'hb-side-menu',
                            'container'         => 'div',
                            'container_class'   => 'side-nav-wrapper',
                            'link_before'       => '<span>',
                            'link_after'        => '</span>',
                            'walker'            =>  new hb_custom_walker
                        )
                    );
                } else { ?>
                    <div class="side-nav-wrapper">
                        <ul id="hb-side-menu" class="hb-side-nav empty-menu"<?php echo $hb_alignment; ?>>
                            <li><?php _e('Please attach a menu to this menu location in Appearance > Menu.', 'hbthemes'); ?></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>

            <div class="side-nav-bottom-part">
                <?php
                    $target = ' target="_self"';
                    if ( hb_options('hb_soc_links_new_tab') ){
                        $target = ' target="_blank"';
                    }
                
                if (hb_options('hb_side_nav_enable_socials')){ ?>
                    <ul class="clearfix" id="side-nav-socials" <?php echo $hb_alignment; ?>>
                        <?php
                            $hb_socials = hb_options('hb_top_header_socials');
                            if ( !empty ( $hb_socials ) ) {
                                    foreach ($hb_socials as $hb_social) {
                                        if ( $hb_social == 'custom-url' ){
                                            $hb_social_n = 'link-5';
                                        } else if ( $hb_social == 'vkontakte' ){
                                            $hb_social_n = 'vk';
                                        } else {
                                            $hb_social_n = $hb_social;
                                        }
                                    ?>
                                    <?php if ($hb_social_n != 'behance' && $hb_social_n != 'vk' && $hb_social_n != 'envelop') { ?>
                                            <li>
                                                <a href="<?php echo hb_options('hb_' . $hb_social . '_link'); ?>" original-title="<?php echo ucfirst($hb_social); ?>"<?php echo $target; ?>><i class="hb-moon-<?php echo $hb_social_n; ?>"></i></a> 
                                            </li>
                                        <?php
                                        } else if ($hb_social_n == 'envelop') { ?>
                                            <li>
                                                <a href="mailto:<?php echo hb_options('hb_' . $hb_social . '_link'); ?>" original-title="<?php echo ucfirst($hb_social); ?>"<?php echo $target; ?>><i class="hb-moon-<?php echo $hb_social_n; ?>"></i></a> 
                                            </li>
                                        <?php } else { ?>
                                            <li>
                                                <a href="<?php echo hb_options('hb_' . $hb_social . '_link'); ?>" original-title="<?php echo ucfirst($hb_social); ?>"<?php echo $target; ?>><i class="icon-<?php echo $hb_social_n; ?>"></i></a> 
                                            </li>
                                        <?php }
                                    }
                            }
                        ?>
                    </ul>
                <?php } ?>

                <?php $bottom_text = hb_options('hb_side_nav_bottom_text');

                if ($bottom_text){ ?>
                    <div class="side-nav-bottom-text" <?php echo $hb_alignment; ?>><?php echo do_shortcode($bottom_text); ?></div>
                <?php } ?>

            </div>
            <!-- END .side-nav-bottom-part -->


        </div>
        <!-- END #hb-side-navigation -->