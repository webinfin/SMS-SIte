<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>
<?php get_header(); ?>

	<?php

		if ( hb_module_enabled('hb_module_not_found_page') ) {
			$title = hb_options('hb_404_title');
			$subtitle = hb_options('hb_404_subtitle');
			$button_caption = hb_options('hb_404_button_caption');
			$icon = hb_options('hb_404_icon');
		} else {
			$title = __("File not Found","hbthemes");
			$subtitle = __("Sorry, but we couldn't find the content you were looking for.", "hbthemes");
			$button_caption = __("Back to our Home", "hbthemes");
			$icon = "hb-moon-construction";
		}
	?>


	<!-- BEGIN #main-content -->
	<div id="main-content">
	<div class="container">

		<div class="not-found-box aligncenter">

			<div class="not-found-box-inner">
				<h1 class="extra-large"><?php echo $title; ?></h1>
				<h4 class="additional-desc"><?php echo $subtitle; ?></h4>
				<div class="hb-separator-s-1"></div>
				<a href="<?php echo home_url(); ?>" class="hb-button"><?php echo $button_caption; ?></a>
			</div>

			<i class="<?php echo $icon; ?>"></i>
		</div>

	</div>
	<!-- END .container -->

	</div>
	<!-- END #main-content -->

<?php get_footer(); ?>