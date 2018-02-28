<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

function wptww_get_testimonial( $atts, $content = null ){
     ob_start();
     $defaults = apply_filters( 'sp_testimonials_default_args', array(
		'limit' 			=> -1,
		'design'            => 'design-1',
		'per_row' 			=> null,
		'orderby' 			=> 'post_date',
		'order' 			=> 'DESC',
		'id' 				=> 0,
		'category' 			=> 0,
		'display_client' 	=> true,
		'display_avatar' 	=> true,
		'display_job' 		=> true,
		'display_company' 	=> true,
		'image_style'       => 'circle',
		'size' 				=> 100,
		'display_quotes'	=> 'true'
	) );
     
    $args = shortcode_atts( $defaults, $atts );
	$testimonialsdesign	= wptww_designs();
	
	$design = $args['design'];
	$design = array_key_exists( trim($design)  , $testimonialsdesign ) ? $design : 'design-1';
	
	// Shortcode file
	$testimonials_design_file_path 	= WTWP_DIR . '/templates/designs/' . $design . '.php';
	$design_file 					= (file_exists($testimonials_design_file_path)) ? $testimonials_design_file_path : '';
	
	if ( isset( $args['limit'] ) ) $args['limit'] = intval( $args['limit'] );
	if ( isset( $args['size'] ) &&  ( 0 < intval( $args['size'] ) ) ) $args['size'] = intval( $args['size'] );
	if ( isset( $args['category'] ) && is_numeric( $args['category'] ) ) $args['category'] = intval( $args['category'] );
	
        foreach ( array( 'display_client','display_job','display_company', 'display_avatar', 'display_quotes' ) as $k => $v ) {
		if ( isset( $args[$v] ) && ( 'true' == $args[$v] ) ) {
			$args[$v] = true;
		} else {
			$args[$v] = false;
		}
	}	
	$query = get_testimonials($args);
	?>
     	<div class="wptww-testimonials-list wptww-clearfix <?php echo $design; ?>">
     	<?php
		if(!empty($query)){
          $count = 0;
          $class = '';
			foreach ( $query as $post ) { 
                                $count++;
				$css_class = 'wptww-quote';
				if ( ( is_numeric( $args['per_row'] ) && ( $args['per_row'] > 0 ) && ( 0 == ( $count - 1 ) % $args['per_row'] ) ) || 1 == $count ) { $css_class .= ' wptww-first'; }
				if ( ( is_numeric( $args['per_row'] ) && ( $args['per_row'] > 0 ) && ( 0 == $count % $args['per_row'] ) ) || count( $query ) == $count ) { $css_class .= ' wptww-last'; }

				// Add a CSS class if no image is available.
				if ( isset( $post->image ) && ( '' == $post->image ) ) {
					$css_class .= ' no-image';
				}
				if ( is_numeric( $args['per_row'] ) ) {
					if($args['per_row'] == 1){
						$per_row = 12;
					}else if($args['per_row'] == 2){
						$per_row = 6;
					}
					else if($args['per_row'] == 3){
						$per_row = 4;	
					}
					else if($args['per_row'] == 4){
						$per_row = 3;
					}
					 else{
                        $per_row = $args['per_row'];
                    }
					$class = 'wp-medium-'.$per_row.' wpcolumns';
				}
				
				// Include shortcode html file
				if( $design_file ) {
					include( $design_file );
					}	
				} 
			} ?>
             </div>
             <?php  
             return ob_get_clean();
	}
add_shortcode('sp_testimonials','wptww_get_testimonial');