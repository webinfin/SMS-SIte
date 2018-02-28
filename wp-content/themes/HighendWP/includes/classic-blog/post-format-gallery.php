<?php 
$gallery_images = rwmb_meta('hb_gallery_images', array('type' => 'plupload_image', 'size' => 'full') , get_the_ID());
$api_images = "";
$api_titles = "";
$api_descriptions = "";

$crop_images = hb_options('hb_blog_enable_image_cropping');
?>
<!-- BEGIN .hentry -->
<article id="post-<?php the_ID(); ?>" <?php if ( !empty($gallery_images) ) post_class('with-featured-image slider-post-type'); else post_class('slider-post-type'); ?> itemscope itemtype="http://schema.org/BlogPosting">
<?php if ( !empty($gallery_images) ) { ?>
<!-- BEGIN .featured-image -->
<div class="featured-image">
	<div class="hb-flexslider clearfix" id="flexslider_<?php the_ID(); ?>">
		<ul class="hb-flex-slides clearfix">
			<?php foreach ( $gallery_images as $id=>$gallery_image ) {  

				if ( $crop_images ) {
					$image = hb_resize( $id, '', 900, 500, true );
				} else {
					$image = wp_get_attachment_image_src( $id, 'full', false);
					$image['url'] = $image[0];
				}

				$api_images .= "'" . addslashes ($gallery_image['url']) . "',";
				$api_titles .= "'" . addslashes ($gallery_image['title']) . "',";
				$api_descriptions .= "'" . addslashes ($gallery_image['description']) . "',";
			?>
			<li><a href="#" class="prettyphoto"><img alt="<?php echo $gallery_image['title']; ?>" src="<?php echo $image['url']; ?>" /></a></li>
			<?php } 
			$api_images = trim($api_images, ",");
			$api_titles = trim($api_titles, ",");
			$api_descriptions = trim($api_descriptions,",");
			?>
		</ul>
	</div>
	<script type="text/javascript">
        jQuery(document).ready(function() {
                jQuery("#flexslider_<?php the_ID(); ?>").flexslider({
                    selector: ".hb-flex-slides > li",
                    slideshow: true,
                    animation: "slide",              //String: Select your animation type, "fade" or "slide"
                    smoothHeight: true,            //{NEW} Boolean: Allow height of the slider to animate smoothly in horizontal mode
                    slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
                    animationSpeed: 500,            //Integer: Set the speed of animations, in milliseconds
                    pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
                    controlNav: true,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
                    directionNav:true,
                    prevText: "",           //String: Set the text for the "previous" directionNav item
                    nextText: ""               //String: Set the text for the "next" directionNav item
                });
		
				//PrettyPhoto
				jQuery("body").on("click", ".prettyphoto", function(){
					api_images = [<?php echo $api_images; ?>];
					api_titles = [<?php echo $api_titles; ?>];
					api_descriptions = [<?php echo $api_descriptions; ?>]
					jQuery.prettyPhoto.open(api_images,api_titles,api_descriptions);
				});
        });
	</script>
</div>
<?php 
} ?>
<?php get_template_part('includes/classic-blog/post', 'description'); ?>
</article>
<!-- END .hentry -->