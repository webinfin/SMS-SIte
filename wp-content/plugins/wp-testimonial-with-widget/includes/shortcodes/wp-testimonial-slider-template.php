<?php 
function wptww_get_testimonial_slider( $atts, $content = null ){
            ob_start();
     $defaults = apply_filters( 'sp_testimonials_default_args', array(
		'limit' 			=> -1,
		'design'            => 'design-1',
		'orderby' 			=> 'post_date',
		'order' 			=> 'DESC',
		'slides_column'     => 1,
		'slides_scroll'     => 1, 
		'category' 			=> 0,
		'display_client' 	=> true,
		'display_avatar' 	=> true,
		'display_job' 		=> true,
		'display_company' 	=> true,
		'image_style'       => 'circle',
		'dots'     			=> "true",
		'arrows'     		=> "true",				
		'autoplay'     		=> "true",		
		'autoplay_interval'  => 3000,				
		'speed'             => 300,
		'size' 				=> 100,
		'display_quotes'	=> 'true',
		'adaptive_height'   => 'false'
 	) );
     $unique = wtwp_get_unique();
     $args = shortcode_atts( $defaults, $atts );
	
	$testimonialsdesign	= wptww_designs();
	
	$design = $args['design'];
	$design = array_key_exists( trim($design)  , $testimonialsdesign ) ? $design : 'design-1';
	
	// Shortcode file
	$testimonials_design_file_path 	= WTWP_DIR . '/templates/designs/' . $design . '.php';
	$design_file 					= (file_exists($testimonials_design_file_path)) ? $testimonials_design_file_path : '';	
	
	wp_enqueue_script( 'wpos-slick-jquery' );
	
	if ( isset( $args['limit'] ) ) $args['limit'] = intval( $args['limit'] );
	if ( isset( $args['size'] ) &&  ( 0 < intval( $args['size'] ) ) ) $args['size'] = intval( $args['size'] );
	if ( isset( $args['slides_column'] ) ) $args['slides_column'] = intval( $args['slides_column'] );
	if ( isset( $args['slides_scroll'] ) ) $args['slides_scroll'] = intval( $args['slides_scroll'] );
	if ( isset( $args['category'] ) && is_numeric( $args['category'] ) ) $args['category'] = intval( $args['category'] );
	if ( isset( $args['dots'] ) ) $args['dots'] =  $args['dots'] ;
	if ( isset( $args['adaptive_height'] ) ) $args['adaptive_height'] =  $args['adaptive_height'] ;
	if ( isset( $args['arrows'] ) ) $args['arrows'] =  $args['arrows'] ;	
	if ( isset( $args['autoplay'] ) ) $args['autoplay'] =  $args['autoplay'] ;
	if ( isset( $args['autoplay_interval'] ) ) $args['autoplay_interval'] =  $args['autoplay_interval'] ;
	if ( isset( $args['speed'] ) ) $args['speed'] =  $args['speed'] ;
        foreach ( array( 'display_client', 'display_job','display_company', 'display_avatar', 'display_quotes' ) as $k => $v ) {
		if ( isset( $args[$v] ) && ( 'true' == $args[$v] ) ) {
			$args[$v] = true;
		} else {
			$args[$v] = false;
		}
	}	     
	$query = get_testimonials($args);
	$class = '';
	?>	
     	<div class="wptww-testimonials-slidelist-<?php echo $unique; ?> wptww-testimonials-slidelist wptww-clearfix <?php echo $design; ?>">
     	<?php
		if(!empty($query)){
          $count = 0;
			foreach ( $query as $post ) { 
                                $count++;
				$css_class = 'wptww-quote';	

				// Add a CSS class if no image is available.
				if ( isset( $post->image ) && ( '' == $post->image ) ) {
					$css_class .= ' no-image';
				}				
				// Include shortcode html file
				if( $design_file ) {
					include( $design_file );
					}	
				} 
			} ?>
             </div>
			 
	<script type="text/javascript">
		jQuery(document).ready(function(){
		jQuery('.wptww-testimonials-slidelist-<?php echo $unique; ?>').slick({
			dots: <?php echo $args['dots']?>,
			infinite: true,
			arrows: <?php echo $args['arrows']?>,
			speed: <?php echo $args['speed']?>,
			autoplay: <?php echo $args['autoplay']?>,						
			autoplaySpeed: <?php echo $args['autoplay_interval']?>,
			slidesToShow: <?php echo $args['slides_column']?>,
			slidesToScroll: <?php echo $args['slides_scroll']?>,
			adaptiveHeight: <?php echo $args['adaptive_height']?>,
			responsive: [
			{
			  breakpoint: 769,
			  settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
				dots: true
			  }
			},
			{
			  breakpoint: 641,
			  settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 481,
			  settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			  }
			}
			]
		});
	});
	</script>
             <?php  
             return ob_get_clean();
	}
add_shortcode( 'sp_testimonials_slider', 'wptww_get_testimonial_slider' );