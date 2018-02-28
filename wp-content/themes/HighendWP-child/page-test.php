<?php
/*
Template Name: Test Template
*/
global $post;
$post_slug=$post->post_name;
?>
<?php get_header(); ?>

<?php 
if ( have_posts() ) : while (have_posts()) : the_post(); 
the_content();
endwhile; endif;
?>

<?php 
/* $main_content_style = "";
if ( vp_metabox('background_settings.hb_content_background_color') )
	$main_content_style = ' style="background-color: ' . vp_metabox('background_settings.hb_content_background_color') . ';"'; 


	<!-- BEGIN #main-content -->

<section class="tools2_banner pricing_con_se" style="background-image: url(<?php echo get_home_url(); ?>/wp-content/uploads/2017/11/contact-banner.jpg?id=351) !important; background-size:cover;">
<div class="wpb_wrapper">
			<h1>Trade Show Tools</h1>
<p>Offline event lead capture. Get the most out of your next event!</p>

		</div>
</section>
*/ ?>
<style>

.options {
    padding: 0 0 10px;
    border-bottom: solid 1px #d2d2d2;
	margin: 0 15px;
}

.options h4 span {
    font-size: 11px;
    display: block;
    color: #000;
	padding: 13px 0 0;
}
.options h4 {
    border-bottom: solid 1px #c4c4c4;
    padding: 0;
    font-size: 28px;
    color: #01abc7;
	border-bottom: none;
	margin: 0;
}

.options button {
    padding: 0 10px;
    height: 44px;
    background: transparent;
    border: solid 1px #c4c4c4;
    margin: 0px;
    text-align: left;
    font-size: 15px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 400;
    opacity: 1 !important;
    color: #000;
    position: relative;
	width: 170px;
	border-radius: 3px;
	top: -13px;
}
.options button::before {
    position: absolute;
    content: "X";
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    right: 10px;
    color: #000;
}
.options .woof_container_inner_productcategories, .options button {
    display: inline-block;
}
.options .woof_container_inner_productcategories {
	width: 25%;
}

.list-grid {
	position: absolute;
	right: 19px !important;
	top: 19px;
}
.desktop-filter .woof_container_inner_productcategories h4 {
	display: none;
}
.woof_submit_search_form_container {
	display: none !important;
}

#main-wrapper .hb-woo-product:first-child {
    border-bottom: solid 1px #c4c4c4; border-top: none;
}

@media screen and (max-width: 767px){
.options button {
	top: 16px;
	float: right;
}
.options {
	display: none;
}
}

</style>


<section class="trade_container">
	
	<div class="container">
	<div class="row">
	<div class="options">
	<div class="woof_container_inner_productcategories"><h4><span>Sort By</span> Filters
	    </h4></div>
	
	<button class="button woof_reset_search_form" data-link="">Clear Filters</button>
	<div class="list-grid"><strong>View:</strong> <a class="btn btn-default viewlist display-list-view" href="<?php echo get_home_url(); ?>/<?php echo $post_slug ?>/?view=list">List</a><a class="btn btn-default btn-sm btn-trailing-ficon display-grid-view viewlist" href="<?php echo get_home_url(); ?>/<?=$post_slug ?>/?view=grid">Grid</a></div>
	</div>
	<div class="col-3">
		<div class="desktop-filter">
		<?php echo do_shortcode('[woof]'); ?>
		</div>

		<div class="mobile-filter">
		<div class="filteroption button">Filter</div>
		<div class="filterlist" style="display:none;">
			<?php echo do_shortcode('[woof]'); ?>
		</div>
		</div>
	</div>
	<?php if(isset($_GET['cat_id']) && ($_GET['cat_id']!='')){
		$tx = 'taxonomies=product_cat:'.$_GET['cat_id']; 
	}?>
	<div class="col-9">
	

	<div class="srtcode"><?php echo do_shortcode("[woof_products per_page=8 is_ajax=1 add-to-cart $tx]");?></div>
	</div>
	</div>
</section>
<!-- END #main-content -->

<?php /* endwhile; endif; */ ?>
<?php get_footer(); ?>
<script>
jQuery('.woof_reset_search_form').click(function(){
	window.location.href="<?php echo get_home_url(); ?>/trade-show-tools-filter";	
});
</script>