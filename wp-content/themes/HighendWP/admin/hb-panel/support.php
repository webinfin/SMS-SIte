<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap hb-alt-wrap">
  	<h2><?php echo __("Highend Theme Support","hbthemes"); ?></h2>

	 <h2 class="nav-tab-wrapper"> <a href="#support-forum" data-tab="support-forum" class="nav-tab"> Support Forum </a>
		<a href="#theme-docs" data-tab="theme-docs" class="nav-tab"> Documentation </a>
		<a href="#video-tuts" data-tab="video-tuts" class="nav-tab"> Video Tutorials </a>
		<a href="#changelog" data-tab="changelog" class="nav-tab"> Changelog </a>
		<a href="#hire-hb" data-tab="hire-hb" class="nav-tab"> Hire HB-Themes </a>
	 </h2>


	<div id="support-forum" class="hb-tabs">
	    	<div class="changelog point-releases">
				<p>By clicking on the following button, you will be redirected to our support forum.</p>
	    	</div>
	    	<p class="about-description"><a href="http://forum.hb-themes.com" target="_blank" class="button button-hero button-primary">Visit Support Forum</a></p>
	</div>


	<div id="theme-docs" class="hb-tabs">
	    	<div class="changelog point-releases">
				<p>By clicking on the following button, you will be redirected to our theme documentation page.</p>
	    	</div>
	    	<p class="about-description"><a href="http://documentation.hb-themes.com/highend" target="_blank" class="button button-hero button-primary">Read Theme Documentation</a></p>
	</div>

	<div id="video-tuts" class="hb-tabs">
	    	<div class="changelog point-releases">
				<p>By clicking on the following button, you will be redirected to our video tutorials page.</p>
	    	</div>
	    	<p class="about-description"><a href="http://documentation.hb-themes.com/highend/#video-tutorials" target="_blank" class="button button-hero button-primary">Watch Video Tutorials</a></p>
	</div>

	<div id="changelog" class="hb-tabs">

	    	<div class="changelog point-releases">
				<p>By clicking on the following button, you will be redirected to our theme changelog page.</p>
	    	</div>
	    	<p class="about-description"><a href="http://hb-themes.com/changelog/highend/" target="_blank" class="button button-hero button-primary">View Changelog</a></p>
	</div>

	<div id="hire-hb" class="hb-tabs">
	    	<div class="changelog point-releases">
				<p>We are offering design and development services.<br/>Fill out the form below and someone from HB-Themes will contact you shortly.</p>
	    	</div>
	    	<p class="about-description"><a href="http://hb-themes.com/hire/" target="_blank" class="button button-hero button-primary">Get a Quote</a></p>
	</div>

</div>
<!-- END .wrap -->

<script type="text/javascript">
jQuery(document).ready(function(e) {
    var tab_link = jQuery(".nav-tab");
	var tabs = jQuery(".hb-tabs");
	var url = window.location,
		hash = url.hash.match(/^[^\?]*/)[0];
	if(hash != ''){
		tab_link.each(function(index, element) {
            jQuery(this).removeClass('nav-tab-active');
        });
		tabs.each(function(index, element) {
            jQuery(this).removeClass('active-tab');
        });
		jQuery('a[href="'+hash+'"]').addClass('nav-tab-active');
		jQuery(""+hash).addClass('active-tab');
	} else {
		jQuery("#support-forum").addClass('active-tab');
		jQuery('a[href="#support-forum"]').addClass('nav-tab-active');
	}
	// Toggle the tabs
	tab_link.click(function(e){
		e.preventDefault();
		window.location = jQuery(this).attr('href');	
		var cur_tab = jQuery(this).data('tab');
		tab_link.each(function(index, element) {
            jQuery(this).removeClass('nav-tab-active');
        });
		tabs.each(function(index, element) {
            jQuery(this).removeClass('active-tab');
        });
		jQuery(this).addClass('nav-tab-active');
		jQuery("#"+cur_tab).addClass('active-tab');
	});
});
</script>