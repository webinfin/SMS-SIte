<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>

<?php
$is_shop = false;

if ( is_page_template( 'page-presentation-fullwidth.php' ) || is_search() ){
	return;
}

if ( function_exists('is_woocommerce') && is_woocommerce() ) {
	if ( function_exists('is_shop') && is_shop() ){
		if ( function_exists('woocommerce_get_page_id') ) {
			$post_id = woocommerce_get_page_id( 'shop' );
			$is_shop = true;
		}
	}
}

if ( $is_shop ){
	$section_type = vp_metabox('featured_section.hb_featured_section_options', null, $post_id);
	$section_effect = vp_metabox('featured_section.hb_featured_section_effect', null, $post_id);
	$thumb = get_post_thumbnail_id($post_id); 
	$rev_slider = vp_metabox('featured_section.hb_rev_slider', null, $post_id);
	$layer_slider = vp_metabox('featured_section.hb_layer_slider', null, $post_id);
	$video_link = vp_metabox('featured_section.hb_page_video', null, $post_id);
	$fi_size = vp_metabox('featured_section.hb_featured_section_height', null, $post_id);
	$fi_custom_size = vp_metabox('featured_section.hb_featured_image_height', null, $post_id);
	$fi_parallax = vp_metabox('featured_section.hb_featured_section_parallax', null, $post_id);
} else {
	$section_type = vp_metabox('featured_section.hb_featured_section_options');
	$section_effect = vp_metabox('featured_section.hb_featured_section_effect');
	$thumb = get_post_thumbnail_id(); 
	$rev_slider = vp_metabox('featured_section.hb_rev_slider');
	$layer_slider = vp_metabox('featured_section.hb_layer_slider');
	$video_link = vp_metabox('featured_section.hb_page_video');
	$fi_size = vp_metabox('featured_section.hb_featured_section_height');
	$fi_custom_size = vp_metabox('featured_section.hb_featured_image_height');
	$fi_parallax = vp_metabox('featured_section.hb_featured_section_parallax');
}
$fi_custom_class = "";
$fi_parallax_class = "";
if ($thumb) { $full_image = wp_get_attachment_image_src ( $thumb, 'full' ); }
if ( $fi_size == 'window-height' ) { $fi_custom_class = " fullscreen-image "; }
if ( $fi_parallax == 'enable' ) { $fi_parallax = " hb-parallax-wrapper"; } else { $fi_parallax = " " . $fi_parallax; }

$slider_section_offset = "";
if ( vp_metabox('misc_settings.hb_special_header_style') ){
	$slider_section_offset = "margin-top: -" . hb_options('hb_regular_header_height') . "px;";
}

?>

<!-- BEGIN #slider-section -->
<div id="slider-section" class="clearfix<?php echo $fi_custom_class; echo $fi_parallax ?>" style="<?php echo $slider_section_offset; ?><?php if ( $fi_size=='custom-height' || $fi_size == 'window-height') echo 'height: ' . $fi_custom_size . 'px;'. $slider_section_offset .' background-image: url('.$full_image[0].'); background-size:cover; background-position: center center; background-repeat: no-repeat;';?>">
	<?php 
	if ( $section_effect != 'none' ){ ?>
		<canvas id="hb-canvas-effect"></canvas>
	<?php } 

	if (is_singular('portfolio')) { 

		$header_layout = vp_metabox('portfolio_layout_settings.hb_portfolio_header_layout');
		if ( $header_layout == 'default' )
			$header_layout = hb_options('hb_portfolio_content_layout');

		if ( $header_layout == 'totalfullwidth' ) {
			get_template_part('includes/portfolio','featured-content');
		}

	} else if ( is_page() || is_singular('team') || $is_shop ) {

		if ( $rev_slider != "" && $section_type == "revolution" )
			print do_shortcode('[rev_slider ' . $rev_slider . ']');
		else if ( $layer_slider != "" && $section_type == "layer" ) 
			print do_shortcode('[layerslider id="'.$layer_slider.'"]');	
		else if ( $video_link && $section_type == "video" ) {
			?>
				<div class="fitVids"><?php echo wp_oembed_get($video_link); ?></div>
			<?php 
		}
		else if ( $thumb && $section_type == "featured_image" && $fi_size == 'original' ) { ?>
			<img class="fw-image" src="<?php echo $full_image[0]; ?>"/>
		<?php }
	}
	?>
</div>
<!-- END #slider-section -->