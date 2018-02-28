<?php
/**
 * @package WordPress
 * @subpackage Highend
 */

?>
<?php if ( vp_metabox('misc_settings.hb_onepage') && !vp_metabox('misc_settings.hb_disable_navigation')) { ?>
	<ul id="hb-one-page-bullets"></ul>
<?php } ?>

<?php if ( hb_options('hb_to_top_button') && !is_page_template('page-blank.php') ) { ?>
<!-- Back to Top Button -->
<a id="to-top"><i class="<?php echo hb_options('hb_back_to_top_icon'); ?>"></i></a>
<!-- END #to-top -->
<?php } ?>

<?php if ( !is_page_template('page-blank.php') && hb_options('hb_enable_quick_contact_box') ) {
	get_template_part('includes/aside-contact-panel');
} ?>

<?php
	// Google Analytics from Theme Options
	$google_analytics_code = hb_options('hb_analytics_code');
	if ($google_analytics_code){
		echo $google_analytics_code;
	}
	
	// Custom Script from Theme Options
	$custom_script_code = hb_options('hb_custom_script');
	if ($custom_script_code){
		echo '<script type="text/javascript">' . $custom_script_code . '</script>';
	}
?>

<?php
if ( !is_page_template('page-presentation-fullwidth.php') ){
 if ( !is_page_template('page-blank.php') && (hb_options('hb_enable_pre_footer_area') && vp_metabox('layout_settings.hb_pre_footer_callout') == "default" || hb_options('hb_enable_pre_footer_area') && vp_metabox('layout_settings.hb_pre_footer_callout') == "" || vp_metabox('layout_settings.hb_pre_footer_callout') == "show" ) ) {
 	get_template_part('includes/pre-footer');
} } ?>
<?php
if ( !is_page_template( 'page-presentation-fullwidth.php' ) ) {
if (
	( ( hb_options('hb_enable_footer_widgets') && (vp_metabox('layout_settings.hb_footer_widgets') == "default" || vp_metabox('layout_settings.hb_footer_widgets') == "" )) || vp_metabox('layout_settings.hb_footer_widgets') == "show" ) && 
	!is_page_template('page-blank.php') 
) { ?>

<?php
	$footer_bg_image = "";
	$footer_bg = "";
if ( hb_options('hb_enable_footer_background' ) ) {
	$footer_bg_image = " background-image"; 
}

if ( hb_options("hb_footer_bg_image") ){
	$footer_bg_image = " footer-bg-image";
	$footer_bg = ' style="background-image: url('. hb_options("hb_footer_bg_image") .')"';
}

?>

<!-- BEGIN #footer OPTION light-style -->
<footer id="footer" class="dark-style<?php echo $footer_bg_image; ?>" <?php echo $footer_bg; ?>>
	
	<!-- BEGIN .container -->
	<div class="container">
		<div class="row footer-row">
                    <div class="neo-opp">
                        <div class="sec-two">
                                <?php dynamic_sidebar('footer-logo'); ?>
                         </div>
                        <div class="fr-ging">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
							
						<div class="fr-oran">
                                <?php dynamic_sidebar('footer-2'); ?>
								<!-- <h4>Learn More</h4>								
									<?php //wp_nav_menu( array('menu'=> 'Learn More' ) ); ?>
								<h4>Support</h4>
									<?php //wp_nav_menu( array('menu'=> 'Support' ) ); ?>
								-->
                        </div>
							
                        <div class="fr-oran">
                                <?php dynamic_sidebar('footer-3'); ?>
								<!--<h4>Our Solutions</h4>								
									<?php //wp_nav_menu( array('menu'=> 'Our Solutions' ) ); ?>								
								<h4>Popular Use Cases</h4>
									<?php //wp_nav_menu( array('menu'=> 'Popular Use Cases' ) ); ?>-->
                            </div>
                        <div class="fr-drte">
                                <?php //dynamic_sidebar('footer-4'); ?>
								<h4>Follow Us</h4>
								<?php if ( function_exists('cn_social_icon') ) echo cn_social_icon(); ?>
                            </div>
                        <div class="fr-term">
                         <?php dynamic_sidebar('footer-5'); ?>
                            </div>
<!--                      <div class="fr-contact">
                           <?php dynamic_sidebar('footer-6'); ?>
                            </div>-->
                         <div class="ic-social">
                           <?php dynamic_sidebar('footer-7'); ?>
                            </div>
                     
                </div>
                    <div class="mobile-footer" style="display:none;">
          
                                <button class="accordion">Learn More</button>
                                <div class="panel">
                                    <?php dynamic_sidebar('mobile-footer-1'); ?>
									<?php //wp_nav_menu( array('menu'=> 'Learn More' ) ); ?>
                                </div>
                                <button class="accordion">Support</button>
                                <div class="panel">
                                     <?php dynamic_sidebar('mobile-footer-2'); ?>
									 <?php //wp_nav_menu( array('menu'=> 'Support' ) ); ?>
                                </div>
                                <button class="accordion">Our Solutions</button>
                                <div class="panel">
                                     <?php dynamic_sidebar('mobile-footer-3'); ?>
									 <?php //wp_nav_menu( array('menu'=> 'Our Solutions' ) ); ?>	
                                </div> 
								<!-- <button class="accordion">Download</button> -->
                                <div class="panel">
                                     <?php //dynamic_sidebar('mobile-footer-4'); ?>
                                </div>
                                <button class="accordion">Popular Use Cases</button>
                                <div class="panel">
                                     <?php dynamic_sidebar('mobile-footer-4'); ?>
									 <?php //wp_nav_menu( array('menu'=> 'Popular Use Cases' ) ); ?>
                                </div>
                               <!--<button class="accordion">Contact Information</button> -->
                                <div class="panel">
                                     <?php //dynamic_sidebar('footer-6'); ?>
                                </div>
                                
                                <div class="social_icons_ss">
								<h2>Follow Us</h2>
                                     
                                     <?php //dynamic_sidebar('mobile-footer-social'); ?>
									<?php if ( function_exists('cn_social_icon') ) echo cn_social_icon(); ?>
                                </div>
								
								
								              <div class="logo-footer">
<a href="#"><img src="<?php echo get_template_directory_uri(); ?>-child/images/SMSWhite1.svg"></a>
</div>
							
                    </div>
		</div>	
            
	</div>
	<!-- END .container -->



<?php
if ( !is_page_template( 'page-presentation-fullwidth.php' ) ){
if ( hb_options('hb_enable_footer_copyright') && !is_page_template('page-blank.php') ) {
	get_template_part('includes/copyright');
} } ?>

<?php if ( ( is_singular('post') && hb_options('hb_blog_enable_next_prev') ) || (is_singular('portfolio') && hb_options('hb_portfolio_enable_next_prev') ) || ( is_singular('team') && hb_options('hb_staff_enable_next_prev') ) ) { ?>
	<nav class="hb-single-next-prev">
	<?php 
	$prev_post = get_previous_post(); 
	$next_post = get_next_post(); 
	?>
	
	<?php if ( !empty($prev_post) ) { ?>
	<a href="<?php echo get_permalink($prev_post->ID); ?>" title="<?php echo $prev_post->post_title; ?>" class="hb-prev-post">
		<i class="hb-moon-arrow-left-4"></i>
		<span class="text-inside"><?php _e('Prev','hbthemes'); ?></span>
	</a>
	<?php } ?>

	<?php if ( !empty($next_post) ) { ?>
	<a href="<?php echo get_permalink($next_post->ID); ?>" title="<?php echo $next_post->post_title; ?>" class="hb-next-post">
		<i class="hb-moon-arrow-right-5"></i>
		<span class="text-inside"><?php _e('Next','hbthemes'); ?></span>
	</a>
	<?php } ?>

</nav>
<!-- END LINKS -->
<?php } ?>

</div>
<!-- END #main-wrapper -->

</div>
<!-- END #hb-wrap -->

<?php if ( hb_options('hb_side_section') ) { ?>
	<div id="hb-side-section">
		<a href="#" class="hb-close-side-section"><i class="hb-icon-x"></i></a>
		<?php if ( is_active_sidebar( 'hb-side-section-sidebar' ) ) {
			dynamic_sidebar('hb-side-section-sidebar');
		} else {
			echo '<p class="aligncenter" style="margin-top:30px;">';
			_e('Please add widgets to this widgetized area ("Side Panel Section") in Appearance > Widgets.', 'hbthemes');
			echo '</p>';
		} ?>
	</div>
<?php } ?>


<?php if ( hb_options('hb_search_style') == 'hb-modern-search') { ?>
	<!-- BEGIN #fancy-search -->
	<div id="modern-search-overlay">
		<a href="#" class="hb-modern-search-close"><i class="hb-icon-x"></i></a>

		<div class="table-middle hb-modern-search-content">
			<p><?php _e('Type and press Enter to search', 'hbthemes'); ?></p>
			
			<form method="get" id="hb-modern-form" action="<?php echo home_url( '/' ); ?>" novalidate="" autocomplete="off">
				<input type="text" value="" name="s" id="hb-modern-search-input" autocomplete="off">
			</form>
		</div>

	</div>
<?php } ?>

<!-- BEGIN #hb-modal-overlay -->
<div id="hb-modal-overlay"></div>
<!-- END #hb-modal-overlay -->


</footer>
<!-- END #footer -->
<?php } } ?>   



