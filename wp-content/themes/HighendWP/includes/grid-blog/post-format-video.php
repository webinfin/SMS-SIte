<?php 
global $blog_grid_column_class;
if ( isset($_POST['col_count']) ) {
	if ( $_POST['col_count'] != "-1" ) $blog_grid_column_class = $_POST['col_count'];
}
?>
<!-- BEGIN .hentry -->
<article id="post-<?php the_ID(); ?>" <?php if ( vp_metabox('post_format_settings.hb_video_post_format.0.hb_video_format_link ') ) post_class('audio-post-format with-featured-image ' . $blog_grid_column_class ); else post_class('image-post-type ' . $blog_grid_column_class ); ?> itemscope itemtype="http://schema.org/BlogPosting">
<?php if ( vp_metabox('post_format_settings.hb_video_post_format.0.hb_video_format_link') ) { ?>
	<!-- BEGIN .featured-image -->
	<div class="featured-image fitVidsAjax">
		<?php echo wp_oembed_get(vp_metabox('post_format_settings.hb_video_post_format.0.hb_video_format_link')); ?>
	</div>
	<!-- END .featured-image -->
	
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#post-<?php the_ID(); ?> .fitVidsAjax').fitVids();
	});
	</script>

<?php } ?>
<?php get_template_part('includes/grid-blog/post', 'description'); ?>

</article>
<!-- END .hentry -->