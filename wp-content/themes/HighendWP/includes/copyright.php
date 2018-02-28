<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>
<!-- BEGIN #copyright-wrapper -->
<div id="copyright-wrapper" class="<?php echo hb_options('hb_copyright_style'); ?> <?php echo hb_options('hb_copyright_color_scheme'); if ( hb_options("hb_footer_bg_image") ){ echo 'footer-bg-image'; } ?> clearfix"> <!-- Simple copyright opcija light style opcija-->

    <!-- BEGIN .container -->
    <div class="container">

        <!-- BEGIN #copyright-text -->
        <div id="copyright-text">
            <p><?php echo do_shortcode(hb_options('hb_copyright_line_text')); ?>

            <?php
                if (hb_options('hb_enable_backlink')){
                    echo ' <a href="http://www.mojomarketplace.com/store/hb-themes?r=hb-themes&utm_source=hb-themes&utm_medium=textlink&utm_campaign=themesinthewild">Theme by HB-Themes.</a>';
                }
            ?>

            </p>
        </div>
        <!-- END #copyright-text -->

        <?php
        if ( has_nav_menu ('footer-menu') ) {
            wp_nav_menu( array ( 'theme_location' => 'footer-menu' , 'container_id' => 'footer-menu', 'container_class'=> 'clearfix', 'menu_id'=>'footer-nav', 'menu_class' => '', 'walker' =>  new hb_custom_walker) );
        } 
        ?>

    </div> 
    <!-- END .container -->

</div>
<!-- END #copyright-wrapper -->