<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}
</script>
<script>
jQuery(document).ready(
    function(){
        jQuery("#show-nav-menu-ai").click(function (e) {
            jQuery(".mobile-menu-top").slideToggle();
			jQuery(".mobile-menu-top").toggleClass('menu-open');
            
			e.preventDefault();
        });
    });
	jQuery(window).scroll(function(){		
		 var scroll = jQuery(window).scrollTop();
		if(scroll>=20)
		{
			jQuery('#hb-header').addClass('sticky');
		}
		else{
			
				jQuery('#hb-header').removeClass('sticky');
			
		}
	});
/* jQuery(document).ready(
    function(){
        jQuery("#show-nav-menu-ai").click(function () {
            jQuery(".mobile-menu-top").slideToggle();
        });
    }); */
 </script>
<?php wp_footer(); ?>

 
<?php if ( is_page( 475) ){ ?>
	<script>
	jQuery(document).ready(function(){
		var href = '';
		href = jQuery("<p>(SAVE 20%)</p>").appendTo("[href='#1514269550455-b6b9b7a1-0e27'] .vc_tta-title-text");
		href = jQuery("<p>(SAVE 15%)</p>").appendTo("[href='#1514269545102-9b7b2ec6-bc6c'] .vc_tta-title-text");
		href = jQuery("<p>(REGULAR PRICE)*</p>").appendTo("[href='#1514269545038-9a65f493-5f88'] .vc_tta-title-text");
		console.log(href);

		jQuery('.vc_tta-tab:eq(0)').removeClass('vc_active');
		jQuery('.vc_tta-panel.pricing_tab_sec:eq(0)').removeClass('vc_active');
		jQuery('.vc_tta-tab:eq(1)').addClass('vc_active');
		jQuery('.vc_tta-panel.pricing_tab_sec:eq(1)').addClass('vc_active');
		
		if (jQuery(window).width() < 767) {
			jQuery('.vc_tta-panel-heading').hide(); 
		}	
	});
	
	
	

	
</script>
<?php }?>
<script>
	jQuery(document).ready(function(){
		
	


jQuery('.plans').click(function(){
	
	var idval = jQuery(this).parent().find('input').val();
	var firstop = idval.split('_');
	var hideop = firstop[0];
	var showop = firstop[1];
	jQuery('.'+hideop).hide();
	jQuery('#'+idval).show();
	
	jQuery('.month').removeClass('showtab');
	jQuery('.annual').removeClass('showtab');
	jQuery('.year').removeClass('showtab');
	
	jQuery('#month_'+showop).addClass('showtab');
	jQuery('#annual_'+showop).addClass('showtab');
	jQuery('#year_'+showop).addClass('showtab');
	
});

jQuery('.vc_tta-tabs-list').click(function(){
	if (jQuery(".showtab").length > 0) 
	{
	jQuery('.month').hide();
	jQuery('.annual').hide();
	jQuery('.year').hide();
	jQuery('.showtab').show();
	}
	
});

	});
	
