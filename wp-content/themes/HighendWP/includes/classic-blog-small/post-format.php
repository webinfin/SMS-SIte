<!-- BEGIN .hentry -->
<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> itemscope itemtype="http://schema.org/BlogPosting">
<?php // get featured image
	$thumb = get_post_thumbnail_id(); 
	
	if ( $thumb ) { 
		
		if ( hb_options('hb_blog_enable_image_cropping') ) {
			$image = hb_resize( $thumb, '', 900, 500, true );
		} else {
			$image = wp_get_attachment_image_src( $thumb, 'full', false);
			$image['url'] = $image[0];
		}
		
		if ( $image ) { 
	?>	
		<div class="featured-image">
			<a href="<?php the_permalink(); ?>">
				<img src="<?php echo $image['url']; ?>" alt="<?php the_title(); ?>" />
				<div class="featured-overlay"></div>
				<div class="item-overlay-text" style="opacity: 0;">
					<div class="item-overlay-text-wrap">
						<span class="plus-sign"></span>
					</div>
				</div>
			</a>
		</div>
	<?php } 
	}
	?>
<?php get_template_part('includes/classic-blog-small/post', 'description'); ?>
</article>
<!-- END .hentry -->