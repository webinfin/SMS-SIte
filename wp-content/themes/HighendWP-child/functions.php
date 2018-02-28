<?php

function highend_child_theme_enqueue_styles() {
  $parent_style = 'highend-parent-style';
  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' ); 
  wp_enqueue_style( 'highend-child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ),  wp_get_theme()->get('Version') ); 
}

add_action( 'wp_enqueue_scripts', 'highend_child_theme_enqueue_styles' );


add_action( 'init', 'yourtheme_woocommerce_image_dimensions', 1 );

/**
 * Define image sizes
 */
function yourtheme_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> 'auto',	// px
		'height'	=> 'auto',	// px
		'crop'		=> 0
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
}
function allow_onclick_content() {
  global $allowedposttags, $allowedtags;
  $newattribute = "onclick";

  $allowedposttags["a"][$newattribute] = true;
  $allowedtags["a"][$newattribute] = true; //unnecessary?
}
add_action( 'init', 'allow_onclick_content' );

// The code for displaying WooCommerce Product Custom Fields
add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields' ); 

// Following code Saves  WooCommerce Product Custom Fields
add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save' );

function woocommerce_product_custom_fields () {
 global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => '_model_text_field',
            'placeholder' => 'Model Number',
            'label' => __('Model', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );   
    echo '</div>';
}

function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_product_text_field = $_POST['_model_text_field'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_model_text_field', esc_attr($woocommerce_custom_product_text_field));

}

/* Create a Shortcode For Categories */
function showcat_shortcode( $output ) {
	
global $post;
  $taxonomy     = 'product_cat';
  $orderby      = 'menu_order';  
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no  
  $title        = '';  
  $empty        = 0;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
 $all_categories = get_categories( $args );
 ?>

 <div class="row">
 <?php
 
 foreach ($all_categories as $cat) {
	//echo "<pre>"; print_r($cat);

    if($cat->category_parent == 0) {
		$category_id = $cat->slug; 
		if($category_id <> 'uncategorized'){ ?>
		
		<div class="col-6">
		<div class="trade_show_cate">
		<div class="trad_cat_banr"><?php $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_url( $thumbnail_id);
		if($image!='')
		{
			?>
			<img src="<?php echo $image; ?>" />
			<?php
		}
		else{ ?>
		<img class="alignnone size-full wp-image-749" src="<?php echo get_home_url(); ?>/wp-content/uploads/2018/01/kiosk2.png" alt="" width="324" height="286" />
		<?php } ?></div>
<div class="trad_cat_logo">
<img class="alignnone  wp-image-582" src="<?php echo get_home_url(); ?>/wp-content/uploads/2018/01/logo_sam-1.png" alt="" width="246" height="26" />
<h1><a href="<?php echo get_home_url();?>/trade-show-tools-filter/?cat_id=<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></a></h1>
</div>
</div>
</div>
<?php }
     
    }       
}
?>
</div>

<?php
}
add_shortcode( 'show_categories', 'showcat_shortcode' );



/** Feature Items Slider***/


function feature_shortcode( $output ) {
	?>
	<link rel='stylesheet prefetch' href='//cdn.jsdelivr.net/jquery.slick/1.5.0/slick.css'>
<link rel='stylesheet prefetch' href='//cdn.jsdelivr.net/jquery.slick/1.5.0/slick-theme.css'>
	<?php
	
$args = array( 'post_type' => 'product', 'posts_per_page' => -1, 'product_cat' => 'featured-items', 'orderby' => 'menu-order' );
        $loop = new WP_Query( $args );
        
 ?>


 <div class="slick-track">
 <?php 
 while ( $loop->have_posts() ) : $loop->the_post(); global $product;
	//echo "<pre>"; print_r($cat);

   ?>
		
		
<div class="slick-image-slide">
	<div class="slide-wrap wp-medium-12 wpcolumns">
		<div class="slider-content-left wp-medium-6 wpcolumns">
			<h2 class="slide-title"><?php the_title(); ?></h2>
			<?php //if($show_content) { ?>
			<div class="slick-slider-short-content">
				<?php //the_content(); ?>
								<div class="prod_info">
<?php if(get_field('product_special_description')!=''){ 
the_field('product_special_description');

 } else{?>

<p>Exclusive Offer</p>
<h2>KIOSK APPS</h2>
<h3>Sale 60% Off</h3>
<div>
<h1>Starting at</h1><p>$50.99</p>
</div>
<?php } ?>
<p><a href="/trade-show-tools/" style="line-height: 30px; border-width: 0px; margin: 25px 0px 0px; padding: 10px; font-weight: 600; font-size: 25px;" tabindex="-1">Shop Now</a></p>
</div>
						
			</div>
			<?php //} ?>
			
		</div>
		<div class="slider-content-right wp-medium-6 wpcolumns">			<?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'full'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"  />'; ?>			</div>
		</div>
	</div>

 
<?php endwhile; ?>
</div>
<script src='<?php echo get_template_directory_uri(); ?>-child/js/slick.min.js'></script>
<script>
jQuery(".slick-track").slick({
        dots: true,
       
        slidesToShow: 1,
        
      });
</script>

<?php
}
add_shortcode( 'feature_slider', 'feature_shortcode' );

/** Feature Items Slider***/

function remove_editor_menu() {
remove_action('admin_menu', '_add_themes_utility_last', 101);
}
add_action('_admin_menu', 'remove_editor_menu', 1);

function my_login_logo_one() { 
?> 
<style>
#login h1 a {
 height: 115px !important;
}
</style>
<?php
} add_action( 'login_enqueue_scripts', 'my_login_logo_one' );