function plans(plan_duration,plan_name)
{
	jQuery('.'+plan_duration).hide();
	jQuery('#'+plan_duration+"_"+plan_name).show();
}


jQuery(document).ready(function() {
	<?php if(isset($_GET['view'])){ 
	if($_GET['view']=='grid'){ ?>
	jQuery('.viewlist').removeClass('active');
	jQuery('.display-grid-view').addClass('active');
	check_height();
 jQuery(window).resize(function(){
 
  jQuery('.grid .hb-woo-product.col-4').each(function() {
     jQuery(this).css("height", "auto");
   });
  check_height();
});

function check_height(){
   var maxHeight = -1;
    //console.log("yes");
   jQuery('.grid .hb-woo-product.col-4').each(function() {
     maxHeight = maxHeight > jQuery(this).height() ? maxHeight : jQuery(this).height();
   });

   jQuery('.grid .hb-woo-product.col-4').each(function() {
     jQuery(this).height(maxHeight);
   });
  }
 <?php } else{  ?>
	jQuery('.viewlist').removeClass('active');
	jQuery('.display-list-view').addClass('active');
	<?php } }else{ ?>
	jQuery('.viewlist').removeClass('active');
	jQuery('.display-list-view').addClass('active');
	<?php } ?>	
});
</script>

<script>

jQuery(document).ready(function() {
	jQuery('.filteroption').click(function(){		
		jQuery('.filterlist').toggle();
	});
check_height();

 jQuery(window).resize(function(){
 
  jQuery('.trade_show_cate').each(function() {
     jQuery(this).css("height", "auto");
   });
  check_height();
});

function check_height(){
   var maxHeight = -1;
    //console.log("yes");
   jQuery('.trade_show_cate').each(function() {
     maxHeight = maxHeight > jQuery(this).height() ? maxHeight : jQuery(this).height();
   });

   jQuery('.trade_show_cate').each(function() {
     jQuery(this).height(maxHeight);
   });
  }
  
  
  
  jQuery('.main-navigation').find('ul li').each(function(){ 
var obj = jQuery(this);
if(jQuery(this).find('ul li').length > 0){ jQuery(this).find('ul li').hover(function(){ 
jQuery(obj).toggleClass('my-cstm-class');}); } });

if ( jQuery(window).width() < 769 ) {
	 jQuery('.desktop-filter').remove();
	 jQuery('.woof_submit_search_form').show();
} else {
	jQuery('.mobile-filter').hide();
}
	
jQuery(window).resize(function() {
	if ( jQuery(window).width() > 900 ) {
   jQuery('.mobile-menu-top').hide();
   jQuery('#show-nav-menu-ai').removeClass('open');
   jQuery('.sub-menu').hide();
	}
}).resize();	

});

</script>
<?php if ( !is_front_page() && !is_home() ) 
{
?>
<script> jQuery("#logo").addClass("inner-logo");</script>
<?php		 
}  
?>
</body>
<!-- END body -->

</html>
<!-- END html -->