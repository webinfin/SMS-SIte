<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>
<?php

/* -----------------------------------------------
THEME SHORTCODES
-------------------------------------------------- */

/* BLOG MINIMAL 
-------------------------------------------------- */
if ( !function_exists('hb_blog_minimal_shortcode')) {
	function hb_blog_minimal_shortcode($params = array()) {
		extract(shortcode_atts(array(
			'count' => '10',
			'order' => 'date',
			'orderby' => 'DESC',
			'exclude' => '',
			'category' => '',
			'offset' => '0',
			'show_featured_image' => 'true',
			'show_date' => 'true',
			'show_excerpt' => 'true',
			'excerpt_length' => '15',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$exclude = str_replace(' ','', $exclude);
		$exclude = explode(',', $exclude);

		$args = array(
			'posts_per_page' => $count,
			'post_type' => 'post',
			'post_status' => 'publish',
			'order' => $order,
			'orderby' => $orderby,
			'offset' => $offset,
			'cat' => $category,
			'post__not_in' => $exclude
		);

		$blog_posts = new WP_Query( $args );

		$output = '<div class="shortcode-wrapper shortcode-blog-minimal' . $class . $animation . '"' . $animation_delay . '>';

		if ( $blog_posts->have_posts() ) : while ( $blog_posts->have_posts() ) : $blog_posts->the_post();

			$format = get_post_format( get_the_ID() );
			$icon_to_use = 'hb-moon-file-3';

			if ($format == 'video'){
				$icon_to_use = 'hb-moon-play-2';
			} else if ($format == 'status' || $format == 'standard'){
				$icon_to_use = 'hb-moon-pencil';
			} else if ($format == 'gallery' || $format == 'image'){
				$icon_to_use = 'hb-moon-image-3';
			} else if ($format == 'audio'){
				$icon_to_use = 'hb-moon-music-2';
			} else if ($format == 'quote'){
				$icon_to_use = 'hb-moon-quotes-right';
			} else if ($format == 'link'){
				$icon_to_use = 'hb-moon-link-5';
			}


			$thumb = get_post_thumbnail_id(); 
			$full_thumb = wp_get_attachment_image_src( get_post_thumbnail_id ( get_the_ID() ), 'original') ;
				
			$output .= '<article class="search-entry clearfix">';

			if ( $show_featured_image == "true" ) {
				if ( $thumb ) {
					$image = hb_resize( $thumb, '', 80, 80, true );
					$output .= '<a href="'.get_permalink().'" title="'.get_the_title().'" class="search-thumb"><img src="'.$image['url'].'" alt="'. get_the_title() .'" /></a>';
				} else {
					$output .= '<a href="'.get_permalink().'" title="'.get_the_title().'" class="search-thumb"><i class="'. $icon_to_use .'"></i></a>';
				}
			}
					
			$echo_title = get_the_title();
			if ( $echo_title == "" ) $echo_title = __('No Title' , 'hbthemes' );
			$output .= '<h4 class="semi-bold"><a href="'.get_permalink().'" title="'.$echo_title.'">'.$echo_title.'</a></h4>';

			if ( $show_date == "true" ) {
				$output .= '<div class="minor-meta">'. get_the_time('M j, Y') .'</div>';
			}

			if ( $show_excerpt == "true" ) {			
				$output .= '<div class="excerpt-wrap">';
					$output .= wp_trim_words( get_the_content(), $excerpt_length ? $excerpt_length : 9999999 );
				$output .= '</div>';
			}
					
			$output .= '</article>';

		endwhile; endif;
		wp_reset_query();

		$output .= '</div>';

		return $output;
	}
}
add_shortcode('hb_blog_minimal', 'hb_blog_minimal_shortcode');


/* FLIP BOXES
-------------------------------------------------- */
if ( !function_exists('hb_flip_boxes_shortcode') ) {
	function hb_flip_boxes_shortcode($params = array()) {
		extract( shortcode_atts( array(
			'flip_direction'              => 'horizontal',
			'front_background_type'		  => 'color',
			'front_background_image'      => '',
			'front_background_color'      => '#BF5D52',
			'back_background_type'        => 'color',
			'back_background_image'		  => '',
			'back_background_color'       => '#5B6C7D',
			'min_height'                  => '350',
			'icon_type'                   => 'icon',
			"image"                       => '',
			"icon"                        => 'hb-moon-brain',
			'icon_size'                   => '48',
			'icon_color'                  => 'inherit',
			'front_title'                 => '',
			'front_title_size'            => 'inherit',
			'front_title_color'           => 'inherit',
			'back_title'                  => '',
			'back_title_size'             => 'inherit',
			'back_title_color'            => 'inherit',
			'front_desc'                  => '',
			'front_desc_size'             => 'inherit',
			'front_desc_color'            => 'inherit',
			'back_desc'                   => '',
			'back_desc_size'              => 'inherit',
			'back_desc_color'             => 'inherit',
			'button_url'                  => '',
			'button_text'                 => '',
			'button_color'				  => '',
			'button_target'				  => '',
			'animation' 				  => '',
			'animation_delay' 			  => '',
			'class'						  => ''
		), $params ) );

		$output = $front = $flip = '';

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$icon_size = is_numeric($icon_size) ? $icon_size . 'px' : $icon_size; 
		$front_title_size = is_numeric($front_title_size) ? $front_title_size . 'px' : $front_title_size; 
		$front_desc_size = is_numeric($front_desc_size) ? $front_desc_size . 'px' : $front_desc_size; 		
		$back_desc_size = is_numeric($back_desc_size) ? $back_desc_size . 'px' : $back_desc_size; 
		$back_title_size = is_numeric($back_title_size) ? $back_title_size . 'px' : $back_title_size; 
		$min_height = is_numeric($min_height) ? $min_height . 'px' : $min_height; 
		$button_color = $button_color ? " hb-" . $button_color : "";

		if ( $button_target == "yes" ){
			$button_target = "_blank";
		} else {
			$button_target = "_self";
		}

		$front_background_color = $front_background_type == "color" ? ('background-color: '.$front_background_color.';') : '';
		$front_background_image = $front_background_image ? wp_get_attachment_image_src($front_background_image, "full") : "";
		$front_background_image = $front_background_type == "image" ? ('background-image: url(\''. $front_background_image[0].'\');') : '';

		$image = $image ? wp_get_attachment_image_src($image, "full") : "";
		$image = $image ? $image[0] : "";
		
		/* Flipbox Front */
		$front .= '<div class="hb-flipbox-front " style="' . $front_background_color . $front_background_image . '" >';
		$front .= ' <div class="hb-flipbox-content">';
		if($icon_type == 'icon'){
		      $front .= ' <div class="front-icon"><i class="'.$icon.'" style="font-size:'.$icon_size.'; '.($icon_color ? ('color:'.$icon_color.';') : '').'"></i></div>';
		}else if ( $icon_type=="image"){
		      $front .= '<div class="front-icon"><img src="'.$image.'" alt="'.$front_title.'" /></div>';
		}
		$front .= '       <h4 class="modern" style="font-size:'.$front_title_size.'; '.($front_title_color ? ('color:'.$front_title_color.';') : '').'">'.$front_title.'</h4>';
		$front .= '       <div class="front-desc" style="font-size:'.$front_desc_size.'; '.($front_desc_color ? ('color:'.$front_desc_color.';') : '').'">'.$front_desc.'</div>';
		$front .= ' </div>';
		$front .= '</div>';

		$back_background_color = $back_background_type == "color" ? ('background-color: '.$back_background_color.';') : '';
		$back_background_image = $back_background_image ? wp_get_attachment_image_src($back_background_image, "full") : "";
		$back_background_image = $back_background_type == "image" ? ('background-image: url(\''. $back_background_image[0].'\');') : '';

		/* Flipbox Back */
		$flip .= '<div class="hb-flipbox-back" style="'.$back_background_image . $back_background_color .'">';
		$flip .= '  <div class="hb-flipbox-content">';
		$flip .= '        <h4 class="modern" style="font-size:'.$back_title_size.'; '.($back_title_color ? ('color:'.$back_title_color.';') : '').'">'.$back_title.'</h4>';
		$flip .= '        <div class="back-desc" style="font-size:'.$back_desc_size.'; '.($back_desc_color ? ('color:'.$back_desc_color.';') : '').' ">'.$back_desc.'</div>';

		$flip .= !empty( $button_url ) ? '<a href="' . $button_url . '" class="hb-button no-three-d' . $button_color . '" target="'. $button_target .'">' . $button_text . '</a>' : "";

		$flip .= '  </div>';
		$flip .= '</div>';

		$output = '<div class="shortcode-wrapper shortcode-blog-boxes' . $class . $animation . '"' . $animation_delay . '>';
		$output .= '<div class="hb-flipbox-container flip-'.$flip_direction.'" style="height:1px; height:'.$min_height.'">';
		$output .= '      <div class="hb-flipbox-flipper">';
		$output .=              $front;
		$output .=              $flip;
		$output .= '            <div class="clearboth"></div>';
		$output .= '      </div>';
		$output .= '</div>';
		$output .= '</div>';


		return $output;
	}
}
add_shortcode('hb_flip_box', 'hb_flip_boxes_shortcode');


/* BLOG BOXES
-------------------------------------------------- */
if ( !function_exists('hb_blog_boxes_shortcode') ) {
	function hb_blog_boxes_shortcode($params = array()) {
		extract(shortcode_atts(array(
			'count' => '10',
			'columns' => '3',
			'order' => 'date',
			'orderby' => 'DESC',
			'exclude' => '',
			'category' => '',
			'offset' => '0',
			'crop_image' => 'false',
			'crop_width' => '600',
			'crop_height' => '400',
			'show_featured_image' => 'false',
			'show_categories' => 'false',
			'show_excerpt' => 'false',
			'show_read_more' => 'false',
			'show_date' => 'false',
			'excerpt_length' => '15',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$exclude = str_replace(' ','', $exclude);
		$exclude = explode(',', $exclude);

		$args = array(
			'posts_per_page' => $count,
			'post_type' => 'post',
			'post_status' => 'publish',
			'order' => $order,
			'orderby' => $orderby,
			'offset' => $offset,
			'cat' => $category,
			'post__not_in' => $exclude
		);

		$blog_posts = new WP_Query( $args );

		$output = '<div class="shortcode-wrapper shortcode-blog-boxes' . $class . $animation . '"' . $animation_delay . '>';
		
		if ( $blog_posts->have_posts() ) :

			$column_class = "col-" . (12/$columns);
			$output .= '<div class="row">';
			$item_count = 0;
			$total_item_count = $blog_posts->post_count;

			while ( $blog_posts->have_posts() ) : $blog_posts->the_post();
			$item_count++;
			$output .= '<div class="' . $column_class . '">';
				$output .= '<div class="hb-blog-box">';

				// Featured Image
				if ( $show_featured_image == "true" ) {
					$thumb = get_post_thumbnail_id();
					if ( $thumb ) {
						if ( $crop_image =="true" && $crop_width && $crop_height ) {
							$image = hb_resize($thumb, "", $crop_width, $crop_height, true);
						} else {
							$image_tmp = wp_get_attachment_image_src($thumb, "full" );
							$image['url'] = $image_tmp[0];
							$image['width'] = $image_tmp[1];
							$image['height'] = $image_tmp[2];
						}

						$output .= '<div class="hb-blog-box-header">';
						$output .= '<a href="' . get_permalink() . '">';
						$output .= '<img width="' . $image['width'] . '" height="' . $image['height'] . '" src="' . $image['url'] . '" alt="' . get_post_meta($thumb, '_wp_attachment_image_alt', true ) . '">';
						$output .= '</a>';
						$output .= '</div>';
					}
				}

				// START POST CONTENT
				$output .= '<div class="hb-blog-box-content">';
					
					// CONTENT CATEGORIES
					if ( $show_categories == "true" ) {
						$post_categories = wp_get_post_categories( get_the_ID() );
						$cats = array();
							
						if ( !empty ($post_categories) ) {
							$output .= '<div class="hb-blog-box-categories">';
							$cat_links = array();
							foreach($post_categories as $c){
								$cat = get_category( $c );
								$cat_links[] = '<a href="' . get_category_link ( $cat->term_id ) . '">' . $cat->name . '</a>';
							}
							$output .= implode(", ", $cat_links);
							$output .= '</div>';
						}
					}

					// CONTENT TITLE
					$output .= '<div class="hb-blog-box-title"><h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3></div>';

					// CONTENT EXCERPT
					if ( $show_excerpt == "true" ) {
						$output .= '<div class="hb-blog-box-excerpt"><p>';

						if ( has_excerpt() ) 
							$output .= get_the_excerpt();
						else 
							$output .= wp_trim_words( get_the_content(), $excerpt_length );

						$output .= '</p></div>';
					}

				$output .= '</div>';
				// END POST CONTENT

				// CONTENT FOOTER
				$content_footer_class = "";
				if ( $show_read_more != "true" ) $content_footer_class .= " without-more-button";
				if ( $show_date != "true" ) $content_footer_class .= " without-date";

				if ( $show_read_more == "true" || $show_date == "true" ) {
					$output .= '<div class="hb-blog-box-footer clearfix' . $content_footer_class . '">';
					$output .= '<span class="hb-blog-box-date">' . get_the_time( get_option( 'date_format') ) . '</span>';
					$output .= '<span class="hb-blog-box-read-more"><a href="' . get_permalink() . '" class="hb-special-read-more">' . __('Read More' , 'hbthemes') . '<span><i class="hb-moon-arrow-right-5"></i></span></a></span>';
					$output .= '</div>';
				}

				$output .= '</div>';
			$output .= '</div>';

			if ( $item_count % $columns == 0  && $item_count < $total_item_count ) {
				$output .= '</div><div class="row">';
			}

			endwhile;		

			$output .= '</div>';	

		endif;

		wp_reset_query();
		
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('hb_blog_boxes', 'hb_blog_boxes_shortcode');

/* BLOG IMAGES CAROUSEL
-------------------------------------------------- */
if ( !function_exists('hb_owl_blog_carousel_shortcode') ) {
	function hb_owl_blog_carousel_shortcode($params = array()) {

		extract(shortcode_atts(array(
			'count' => '10',
			'columns' => '3',
			'order' => 'date',
			'orderby' => 'DESC',
			'exclude' => '',
			'category' => '',
			'offset' => '0',
			'with_padding' => 'false',
			'height' => '350',
			'show_categories' => 'true',
			'show_excerpt' => 'false',
			'show_read_more' => 'true',
			'show_date' => 'false',
			'excerpt_length' => '15',
			'data_sliderspeed' => '650',
			'data_autoplay' => '5000',
			'data_stoponhover' => 'false',
			'data_lazyload' =>'false',
			'data_pagination' => 'false',
			'data_navigation' => 'true',
			'data_rewindnav' => 'true',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$exclude = str_replace(' ','', $exclude);
		$exclude = explode(',', $exclude);

		$args = array(
			'posts_per_page' => $count,
			'post_type' => 'post',
			'post_status' => 'publish',
			'order' => $order,
			'orderby' => $orderby,
			'offset' => $offset,
			'cat' => $category,
			'post__not_in' => $exclude
		);

		$blog_posts = new WP_Query( $args );
		
		if ( !$data_autoplay || $data_autoplay == "0" || $data_autoplay == 0 )
			$data_autoplay = "false";
		
		if ( $with_padding == "true" ) $with_padding = " hb-with-padding";
		else $with_padding = "";

		$output = '<div class="shortcode-wrapper shortcode-blog-images-carousel' . $class . $animation . '"' . $animation_delay . '>';

		if ( $blog_posts->have_posts() ) :
			$output .= '<div class="hb-owl-slider-wrapper">';
			$output .= '<div class="hb-owl-slider hb-post-carousel' . $with_padding . '" 
							data-items="' . $columns . '" 
							data-slidespeed="' . $data_sliderspeed . '" 
							data-autoplay="' . $data_autoplay . '" 
							data-stoponhover="' . $data_stoponhover . '"
							data-rewindnav="' . $data_rewindnav . '"
							data-lazyload="' . $data_lazyload . '"
							data-pagination="' . $data_pagination . '"
							data-navigation="' . $data_navigation . '"
							data-theme="highend-owl">';

			while ( $blog_posts->have_posts() ) : $blog_posts->the_post();
				$post_id = get_the_ID();

				$thumb = get_post_thumbnail_id(); 
				$full_image = wp_get_attachment_image_src($thumb,'full');
				$full_image = 'background-image: url(\'' . $full_image[0] . '\');';
				
				$output .= '<div class="hb-owl-item" style="height:' . $height . 'px;' . $full_image . '">';
				$output .= '<a href="' . get_permalink() . '"></a>';
				
				$output .= '<div class="hb-post-info">';

				if ( $show_categories == "true" ) {
					$cats = array();
					foreach(wp_get_post_categories($post_id) as $c)
					{
						$cat = get_category($c);
						array_push($cats,$cat->name);
					}

					if(sizeOf($cats)>0)
					{
						$post_categories = implode(', ',$cats);
					} else {
						$post_categories = "";
					}
					$output .= '<div class="hb-post-categories">' . $post_categories . '</div>';
				}
				
				$output .= '<a href="' . get_permalink() . '" class="hb-post-title">' . get_the_title() . '</a>';
				
				if ( $show_date == "true" )
					$output .= '<div class="hb-owl-date">' . get_the_time ( get_option('date_format') ) . '</div>';
				
				if ( $show_excerpt == "true" ) {
					if ( has_excerpt() )
						$output .= '<p class="hb-owl-excerpt">' . get_the_excerpt() . '</p>';
					else
						$output .= '<p class="hb-owl-excerpt">' . wp_trim_words( get_the_content(), $excerpt_length ) . '</p>';
				}
				
				if ( $show_read_more == "true" )
					$output .= '<a href="' . get_permalink() . '" class="hb-owl-read-more">' . __('Read More','hbthemes') . '<span><i class="hb-moon-arrow-right-5"></i></span></a>';
				
				$output .= '</div>';
				
				$output .= '</div>';

			endwhile; 

			$output .= '</div>';
			$output .= '</div>';
		endif;

		wp_reset_query();

		$output .= '</div>';

		return $output;
	}
}
add_shortcode('blog_images_carousel', 'hb_owl_blog_carousel_shortcode');

/* FULLWIDTH PORTFOLIO
-------------------------------------------------- */
if ( !function_exists('hb_portfolio_fullwidth_shortcode') ) {
	function hb_portfolio_fullwidth_shortcode($params = array()) {

		extract(shortcode_atts(array(
			'count' => '8',
			'columns' => '2',
			'ratio' => 'ratio1',
			'orientation' => 'landscape',
			'category' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'margin_top' => '',
			'margin_bottom' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}


		if ( is_numeric($margin_bottom)) $margin_bottom = $margin_bottom . 'px';
		if ( is_numeric($margin_top)) $margin_top = $margin_top .'px';
		$style = "";
		if ( $margin_bottom || $margin_top ) {
			$style = ' style="';
			if ( $margin_bottom ) $style .= 'margin-bottom:' . $margin_bottom . ';';
			if ( $margin_top ) $style .= 'margin-top:' . $margin_top . ';';
			$style .= '"';
		}

		$output = "";
		$image_dimensions = get_image_dimensions ( $orientation, $ratio, 1000 );


		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'portfolio',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $count,
					'tax_query' => array(
							array(
								'taxonomy' => 'portfolio_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'portfolio',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $count,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="shortcode-wrapper shortcode-portfolio-fullwidth gallery-carousel-wrapper-2' . $class . $animation . '"' . $animation_delay . $style . '>';
		$output .= '<div class="fw-section without-border light-text">';
		$output .= '<div class="content-total-fw">';
		$output .= '<div class="hb-fw-elements columns-' . $columns . '">';

		while ( $queried_items->have_posts() ) : $queried_items->the_post();
			$perma = get_the_permalink();
			$custom_url = vp_metabox('portfolio_settings.hb_portfolio_custom_url');
			$custom_color = vp_metabox('portfolio_settings.hb_portfolio_custom_bg_color');

			if ($custom_color){
				$custom_color = ' style="background: ' . hb_color($custom_color, 0.85) . ';"';
			} else {
				$custom_color = "";
			}
						
			if ($custom_url){
				$perma = $custom_url;
			}
			$thumb = get_post_thumbnail_id(); 
			$image = hb_resize( $thumb, '', $image_dimensions['width'], $image_dimensions['height'], true );

			$output .= '<div class="hb-fw-element">';
			$output .= '<a href="' . $perma . '">';

			if ( $image )
				$output .= '<img src="' . $image['url'] . '" width="'. $image['width'] .'" height="'. $image['height'] .'" alt="' . get_post_meta($thumb, '_wp_attachment_image_alt', true ) . '"/>';
			
			$output .= '<div class="item-overlay-text"'. $custom_color .'>';
			$output .= '<div class="item-overlay-text-wrap">';
			$output .= '<h4><span class="hb-gallery-item-name">' . get_the_title() . '</span></h4>';
			$output .= '<div class="hb-small-separator"></div>';
			$output .= '<span class="item-count-text">' . get_the_time('j M Y') . '</span>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</a>';
			$output .= '</div>';
		endwhile;

		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		endif;

		wp_reset_query();
		
		return $output;  
	}
}
add_shortcode('portfolio_fullwidth', 'hb_portfolio_fullwidth_shortcode');

/* FULLWIDTH GALLERY
-------------------------------------------------- */
if ( !function_exists('hb_gallery_fullwidth_shortcode') ) {
	function hb_gallery_fullwidth_shortcode($params = array()) {

		extract(shortcode_atts(array(
			'count' => '8',
			'columns' => '2',
			'ratio' => 'ratio1',
			'orientation' => 'landscape',
			'category' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));


		if ( $class != '' ){
			$class = ' ' . $class;
		}
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";
		$image_dimensions = get_image_dimensions ( $orientation, $ratio, 1000 );


		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'gallery',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $count,
					'tax_query' => array(
							array(
								'taxonomy' => 'gallery_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'gallery',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $count,
					'status' => 'publish',
				));
		}

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="shortcode-wrapper shortcode-portfolio-fullwidth gallery-carousel-wrapper-2' . $class . $animation . '"' . $animation_delay . '>';
		$output .= '<div class="fw-section without-border light-text">';
		$output .= '<div class="content-total-fw">';
		$output .= '<div class="hb-fw-elements columns-' . $columns . '">';

		while ( $queried_items->have_posts() ) : $queried_items->the_post();
			$thumb = get_post_thumbnail_id(); 
			$image = hb_resize( $thumb, '', $image_dimensions['width'], $image_dimensions['height'], true );
			$full_image = wp_get_attachment_image_src($thumb,'full');
			$gallery_attachments = rwmb_meta('hb_gallery_images', array('type' => 'plupload_image', 'size' => 'full') , get_the_ID());
			$unique_id = rand(1,10000);
			$custom_color = vp_metabox('gallery_settings.hb_gallery_custom_bg_color');

			$thumb_post = get_post( $thumb );
			$thumb_caption = $thumb_post->post_excerpt;

			if ($custom_color){
				$custom_color = ' style="background: ' . hb_color($custom_color, 0.85) . ';"';
			} else {
				$custom_color = "";
			}

			if ( !$image && !empty($gallery_attachments))
			{
				reset($gallery_attachments);
				$thumb = key($gallery_attachments);
				$image = hb_resize( $thumb, '', $image_dimensions['width'], $image_dimensions['height'], true );
				$full_image = wp_get_attachment_image_src($thumb,'full');
			}

			$output .= '<div class="hb-fw-element">';
			$output .= '<a href="' . $full_image[0] . '" data-title="'.$thumb_caption.'" rel="prettyPhoto[gallery_' . $unique_id . ']">';

			if ( $image )
				$output .= '<img src="' . $image['url'] . '" width="'. $image['width'] .'" height="'. $image['height'] .'" alt="' . get_post_meta($thumb, '_wp_attachment_image_alt', true ) . '"/>';
			
			$output .= '<div class="item-overlay-text"'.$custom_color.'>';
			$output .= '<div class="item-overlay-text-wrap">';
			$output .= '<h4><span class="hb-gallery-item-name">' . get_the_title() . '</span></h4>';
			$output .= '<div class="hb-small-separator"></div>';
			$output .= '<span class="item-count-text">' . get_the_time('j M Y') . '</span>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</a>';
			$output .= '</div>';

			if ( !empty ( $gallery_attachments ) ) {
				$output .= '<div class="hb-reveal-gallery">';
				foreach ( $gallery_attachments as $gal_id => $gal_att ) {
					if( $gal_id != $thumb )
						$output .= '<a href="' . $gal_att['url'] . '" data-title="' . $gal_att['description'] . '" rel="prettyPhoto[gallery_' . $unique_id . ']"></a>';
				}
				$output .= '</div>';
			}

		endwhile;

		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		endif;
		
		wp_reset_query();

		return $output;  
	}
}
add_shortcode('gallery_fullwidth', 'hb_gallery_fullwidth_shortcode');

/* MENU PRICING ITEM
-------------------------------------------------- */
if ( !function_exists('hb_menu_pricing_item_shortcode') ) {
	function hb_menu_pricing_item_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'title' => 'My Menu item',
			'text' => '',
			'price' => '$2.99',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ( $class ) $class = ' ' . $class;
		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output .= '<div class="shortcode-wrapper shortcode-menu-pricing-item' . $class . $animation . '"' . $animation_delay . '>';

		$output .= '<div class="hb-menu-pricing-item">';
		$output .=  '<div class="hb-menu-pricing-item-text">';
			$output .=  '<h4 class="hb-menu-pricing-item-title">' . $title . '</h4>';
			if ( $text) $output .= '<p>' . $text . '</p>';
		$output .= '</div>';

		$output .= '<div class="hb-menu-pricing-item-price">';
			$output .=  '<div class="hb-menu-pricing-item-price-inner"><span>' . $price . '</span></div>';
		$output .=  '</div>';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('menu_pricing_item', 'hb_menu_pricing_item_shortcode');


/* TOGGLE
-------------------------------------------------- */
if ( !function_exists('hb_toggle_group_shortcode')) {
	function hb_toggle_group_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'initial_index' => '-1',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ( $class ) $class = ' ' . $class;
		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = '<div class="hb-toggle' . $class . $animation . '" data-initialindex="' . $initial_index . '"' . $animation_delay . '>';
		$output .= do_shortcode($content);
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('toggle_group', 'hb_toggle_group_shortcode');

if ( !function_exists('hb_toggle_shortcode')) {
	function hb_toggle_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'icon' => '',
			'title' => 'Toggle',
		), $params));


		$output = '<div class="hb-accordion-single">';
		$output .= '<div class="hb-accordion-tab">';

		if ( $icon ) {
			$output .= '<i class="' . $icon . '"></i>';
		}

		if ( $title ) {
			$output .= $title . '<i class="icon-angle-right"></i>';
		}

		$output .= '</div>';
		$output .= '<div class="hb-accordion-pane">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('toggle_item', 'hb_toggle_shortcode');

/* ACCORDION
-------------------------------------------------- */
if ( !function_exists('hb_accordion_group_shortcode') ) {
	function hb_accordion_group_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'initial_index' => '-1',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ( $class ) $class = ' ' . $class;
		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = '<div class="hb-accordion' . $class . $animation . '" data-initialindex="' . $initial_index . '"' . $animation_delay . '>';
		$output .= do_shortcode($content);
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('accordion_group', 'hb_accordion_group_shortcode');

if ( !function_exists('hb_accordion_shortcode') ) {
	function hb_accordion_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'icon' => '',
			'title' => 'Accordion',
		), $params));


		$output = '<div class="hb-accordion-single">';
		$output .= '<div class="hb-accordion-tab">';

		if ( $icon ) {
			$output .= '<i class="' . $icon . '"></i>';
		}

		if ( $title ) {
			$output .= $title . '<i class="icon-angle-right"></i>';
		}

		$output .= '</div>';
		$output .= '<div class="hb-accordion-pane">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('accordion_item', 'hb_accordion_shortcode');

/* ICON
-------------------------------------------------- */
if ( !function_exists('hb_icon_shortcode')) {
	function hb_icon_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'name' => 'hb-moon-brain',
			'size' => '',
			'color' => '',
			'float' => '',
			'jump' => 'no',
			'link' => '',
			'new_tab' => 'no',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($new_tab == 'yes'){
			$new_tab = ' target="_blank"';
		} else {
			$new_tab = ' target="_self"';
		}

		if ( $class ) $class = ' ' . $class;
		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($float == 'none'){
			$float = ' hb-icon-float-none aligncenter';
		} else if ( $float == 'left' ){
			$float = ' hb-icon-float-left';
		} else if ( $float == 'right' ){
			$float = ' hb-icon-float-right';
		} else {
			$float = ' hb-icon-float-left';
		}

		if ($jump == 'yes'){
			$jump = ' hb-jumping';
		} else {
			$jump = '';
		}

		if ($size == 'large'){
			$size = ' hb-icon-large';
		} else if ($size == 'small'){
			$size = ' hb-icon-small';
		} else {
			$size = ' hb-icon-medium';
		}

		if ($color != ''){
			if ($color[0] == '#'){
				$color = ' style="color:'.$color.'"';
			} else {
				$color = ' style="color:#'.$color.'"';
			}
		}

		$output = "";
		if ($link != ''){
			$output .= '<a href="'.$link.'"'.$new_tab.'><i class="'.$name.$float.$jump.$size.$class.' hb-icon"'.$color.'></i></a>';
		} else {
			$output .= '<i class="'.$name.$float.$jump.$size.$class.' hb-icon"'.$color.'></i>';
		}

		return $output;
	}
}
add_shortcode('icon', 'hb_icon_shortcode');

/* FAQ
-------------------------------------------------- */
if ( !function_exists('hb_faq_shortcode')) {
	function hb_faq_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'category' => '',
			'filter' => 'no',
			'animation' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( $class ) $class = ' ' . $class;

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'faq',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => -1,
					'tax_query' => array(
							array(
								'taxonomy' => 'faq_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'faq',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => -1,
					'status' => 'publish',
				));
		}

		$output = "";

		$output .= '<div class="shortcode-wrapper shortcode-faq-module clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="faq-module-wrapper clearfix">';

		if ( $filter == 'yes' ) {
			$faq_categories = array();
			if ( $queried_items->have_posts() ) : while ( $queried_items->have_posts() ) : $queried_items->the_post(); 
				$faq_post_categories = wp_get_post_terms( get_the_ID(), 'faq_categories', array("fields" => "all"));
				if ( !empty ( $faq_post_categories) )
				{
					foreach($faq_post_categories as $faq_category)
					{
						$faq_categories[$faq_category->slug] = $faq_category->name;
					}
				}
			endwhile; endif;
			array_unique($faq_categories);

			$output .= '<div class="filter-tabs-wrapper clearfix">';
			$output .= '<ul class="filter-tabs faq-filter clearfix">';
			$output .= '<li class="selected"><a href="#" data-filter="*" data-filter-name="' . __('All','hbthemes'). '">' . __('All' , 'hbthemes') . ' <span class="hb-filter-count">(0)</span></a></li>';
			if ( !empty($faq_categories) ) { 
				foreach ( $faq_categories as $slug=>$name ) { 
					$output .= '<li><a href="#" data-filter="' . $slug . '" data-filter-name="' . $name . '">' . $name . ' <span class="hb-filter-count">(0)</span></a></li>';
				}
			}
			$output .= '</ul>';
			$output .= '</div>';
			$output .= '<div class="spacer" style="height:20px;"></div>';
		}

		if ( $queried_items->have_posts() ) : while ( $queried_items->have_posts() ) : $queried_items->the_post();
			$unique_id = rand(1,10000);
			$faq_cats = wp_get_post_terms(get_the_ID() , 'faq_categories' , array("fields"=>"slugs"));
			$faq_cats_string = implode ( $faq_cats , " ");
			$faq_content = get_the_content();
			$faq_content = apply_filters( 'the_content', $faq_content );

			if ( $faq_cats_string ) $faq_cats_string = ' ' . $faq_cats_string;

			$output .= '<div id="hb-toggle-' . $unique_id . '" class="hb-toggle' . $faq_cats_string . '">';
			$output .= '<div class="hb-accordion-single">';
			$output .= '<div class="hb-accordion-tab"><i class="hb-moon-plus-circle"></i> ' . get_the_title() . '<i class="icon-angle-right"></i></div>';
			$output .= '<div class="hb-accordion-pane" style="display: none;">';
			$output .= do_shortcode($faq_content);
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

		endwhile; endif;
		wp_reset_query();

		$output .= '</div>';
		$output .= '</div>';


		return $output;
	}
}
add_shortcode('faq', 'hb_faq_shortcode');

/* ROW
-------------------------------------------------- */
if ( !function_exists('hb_row_shortcode') ) {
	function hb_row_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'margin_top' => '',
			'margin_bottom' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ( $class ) $class = ' ' . $class;
		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}


		if ( is_numeric($margin_bottom)) $margin_bottom = $margin_bottom . 'px';
		if ( is_numeric($margin_top)) $margin_top = $margin_top .'px';
		$style = "";
		if ( $margin_bottom || $margin_top ) {
			$style = ' style="';
			if ( $margin_bottom ) $style .= 'margin-bottom:' . $margin_bottom . ';';
			if ( $margin_top ) $style .= 'margin-top:' . $margin_top . ';';
			$style .= '"';
		}
		$output = '<div class="row clearfix' . $class . $animation . '"' . $style . $animation_delay . '>' . do_shortcode ( $content ) . '</div>';
		return $output;
	}
}
add_shortcode('row', 'hb_row_shortcode');

/* ALIGNCENTER
-------------------------------------------------- */
if ( !function_exists('hb_aligncenter_shortcode')) {
	function hb_aligncenter_shortcode($params = array(), $content = null) {	
		return '<div class="hb-aligncenter">' . do_shortcode($content) . '</div>';
	}
}
add_shortcode('align_center', 'hb_aligncenter_shortcode');

/* COLUMN
-------------------------------------------------- */
if ( !function_exists('hb_column_shortcode')) {
	function hb_column_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'size' => '',
			'class' => '',
			'margin_top' => '',
			'animation' => '',
			'animation_delay' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( $margin_top != '' )
			return '<div class="' . $size . ' '. $class . $animation .'" style="margin-top:' . $margin_top . '"'. $animation_delay .'>' . do_shortcode($content) . '</div>';
		else
			return '<div class="' . $size . ' '. $class . $animation .'"'.$animation_delay.'>' . do_shortcode($content) . '</div>';
	}
}
add_shortcode('column', 'hb_column_shortcode');

/* FULLWIDTH GOOGLE MAP
-------------------------------------------------- */
if ( !function_exists('hb_fw_map_embed_shortcode')) {
	function hb_fw_map_embed_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'latitude' => '48.856614',
			'longitude' => '2.352222',
			'from_to' => 'no',
			'zoom' => '16',
			'custom_pin' => '',
			'height' => '350',
			'margin_bottom' => '',
			'margin_top' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$classes = "map-wrapper shadow";

		if ($margin_top != ''){
			$margin_top = 'margin-top: ' . $margin_top . ';';
		}

		if ($margin_bottom != ''){
			$margin_bottom = 'margin-bottom: ' . $margin_bottom . ';';
		}

		$margins = ' style="'.$margin_top.$margin_bottom . '"';

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ( $custom_pin == "" )
			$custom_pin = hb_options('hb_custom_marker_image'); 
		else if ( is_numeric($custom_pin)) {
			$custom_pin = wp_get_attachment_image_src ( $custom_pin, 'full');
			$custom_pin = $custom_pin[0];
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$style = "";
		if ( $height != "" ) 
			$style = ' style="height:' . $height . 'px;"';

		$data_show_location = "0";

		if ( $from_to == "yes" ) { 
			$latitude = hb_options('hb_map_latitude');
			$longitude = hb_options('hb_map_longitude');
			$data_show_location = "-1";
		}

		$output = "";

		$output .= '<div class="fw-section without-border"'.$margins.'>';
		$output .= '<div class="content-total-fw">';

		$output .= '<div class="shortcode-wrapper shortcode-map-embed clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="' . $classes . '">';
		$output .= '<div class="hb-gmap-map" data-api-key="' . hb_options('hb_gmap_api_key') . '" data-show-location="' . $data_show_location . '" data-map-level="' . $zoom . '" data-map-lat="' . $latitude . '" data-map-lng="' . $longitude . '" data-map-img="' . $custom_pin . '" data-overlay-color="';
		
			if ( hb_options('hb_enable_map_color') ) 
			{ 
				$output .= hb_options('hb_map_focus_color'); 
			} 
			else { $output .= 'none'; }

		$output .= '"' . $style . '></div>';
		$output .= '</div>';
		$output .= '</div>';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('fw_map_embed', 'hb_fw_map_embed_shortcode');

/* PROCESS STEPS 
-------------------------------------------------- */
if ( !function_exists('hb_process_steps_shortcode')) {
	function hb_process_steps_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$my_content = "";
		$steps = substr_count($content, '/process_step');

		$output = '<div class="hb-process-steps clearfix steps-' . $steps . ' ' . $animation . $class . '"' . $animation_delay . '>';
		$output .= '<ul class="hb-process">';
		
		/* Remove unwanted br tags added by WordPress */
		$my_content = do_shortcode($content);
		$my_content = preg_replace("/<br\W*?\/>/", "\n", $my_content);
		$output .= $my_content;

		$output .= '</ul>';
		$output .= '</div>';
		return $output;  
	}
}
add_shortcode('process_steps', 'hb_process_steps_shortcode');

/* PROCESS STEP
-------------------------------------------------- */
if ( !function_exists('hb_process_step_shortcode')) {
	function hb_process_step_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'title' => '',
			'icon' => '',
			'link' => '',
			'new_tab' => 'no',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));
		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($new_tab == 'yes'){
			$new_tab = ' target="_blank"';
		} else {
			$new_tab = '';
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}
		$output = '<li><div class="feature-box aligncenter">';

		if ( $icon ) {
			if ( is_numeric($icon) ){
				if ( $link ) {
					$output .= '<a href="'.$link.'"'.$new_tab.'><i class="ic-holder-1">' . $icon . '</i></a>';
				} else {
					$output .= '<i class="ic-holder-1">' . $icon . '</i>';
				}
			}
			else {
				if ( $link ) {
					$output .= '<a href="'.$link.'"'.$new_tab.'><i class="' . $icon . ' ic-holder-1"></i></a>';
				} else {
					$output .= '<i class="' . $icon . ' ic-holder-1"></i>';
				}
			}
		}

		if ( $title ) {

			if ( $link ) {
				$output .= '<a href="'.$link.'"'.$new_tab.'><h4 class="bold">' . $title . '</h4></a>';
			} else {
				$output .= '<h4 class="bold">' . $title . '</h4>';
			}
			$output .= '<div class="hb-small-break"></div>';
		}

		if ( $content )
			$output .= '<p>' . $content . '</p>';
		
		$output .= '</div></li>';
								

		return $output;  
	}
}
add_shortcode('process_step', 'hb_process_step_shortcode');

/* PRICING TABLE 
-------------------------------------------------- */
if ( !function_exists('hb_pricing_table')) {
	function hb_pricing_table_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'pricing_item' => '',
			'style' => 'standard',
			'columns' => '1',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( !$style ) $style = "standard";
		if ( !$columns ) $columns = 4;

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$query = new WP_Query( array( 'post_type' => 'hb_pricing_table', 'post__in' => array( $pricing_item ) ) );
		
		$output = "";

		if ( $query->have_posts() ) : 
			$output = '<div class="hb-pricing-table-wrapper pricing-' . $style . ' columns-' . $columns . ' clearfix' . $class . $animation . '"' . $animation_delay . '>';
			while ( $query->have_posts() ) : $query->the_post();
				$items = vp_metabox('pricing_settings.hb_pricing_table_items');
				if (!empty($items)) {
					foreach ($items as $item) {

						if ( $item['hb_pricing_featured'] )
							$output .= '<div class="hb-pricing-item highlight-table">';
						else
							$output .= '<div class="hb-pricing-item">';

						if ( $item['hb_pricing_ribbon'] != 'none' && $item['hb_pricing_ribbon'] != "" ) {
							if ( $item['hb_pricing_ribbon'] == 'blue' )
								$output .= '<span class="hb-pricing-ribbon alt">' . $item['hb_pricing_featured_ribbon'] . '</span>';
							else
								$output .= '<span class="hb-pricing-ribbon">' . $item['hb_pricing_featured_ribbon'] . '</span>';
						} 

						if ( $item['hb_pricing_price'] ) {
							$bg_color = "";
							if ( $style == "colored" )
								$bg_color = ' style="background:' . $item['hb_pricing_color'] . ';"';
							$output .= '<div class="pricing-table-price"' . $bg_color . '>' . $item['hb_pricing_price'] ;
							if ( $item['hb_pricing_period'] ) $output .= '<span>/ ' . $item['hb_pricing_period'] . '</span>';
							$output .= '</div>';
						}
						if ( $item['hb_pricing_title'] ) {
							$bg_color = "";
							if ( $style == "colored" )
								$bg_color = ' style="background:' . $item['hb_pricing_color'] . ';"';
							$output .= '<div class="pricing-table-caption"' . $bg_color . '>' . $item['hb_pricing_title'] . '</div>';
						}

						$output .= '<div class="pricing-table-content">';
						if ( function_exists('wpb_js_remove_wpautop') )	
							$output .= wpb_js_remove_wpautop($item['hb_pricing_feature_list']);
						else
							$output .= do_shortcode($item['hb_pricing_feature_list']);	
						//$output .= do_shortcode($item['hb_pricing_feature_list']);
						//if ( $item['hb_pricing_button_text'] && $item['hb_pricing_button_link'] )
						//	$output .= '<div class="clear" style="margin-bottom:20px;"></div><a class="hb-button hb-small-button hb-second-dark" href="' . $item['hb_pricing_button_link'] . '" target="_self">' . $item['hb_pricing_button_text'] . '</a>';
						$output .= '</div>';


						$output .= '</div>';
					}
				}
			endwhile;
			$output .= '</div>';
		endif;
		wp_reset_query();

		return $output;  
	}
}
add_shortcode('pricing_table', 'hb_pricing_table_shortcode');

/* BLOG CAROUSEL
-------------------------------------------------- */
if ( !function_exists('hb_blog_carousel_shortcode')) {
	function hb_blog_carousel_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'read_more' => 'yes',
			'visible_items' => '2',
			'total_items' => '10',
			'category' => '',
			'excerpt_length' => hb_options('hb_blog_excerpt_length'),
			'orderby' => 'date',
			'order' => 'DESC',
			'carousel_speed' => '3000',
			'auto_rotate' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ( $auto_rotate == "no" ) 
			$auto_rotate = "false";
		else 
			$auto_rotate = "true";
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( !$excerpt_length ) $excerpt_length = hb_options('hb_blog_excerpt_length');

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'post',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $total_items,
					'tax_query' => array(
							array(
								'taxonomy' => 'category',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'post',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $total_items,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="shortcode-wrapper shortcode-blog-carousel blog-carousel-wrapper' . $class . $animation . '"' . $animation_delay . '>';

		// Carousel Nav
		$output .= '<div id="carousel-nav-' . $unique_id . '" class="crsl-nav">';
		$output .= '<a href="#" class="previous"><i class="icon-angle-left"></i></a>';
		$output .= '<a href="#" class="next"><i class="icon-angle-right"></i></a>';
		$output .= '</div>';

		// Carousel Items
		//$output .= '<div class="crsl-items init-carousel" id="carousel-' . $unique_id . '" data-navigation="carousel-nav-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'">';

		$output .= '<div class="crsl-items init-carousel" id="carousel-' . $unique_id . '" data-navigation="carousel-nav-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'">';
		$output .= '<div class="crsl-wrap">';
		while ( $queried_items->have_posts() ) : $queried_items->the_post();

			$output .= '<div class="blog-shortcode-1 crsl-item">';
			
			if ( hb_options('hb_blog_enable_date') )
				$output .= '<div class="blog-list-item-date">' . get_the_time('d') . '<span>' . get_the_time('M') . '</span></div>';

			$output .= '<div class="blog-list-content';
			if ( !hb_options('hb_blog_enable_date') )
				$output .= ' nlm';
			$output .= '">';
			$output .= '<h6 class="special"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h6>';

			if ( hb_options('hb_blog_enable_comments') && comments_open() ) {
				$comm_num = get_comments_number();
				if ( $comm_num != 1 )
					$output .= '<small>' . $comm_num . __(' Comments' , 'hbthemes') . '</small>';
				else 
					$output .= '<small>' . __('1 Comment' , 'hbthemes') . '</small>';
			}

			$output .= '<div class="blog-list-item-excerpt">';
			$output .= '<p>' . wp_trim_words( strip_shortcodes( get_the_content() ) , $excerpt_length , NULL) . '</p>';
			if ( $read_more == "yes" )
				$output .= '<a href="' . get_permalink() . '" class="simple-read-more">'. __('Read More', 'hbthemes') .'</a>';
			$output .= '</div>';
			$output .= '</div>';
			
			$output .= '</div>';

		endwhile;

		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		endif;
		wp_reset_query();
		
		return $output;  
	}
}
add_shortcode('blog_carousel', 'hb_blog_carousel_shortcode');

/* LIST
-------------------------------------------------- */
if ( !function_exists('hb_list_shortcode')) {
	function hb_list_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'type' => 'unordered',
			'lined' => 'no',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}
			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}
			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($lined == 'yes'){
			$lined = ' line-list';
		} else {
			$lined = '';
		}

		$output = '';

		if ( $type == 'ordered' ){
			$output = '<div class="shortcode-wrapper shortcode-list clearfix'.$class.$animation.'"'.$animation_delay.'>';
			$output .= '<ol class="hb-ordered-list'.$lined.'">';
			$output .= shortcode_empty_paragraph_fix(do_shortcode($content));
			$output .= '</ol>';
			$output .= '</div>';
		} else if ( $type == 'unordered' ){
			$output = '<div class="shortcode-wrapper shortcode-list clearfix'.$class.$animation.'"'.$animation_delay.'>';
			$output .= '<ul class="hb-unordered-list'.$lined.'">';
			$output .= shortcode_empty_paragraph_fix(do_shortcode($content));
			$output .= '</ul>';
			$output .= '</div>';
		} else {
			$output = '<div class="shortcode-wrapper shortcode-list clearfix'.$class.$animation.'"'.$animation_delay.'>';
			$output .= '<ul class="hb-ul-list'.$lined.'">';
			$output .= shortcode_empty_paragraph_fix(do_shortcode($content));
			$output .= '</ul>';
			$output .= '</div>';
		}

		wpautop( $output, false );
		
		return $output;  
	}
}
add_shortcode('list', 'hb_list_shortcode');

/* LIST ITEM
-------------------------------------------------- */
if ( !function_exists('hb_list_item_shortcode')) {
	function hb_list_item_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'icon' => '',
			'color' => ''
		), $params));

		$output = '';

		if ($icon != ''){
			if ($color != ''){
				if ($color[0] == '#'){
					$color = ' style="color:'.$color.'"';
				} else {
					$color = ' style="color:#'.$color.'"';
				}
			}

			$icon = '<i class="'.$icon.'"'.$color.'></i>';
		}

		$output .= '<li>';
		$output .= $icon . do_shortcode($content);
		$output .= '</li>';
		
		return $output;  
	}
}
add_shortcode('list_item', 'hb_list_item_shortcode');

/* ICON COLUMN
-------------------------------------------------- */
if ( !function_exists('hb_icon_column_shortcode')) {
	function hb_icon_column_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'icon' => 'hb-moon-brain',
			'title' => 'My column title',
			'align' => 'left',
			'link' => '',
			'new_tab' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}
			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}
			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($align == 'left'){
			$align = ' alignleft';
		} else if ($align == 'center'){
			$align = ' aligncenter';
		} else {
			$align = ' alignright';
		}

		if ($icon != ''){
			if ( strlen($icon) < 3 ){
				$icon = '<i class="title-icon">'.$icon.'</i>';
			} else {
				$icon = '<i class="'.$icon.' title-icon"></i>';
			}
		}

		if ($new_tab == 'yes'){
			$new_tab = ' target="_blank"';
		} else {
			$new_tab = ' target="_self"';
		}

		$output = '<div class="shortcode-wrapper shortcode-icon-column clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="feature-box standard-icon-box'.$align.'">';

		$output .= '<div class="feature-box-content">';
		if ($link != ''){
			$output .= '<h4 class="bold"><a href="'.$link.'"'.$new_tab.'>'.$icon.$title.'</a></h4>';
		} else {
			$output .= '<h4 class="bold">'.$icon.$title.'</h4>';
		}
		$output .= do_shortcode($content);
		$output .= '</div>';

		$output .= '</div>';
		$output .= '</div>';

		return $output;  
	}
}
add_shortcode('icon_column', 'hb_icon_column_shortcode');

/* SKILLS BAR
-------------------------------------------------- */
if ( !function_exists('hb_skill_shortcode')) {
	function hb_skill_shortcode($params = array()) {

		extract(shortcode_atts(array(   
			'number' => '75',
			'char' => '%',
			'caption' => 'Enter Caption',
			'color' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		$bg_color = '';

		if ($color != ''){
			if ($color[0] == '#'){
				$bg_color = ' style="background-color:'.$color.';"';
				$color = ' style="color:'.$color.'"';
			} else {
				$bg_color = ' style="background-color:#'.$color.';"';
				$color = ' style="color:#'.$color.'"';
			}
		}

		$output = '<div class="hb-skill-meter clearfix'.$class.'">';
		
		$output .= '<div class="hb-skill-meter-title clearfix">';
		$output .= '<span class="bar-title">'.$caption.'</span>';
		$output .= '<span class="progress-value"'.$color.'"><span class="value">'.$number.'</span>'.$char.'</span>';
		$output .= '</div>';

		$output .= '<div class="hb-progress-bar">';
		$output .= '<span class="progress-outer" data-width="'.$number.'"><span class="progress-inner"'.$bg_color.'></span></span>';
		$output .= '</div>';

		$output .= '</div>';

		
		return $output;  
	}
}
add_shortcode('skill', 'hb_skill_shortcode');

/* ICON FEATURE
-------------------------------------------------- */
if ( !function_exists('hb_icon_feature_shortcode')) {
	function hb_icon_feature_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'icon' => '',
			'icon_position' => 'center',
			'border' => 'yes',
			'title' => '',
			'align' => 'left',
			'image' => '',
			'link' => '',
			'new_tab' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($new_tab == 'yes'){
			$new_tab = ' target="_blank"';
		} else {
			$new_tab = ' target="_self"';
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}
			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}
			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($icon != ''){
			if ( strlen($icon) < 3 ){
				$icon = '<i class="ic-holder-1">'.$icon.'</i>';
			} else {
				$icon = '<i class="'.$icon.' ic-holder-1"></i>';
			}
		}

		if ($image != ''){
			$icon = '';
			$alt = __("Featured Image" , "hbthemes");
			if( strpos($image, "http" ) !== false){
				// Image URL
			} else {
				// Image ID
				$alt = get_post_meta($image, '_wp_attachment_image_alt', true );
				$image = wp_get_attachment_image_src($image, 'full');
				$image = $image[0];
			}

			$image = '<img class="icon-box-img" src="'.$image.'" alt="' . $alt . '">';
		}

		if ( $border ){
			if ( $border == 'yes' ){
				$border = '';
			} else {
				$border = ' alternative';
			}
		} else {
			$border = ' alternative';
		}

		if ($icon_position == 'left'){
			$icon_position = ' left-icon-box';
		} else if ($icon_position == 'center'){
			$icon_position = '';
		} else {
			$icon_position = ' right-icon-box';
		}

		if ($align == 'left'){
			$align = 'alignleft';
		} else if ($align == 'center'){
			$align = 'aligncenter';
		} else {
			$align = 'alignright';
		}

		if ( $title != '' ){
			if ($link != ''){
				$title = '<h4 class="bold"><a href="'.$link.'"'.$new_tab.'>'.$title.'</a></h4>';
			} else {
				$title = '<h4 class="bold">'.$title.'</h4>';
			}
		}

		$output = '<div class="shortcode-wrapper shortcode-icon-box clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="feature-box'.$border.$icon_position.' aligncenter">';
		
		if ($link != '' && $icon != ''){
			$output .= '<a href="'.$link.'"'.$new_tab.' class="hb-icon-wrapper">' . $icon . '</a>';
		} else if ( $link != '' && $image != '' ){
			$output .= '<a href="'.$link.'"'.$new_tab.'>' . $image . '</a>';
		} else if ( $link == '' ){

			if ($icon != ''){
				$output .= '<div class="hb-icon-wrapper">'.$icon.'</div>';
			} else if ($image != ''){
				$output .= '<div class="hb-icon-wrapper">' . $image . '</div>';
			}
		}

		if ( $image != '' ){
			$output .= '<div class="feature-box-content with-image">';	
		} else {
			$output .= '<div class="feature-box-content">';
		}
		$output .= $title;
		if ($icon_position == ''){
			$output .= '<div class="hb-small-break"></div>';
		}
		$output .= '<p>' . do_shortcode($content) . '</p>';
		$output .= '</div>';

		$output .= '</div>';
		$output .= '</div>';
		
		return $output;  
	}
}
add_shortcode('icon_feature', 'hb_icon_feature_shortcode');

/* TESTIMONIAL BOX
-------------------------------------------------- */
if ( !function_exists('hb_testimonial_box_shortcode')) {
	function hb_testimonial_box_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'type' => 'normal',
			'count' => '4',
			'columns' => '1',
			'category' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( !$columns ) $columns = 2;

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'hb_testimonials',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $count,
					'tax_query' => array(
							array(
								'taxonomy' => 'testimonial_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'hb_testimonials',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $count,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="shortcode-wrapper shortcode-testimonial-box testimonial-box-wrapper' . $class . $animation . '"' . $animation_delay . '>';

		$item_count = 0;

		while ( $queried_items->have_posts() ) : $queried_items->the_post();

			if ( $item_count % $columns == 0 )
				$output .= '<div class="row">';
		
			$output .= '<div class="col-' . 12/$columns . '">';
				
			if ( $type == "normal")  {
				$output .= '<div class="hb-testimonial-box">';
				ob_start();
				hb_testimonial_box (get_the_ID());
				$output .= ob_get_clean();
				$output .= '</div>';	
			} else if ( $type == "large" ) {
				$output .= '<div class="hb-testimonial-quote">';
				ob_start();
				hb_testimonial_quote (get_the_ID());
				$output .= ob_get_clean();
				$output .= '</div>';	
			}
			$output .= '</div>';

			if ( $item_count % $columns == $columns - 1 || $item_count == ($queried_items->found_posts) - 1 )
				$output .= '</div>';
		
			$item_count++;

		endwhile;

		$output .= '</div>';
		
		endif;
		wp_reset_query();
		
		return $output;  
	}
}
add_shortcode('testimonial_box', 'hb_testimonial_box_shortcode');

/* TEAM MEMBER BOX
-------------------------------------------------- */
if ( !function_exists('hb_team_member_box_shortcode')) {
	function hb_team_member_box_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'count' => '1',
			'columns' => '1',
			'excerpt_length' => '20',
			'category' => '',
			'style' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'animation' => '',
			'animation_delay' => '',
			'class' => '',
			'auto_rotate' => 'false'
		), $params));


		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ( $auto_rotate == "no" ) 
			$auto_rotate = "false";
		else 
			$auto_rotate = "true";
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'team',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $count,
					'tax_query' => array(
							array(
								'taxonomy' => 'team_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'team',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $count,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$item_count = 0;
		$output .= '<div class="shortcode-wrapper shortcode-team-member-box ' . $class . $animation . '"' . $animation_delay . '>';
		while ( $queried_items->have_posts() ) : $queried_items->the_post();

			if ( $item_count % $columns == 0 )
				$output .= '<div class="row">';
		
			$output .= '<div class="col-' . 12/$columns . '">';
			ob_start();
			hb_team_member_box (get_the_ID(), $style, $excerpt_length);
			$output .= ob_get_clean();
			$output .= '</div>';

			if ( $item_count % $columns == $columns - 1 || $item_count == ($queried_items->found_posts) - 1 )
				$output .= '</div>';
		
			$item_count++;
		endwhile;
		$output .= '</div>';

		endif;
		wp_reset_query();
		return $output;  
	}
}
add_shortcode('team_member_box', 'hb_team_member_box_shortcode');

/* TEAM MEMBER CAROUSEL
-------------------------------------------------- */
if ( !function_exists('hb_team_carousel_shortcode')) {
	function hb_team_carousel_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'visible_items' => '2',
			'style' => '',
			'total_items' => '4',
			'excerpt_length' => '20',
			'category' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'carousel_speed' => '5000',
			'auto_rotate' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));


		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ( $auto_rotate == "no" ) 
			$auto_rotate = "false";
		else 
			$auto_rotate = "true";
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'team',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $total_items,
					'tax_query' => array(
							array(
								'taxonomy' => 'team_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'team',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $total_items,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="hb-crsl-wrapper clearfix shortcode-wrapper shortcode-team-carousel ' . $class . $animation . '"' . $animation_delay . '>';

		// Carousel Nav
		$output .= '<div id="carousel-nav-' . $unique_id . '" class="crsl-nav">';
		$output .= '<a href="#" class="previous"><i class="icon-angle-left"></i></a>';
		$output .= '<a href="#" class="next"><i class="icon-angle-right"></i></a>';
		$output .= '</div>';

		// Carousel Items

		$output .= '<div class="crsl-items init-team-carousel" id="carousel-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'" data-navigation="carousel-nav-' . $unique_id . '">';
		$output .= '<div class="crsl-wrap">';
		while ( $queried_items->have_posts() ) : $queried_items->the_post();
			$output .= '<figure class="crsl-item">';
			ob_start();
			hb_team_member_box (get_the_ID(), $style, $excerpt_length);
			$output .= ob_get_clean();
			$output .= '</figure>';
		endwhile;
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		
		endif;
		wp_reset_query();

		return $output;  
	}
}
add_shortcode('team_carousel', 'hb_team_carousel_shortcode');

/* TESTIMONIAL SLIDER
-------------------------------------------------- */
if ( !function_exists('hb_testimonial_slider_shortcode')) {
	function hb_testimonial_slider_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'type' => 'normal',
			'count' => '-1',
			'orderby' => 'date',
			'order' => 'DESC',
			'category' => '',
			'animation_speed' => '',
			'slideshow_speed' => '5000',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));


		if ( $class != '' ){
			$class = ' ' . $class;
		}

		$special_class = "ts-1";
		if ( $type == "large" ) {
			$special_class = "ts-2";
		}
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'hb_testimonials',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $count,
					'tax_query' => array(
							array(
								'taxonomy' => 'testimonial_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'hb_testimonials',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $count,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="shortcode-wrapper shortcode-testimonial-slider testimonial-slider-wrapper' . $class . $animation . '"' . $animation_delay . '>';

		$output .= '<div id="hb-testimonial-' . $unique_id . '" class="'.$special_class.' init-testimonial-slider" data-slideshow-speed="'.$slideshow_speed.'">';
		$output .= '<ul class="testimonial-slider">';
		while ( $queried_items->have_posts() ) : $queried_items->the_post();
			if ( $type == "normal")  {
				$output .= '<li class="hb-testimonial-box">';
				ob_start();
				hb_testimonial_box (get_the_ID());
				$output .= ob_get_clean();
				$output .= '</li>';	
			} else if ( $type == "large" ) {
				$output .= '<li class="hb-testimonial-quote">';
				ob_start();
				hb_testimonial_quote (get_the_ID());
				$output .= ob_get_clean();
				$output .= '</li>';	
			}
		endwhile;

		$output .= '</ul>';
		$output .= '</div>';
		$output .= '</div>';
		
		endif;

		wp_reset_query();
		
		return $output;  
	}
}
add_shortcode('testimonial_slider', 'hb_testimonial_slider_shortcode');

/* CLIENT CAROUSEL
-------------------------------------------------- */
if ( !function_exists('hb_client_carousel_shortcode')) {
	function hb_client_carousel_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'style' => 'simple',
			'visible_items' => '2',
			'total_items' => '12',
			'category' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'new_tab' => 'no',
			'carousel_speed' => '5000',
			'auto_rotate' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( !$style ) $style = 'simple';

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		
		if ( $auto_rotate == "no" ) 
			$auto_rotate = "false";
		else 
			$auto_rotate = "true";
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($new_tab == 'yes'){
			$new_tab = ' target="_blank"';
		} else {
			$new_tab = ' target="_self"';
		}

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'clients',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $total_items,
					'tax_query' => array(
							array(
								'taxonomy' => 'client_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'clients',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $total_items,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="shortcode-wrapper shortcode-client-carousel client-carousel-wrapper' . $class . $animation . '"' . $animation_delay . '>';

		// Carousel Nav
		$output .= '<div id="carousel-nav-' . $unique_id . '" class="crsl-nav">';
		$output .= '<a href="#" class="previous"><i class="icon-angle-left"></i></a>';
		$output .= '<a href="#" class="next"><i class="icon-angle-right"></i></a>';
		$output .= '</div>';

		// Carousel Items
		//$output .= '<div class="crsl-items init-carousel" id="carousel-' . $unique_id . '" data-navigation="carousel-nav-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'">';

		$output .= '<ul class="hb-client-list crsl-items init-carousel columns-' . $visible_items . ' ' . $style . ' clearfix" id="carousel-' . $unique_id . '" data-navigation="carousel-nav-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'">';
		$output .= '<div class="crsl-wrap">';
		while ( $queried_items->have_posts() ) : $queried_items->the_post();
			$thumb = get_post_thumbnail_id();
			$output .= '<li class="crsl-item">';
			if ( vp_metabox('clients_settings.hb_client_url') )
				$output .= '<a href="' . vp_metabox('clients_settings.hb_client_url') . '"' . $new_tab . '>';
			else 
				$output .= '<a href="#">';
			$output .= '<img src="' . vp_metabox('clients_settings.hb_client_logo') . '" alt="' . get_post_meta($thumb, '_wp_attachment_image_alt', true ) . '" data-title="Optional Caption Here"/></a></li>';
		endwhile;

		$output .= '</div>';
		$output .= '</ul>';
		$output .= '</div>';

		endif;

		wp_reset_query();
		return $output;  
	}
}
add_shortcode('client_carousel', 'hb_client_carousel_shortcode');

/* TEASER
-------------------------------------------------- */
if ( !function_exists('hb_teaser_shortcode')) {
	function hb_teaser_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'new_tab' => '',
			'button_link' => '',
			'button_title' => '',
			'style' => 'boxed',
			'align' => 'alignleft',
			'image' => '',
			'title' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($image != ''){
			$alt = __("Teaser Image" , "hbthemes");
			if( strpos($image, "http" ) !== false){
				// Image URL
			} else {
				// Image ID
				$image = wp_get_attachment_image_src($image, 'full');
				$image = $image[0];
			}

			$resized_img = hb_resize('',$image, 526, 350, true);
			if ( $resized_img )
				$image = '<img src="'.$resized_img['url'] .'" alt="' . $alt . '" />';
		}

		if ($style == 'alternative'){
			$style = ' alternative';
		} else {
			$style = '';
		}

		if ($align == 'alignright'){
			$align = ' alignright';
		} else if ($align == 'alignleft'){
			$align = ' alignleft';
		} else if ($align == 'aligncenter'){
			$align = ' aligncenter';
		} else {
			$align = '';
		}

		if ($title != ''){
			$title = '<h6 class="special">'.$title.'</h6>';
		}

		if ($new_tab == 'yes'){
			$new_tab = ' target="_blank"';
		} else {
			$new_tab = ' target="_self"';
		}

		if ($button_title != ''){
			$button_title = '<div class="clear"></div><a href="'.$button_link.'" class="simple-read-more"'.$new_tab.'>'.$button_title.'</a>';
		}


		$output = '<div class="shortcode-wrapper shortcode-teaser clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="hb-teaser-column'.$style.'">';
		$output .= $image;
		$output .='<div class="teaser-content'.$align.'">';
		$output .= $title;
		$output .= do_shortcode($content);
		$output .= $button_title;
		$output .='</div>';
		$output .= '</div>';
		$output .= '</div>';
		
		return $output;  
	}
}
add_shortcode('teaser', 'hb_teaser_shortcode');

/* ICON BOX
-------------------------------------------------- */
if ( !function_exists('hb_icon_box_shortcode')) {
	function hb_icon_box_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'icon' => '',
			'icon_color' => '',
			'icon_position' => 'top',
			'title' => '',
			'link' => '',
			'align' => 'left',
			'new_tab' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}
			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}
			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($icon_position == 'left'){
			$icon_position = ' left-icon';
		} else {
			$icon_position = ' top-icon';
		}

		if ($icon != ''){

			if ($icon_color != ''){
				if ( substr($icon_color, 0,1) == '#' ){
					$icon_color = substr($icon_color, 1);
				}
				$icon_color = ' style="background-color:#'.$icon_color.';"';
			}

			$icon = '<i class="'.$icon.' box-icon"'.$icon_color.'></i>';
		}

		if ($align == 'left'){
			$align = 'alignleft';
		} else if ($align == 'center'){
			$align = 'aligncenter';
		} else {
			$align = 'alignright';
		}

		if ( $new_tab == "yes" ) $new_tab = "_blank";
		else $new_tab = "_self";

		$output = '<div class="shortcode-wrapper shortcode-icon-box clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="content-box'.$icon_position.'">';
		$output .= $icon;
		$output .= '<div class="'.$align.'">';
		if ( $link ) 
			$output .= '<a href="' . $link . '" target="' . $new_tab . '">';
		if ( $title )
			$output .= '<h4 class="semi-bold">' . $title . '</h4>';
		if ( $link )
			$output .= '</a>';
		if ( function_exists('wpb_js_remove_wpautop') )	
			$output .= wpb_js_remove_wpautop($content);
		else
			$output .= do_shortcode($content);	
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		return $output;  
	}
}
add_shortcode('icon_box', 'hb_icon_box_shortcode');

/* TITLE
-------------------------------------------------- */
if ( !function_exists('hb_title_shortcode')) {
	function hb_title_shortcode($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'type' => 'extra-large',
			'color' => '',
			'animation' => '',
			'align'		=> '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($color != ''){
			$color = ' style="color:'.$color.'!important;border-color:'. $color . '"';
		}

		if ($align == 'center'){
			$align = "aligncenter";
		} else if ($align == 'left'){
			$align = "alignleft";
		} else if ($align == 'right'){
			$align = "alignright";
		}

		$ret_title = '';

		if ($type == 'extra-large'){
			$ret_title = '<p'.$color.' class="hb-text-large '.$align.'">' . do_shortcode($content) . '</p>';
		} else if ($type == 'h1'){
			$ret_title = '<h1'.$color.' class="'.$align.'">' . do_shortcode($content) . '</h1>';
		} else if ($type == 'h2'){
			$ret_title = '<h2'.$color.' class="'.$align.'">' . do_shortcode($content) . '</h2>';
		} else if ($type == 'h3'){
			$ret_title = '<h3'.$color.' class="'.$align.'">' . do_shortcode($content) . '</h3>';
		} else if ($type == 'h4'){
			$ret_title = '<h4'.$color.' class="'.$align.'">' . do_shortcode($content) . '</h4>';
		} else if ($type == 'h5'){
			$ret_title = '<h5'.$color.' class="'.$align.'">' . do_shortcode($content) . '</h5>';
		} else if ($type == 'h6'){
			$ret_title = '<h6'.$color.' class="'.$align.'">' . do_shortcode($content) . '</h6>';
		} else if ($type == 'special-h3'){
			$ret_title = '<h3'.$color.' class="hb-heading hb-center-heading '.$align.'"><span>' . do_shortcode($content) . '</span></h3>';
		} else if ($type == 'special-h3-left'){
			$ret_title = '<h3'.$color.' class="hb-heading '.$align.'"><span>' . do_shortcode($content) . '</span></h3>';
		} else if ($type == 'special-h3-right'){
			$ret_title = '<h3'.$color.' class="hb-heading hb-right-heading '.$align.'"><span>' . do_shortcode($content) . '</span></h3>';
		} else if($type == 'special-h4'){
			$ret_title = '<h4'.$color.' class="hb-heading hb-center-heading '.$align.'"><span>' . do_shortcode($content) . '</span></h4>';
		} else if ($type == 'special-h4-left'){
			$ret_title = '<h4'.$color.' class="hb-heading '.$align.'"><span>' . do_shortcode($content) . '</span></h4>';
		} else if ($type == 'special-h4-right'){
			$ret_title = '<h4'.$color.' class="hb-heading hb-right-heading '.$align.'"><span>' . do_shortcode($content) . '</span></h4>';
		} else if ($type == 'fancy-h1'){
			$ret_title = '<div class="'.$align.'">';
			$ret_title .= '<h1'.$color.' class="hb-bordered-heading '.$align.'">' . do_shortcode($content) . '</h1>';
			$ret_title .= '</div>';
		} else if ($type == 'fancy-h2'){
			$ret_title = '<div class="'.$align.'">';
			$ret_title .= '<h2'.$color.' class="hb-bordered-heading '.$align.'">' . do_shortcode($content) . '</h2>';
			$ret_title .= '</div>';
		} else if ($type == 'fancy-h3'){
			$ret_title = '<div class="'.$align.'">';
			$ret_title .= '<h3'.$color.' class="hb-bordered-heading '.$align.'">' . do_shortcode($content) . '</h3>';
			$ret_title .= '</div>';
		} else if ($type == 'fancy-h4'){
			$ret_title = '<div class="'.$align.'">';
			$ret_title .= '<h4'.$color.' class="hb-bordered-heading '.$align.'">' . do_shortcode($content) . '</h4>';
			$ret_title .= '</div>';
		} else if ($type == 'fancy-h5'){
			$ret_title = '<div class="'.$align.'">';
			$ret_title .= '<h5'.$color.' class="hb-bordered-heading '.$align.'">' . do_shortcode($content) . '</h5>';
			$ret_title .= '</div>';
		} else if ($type == 'fancy-h6'){
			$ret_title = '<div class="'.$align.'">';
			$ret_title .= '<h6'.$color.' class="hb-bordered-heading '.$align.'">' . do_shortcode($content) . '</h6>';
			$ret_title .= '</div>';
		} else if ($type == 'subtitle-h3'){
			$ret_title = '<h3'.$color.' class="hb-subtitle '.$align.'">' . do_shortcode($content) . '</h3>';
		} else if ($type == 'subtitle-h6'){
			$ret_title = '<h6'.$color.' class="hb-subtitle-small '.$align.'">' . do_shortcode($content) . '</h6>';
		} else if ($type == 'special-h6' || $type == 'h6-special'){
			$ret_title = '<h6'.$color.' class="special '.$align.'">'. do_shortcode($content) .'</h6>';
		} else if ($type== 'modern-h1'){
			$ret_title = '<h1'.$color.' class="modern '.$align.'">'. do_shortcode($content) .'<span'.$color.' class="bottom-line"></span></h1>';
		} else if ($type== 'modern-h2'){
			$ret_title = '<h2'.$color.' class="modern '.$align.'">'. do_shortcode($content) .'<span'.$color.' class="bottom-line"></span></h2>';
		} else if ($type== 'modern-h3'){
			$ret_title = '<h3'.$color.' class="modern '.$align.'">'. do_shortcode($content) .'<span'.$color.' class="bottom-line"></span></h3>';
		} else if ($type== 'modern-h4'){
			$ret_title = '<h4'.$color.' class="modern '.$align.'">'. do_shortcode($content) .'<span'.$color.' class="bottom-line"></span></h4>';
		} else if ($type== 'modern-h5'){
			$ret_title = '<h5'.$color.' class="modern '.$align.'">'. do_shortcode($content) .'<span'.$color.' class="bottom-line"></span></h5>';
		} else if ($type== 'modern-h6'){
			$ret_title = '<h6'.$color.' class="modern '.$align.'">'. do_shortcode($content) .'<span'.$color.' class="bottom-line"></span></h6>';
		} else if ($type== 'modern-h2-large'){
			$ret_title = '<h2'.$color.' class="modern large '.$align.'">'. do_shortcode($content) .'<span'.$color.' class="bottom-line"></span></h2>';
		} else {
			$ret_title = '<h1'.$color.' class="'.$align.'">' . do_shortcode($content) . '</h1>';
		}

		$output = '<div class="shortcode-wrapper shortcode-title clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= $ret_title;
		$output .= '</div>';
		
		return $output;  
	}
}
add_shortcode('title', 'hb_title_shortcode');

/* IMAGE BANNER
-------------------------------------------------- */
if ( !function_exists('hb_image_banner')) {
	function hb_image_banner($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'url' => '',
			'text_color' => 'dark',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($text_color == 'light'){
			$text_color = ' light-style light-text';
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		$alt = __("Banner Image", "hbthemes");
		if ($url != ''){
			if( strpos($url, "http" ) !== false){
				// Image URL
			} else {
				// Image ID
				$url = wp_get_attachment_image_src($url, 'full');
				$url = $url[0];
			}
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = '<div class="shortcode-wrapper shortcode-image-banner clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="hb-image-banner-content hb-center-me clearfix '.$text_color.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		$output .= '<img src="'.$url.'" alt="' . $alt . '" class="banner-image">';
		$output .= '</div>';

		return $output;  
	}
}
add_shortcode('image_banner', 'hb_image_banner');

/* GALLERY CAROUSEL
-------------------------------------------------- */
if ( !function_exists('hb_gallery_carousel_shortcode')) {
	function hb_gallery_carousel_shortcode($params = array()) {

		extract(shortcode_atts(array(
			'style' => 'standard',
			'visible_items' => '2',
			'total_items' => '10',
			'category' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'carousel_speed' => '3000',
			'auto_rotate' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));


		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ( $style != 'modern' ) $style = 'standard';
		
		if ( $auto_rotate == "no" ) 
			$auto_rotate = "false";
		else 
			$auto_rotate = "true";
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'gallery',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $total_items,
					'tax_query' => array(
							array(
								'taxonomy' => 'gallery_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'gallery',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $total_items,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		if ( $style == "standard" )
			$output .= '<div class="shortcode-wrapper shortcode-gallery-carousel gallery-carousel-wrapper-2' . $class . $animation . '"' . $animation_delay . '>';
		else 
			$output .= '<div class="shortcode-wrapper shortcode-gallery-carousel gallery-carousel-wrapper' . $class . $animation . '"' . $animation_delay . '>';

		// Carousel Nav
		$output .= '<div id="carousel-nav-' . $unique_id . '" class="crsl-nav">';
		$output .= '<a href="#" class="previous"><i class="icon-angle-left"></i></a>';
		$output .= '<a href="#" class="next"><i class="icon-angle-right"></i></a>';
		$output .= '</div>';

		// Carousel Items
		$output .= '<div class="crsl-items init-carousel" id="carousel-' . $unique_id . '" data-navigation="carousel-nav-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'">';
		$output .= '<div class="crsl-wrap">';

		while ( $queried_items->have_posts() ) : $queried_items->the_post();
			$thumb = get_post_thumbnail_id();
			$filters_names = wp_get_post_terms(get_the_ID() , 'gallery_categories' , array("fields"=>"names"));
			$filters_names_string = implode ($filters_names, ", ");
			$gallery_rel = "gal_rel_" . rand(1,10000);
			$custom_color = vp_metabox('gallery_settings.hb_gallery_custom_bg_color');
			$thumb_post = get_post( $thumb );
			$thumb_caption = $thumb_post->post_excerpt;

			if ($custom_color){
				$custom_color = ' style="background: ' . hb_color($custom_color, 0.85) . ';"';
			} else {
				$custom_color = "";
			}

			if ( $style == "standard" )
				$output .= '<div class="standard-gallery-item crsl-item" data-value="' . get_the_time('c') . '">';
			else
				$output .= '<div class="gallery-item crsl-item" data-value="' . get_the_time('c') . '">';

			$image = hb_resize($thumb,'',586,349,true);
			$full_image = wp_get_attachment_image_src($thumb, 'full');
			$gallery_attachments = rwmb_meta('hb_gallery_images', array('type' => 'plupload_image', 'size' => 'full') , get_the_ID());
			$filters_names = wp_get_post_terms(get_the_ID() , 'gallery_categories' , array("fields"=>"names"));
			$filters_names_string = implode ($filters_names, ", ");
				
			if ( !$image && !empty($gallery_attachments))
			{
				reset($gallery_attachments);
				$thumb = key($gallery_attachments);
				$image = hb_resize( $thumb, '', 586, 349, true );
				$full_image = wp_get_attachment_image_src($thumb,'full');
			}
			$gallery_count = count ($gallery_attachments ) + ( get_post_thumbnail_id() ? 1 : 0 );


			if ( $style == "standard" )
				$output .= '<div class="hb-gal-standard-img-wrapper">';

			$output .= '<a href="' . $full_image[0] . '" data-title="' . $thumb_caption . '" rel="prettyPhoto[' . $gallery_rel . ']">';
			$output .= '<img src="' . $image['url'] . '" width="'. $image['width'] .'" height="'. $image['height'] .'" alt="' . get_post_meta($thumb, '_wp_attachment_image_alt', true ) . '" />';
			$output .= '<div class="item-overlay"></div>';
			$output .= '<div class="item-overlay-text"'.$custom_color.'>';
			$output .= '<div class="item-overlay-text-wrap">';

			if ( $style == "modern" ) {
				$output .= '<h4><span class="hb-gallery-item-name">' . get_the_title() . '</span></h4>';
				$output .= '<div class="hb-small-separator"></div>';
				$output .= '<span class="item-count-text"><span class="photo-count">' . $gallery_count . '</span>';
				if ( $gallery_count != 1) $output .= __(' Photos' ,'hbthemes');
				else $output .= __(' Photo','hbthemes');
				$output .= '</span>';
			} else {
				$output .= '<span class="plus-sign"></span>';
			}
				
			$output .= '</div>';

			if ( $style == "modern")
				$output .= '<div class="item-date" data-value="' . get_the_time('d F Y') . '">' . get_the_time('d M Y') . '</div>';

			$output .= '</div>';
			$output .= '</a>';

			if ( $style == "standard" )
				$output .= '</div>';
			

			if ( $style == "standard" ) {
				$output .= '<div class="hb-gal-standard-description">';
				$output .= '<h3><a><span class="hb-gallery-item-name">' . get_the_title() . '</span></a></h3>';
				$output .= '<div class="hb-small-separator"></div>';
				if ( $filters_names_string ) $output .= '<div class="hb-gal-standard-count">' . $filters_names_string . '</div>';			
				$output .= '</div>';
			}


			if ( !empty ( $gallery_attachments ) ) {
				$output .= '<div class="hb-reveal-gallery">';
				foreach ( $gallery_attachments as $gal_id => $gal_att ) {
					if ( $gal_id != $thumb )
						$output .= '<a href="' . $gal_att['url'] . '" data-title="' . $gal_att['description'] . '" rel="prettyPhoto[' . $gallery_rel . ']"></a>';
				}
				$output .= '</div>';
			}
			$output .= '</div>';



		endwhile;

		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		endif;

		wp_reset_query();
		
		return $output;  
	}
}
add_shortcode('gallery_carousel', 'hb_gallery_carousel_shortcode');

/* PORTFOLIO CAROUSEL
-------------------------------------------------- */
if ( !function_exists('hb_portfolio_carousel_shortcode')) {
	function hb_portfolio_carousel_shortcode($params = array()) {

		extract(shortcode_atts(array(
			'style' => 'standard',
			'visible_items' => '2',
			'total_items' => '8',
			'category' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'carousel_speed' => '5000',
			'auto_rotate' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));


		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ( $style != 'descriptive' ) $style = 'standard';
		
		if ( $auto_rotate == "no" ) 
			$auto_rotate = "false";
		else 
			$auto_rotate = "5000";
		
		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";

		if ( $category ) {
			$category = str_replace(" ", "", $category);
			$category = explode(",", $category);

			$queried_items = new WP_Query( array( 
					'post_type' => 'portfolio',
					'orderby' => $orderby,
					'order' => $order,
					'status' => 'publish',
					'posts_per_page' => $total_items,
					'tax_query' => array(
							array(
								'taxonomy' => 'portfolio_categories',
								'field' => 'slug',
								'terms' => $category
							)
						)			
			));
		} else {
			$queried_items = new WP_Query( array( 
					'post_type' => 'portfolio',
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $total_items,
					'status' => 'publish',
				));
		}
		$unique_id = rand(1,10000);

		if ( $queried_items->have_posts() ) :

		$output .= '<div class="shortcode-wrapper shortcode-portfolio-carousel gallery-carousel-wrapper-2' . $class . $animation . '"' . $animation_delay . '>';

		// Carousel Nav
		$output .= '<div id="carousel-nav-' . $unique_id . '" class="crsl-nav">';
		$output .= '<a href="#" class="previous"><i class="icon-angle-left"></i></a>';
		$output .= '<a href="#" class="next"><i class="icon-angle-right"></i></a>';
		$output .= '</div>';

		// Carousel Items
		$output .= '<div class="crsl-items init-carousel" id="carousel-' . $unique_id . '" data-navigation="carousel-nav-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'">';
		$output .= '<div class="crsl-wrap">';

		while ( $queried_items->have_posts() ) : $queried_items->the_post();
			$perma = get_the_permalink();
			$custom_url = vp_metabox('portfolio_settings.hb_portfolio_custom_url');			
			if ($custom_url){
				$perma = $custom_url;
			}
			$custom_color = vp_metabox('portfolio_settings.hb_portfolio_custom_bg_color');

			if ($custom_color){
				$custom_color = ' style="background: ' . hb_color($custom_color, 0.85) . ';"';
			} else {
				$custom_color = "";
			}
			$thumb = get_post_thumbnail_id();
			$filters_names = wp_get_post_terms(get_the_ID() , 'portfolio_categories' , array("fields"=>"names"));
			$filters_names_string = implode ($filters_names, ", ");


			$output .= '<div class="standard-gallery-item crsl-item" data-value="' . get_the_time('c') . '">';

			if ( $thumb ) {
				$image = hb_resize($thumb,'',586,349,true);
				$output .= '<div class="hb-gal-standard-img-wrapper">';
				$output .= '<a href="' . $perma . '">';
				$output .= '<img src="' . $image['url'] . '" width="'. $image['width'] .'" height="'. $image['height'] .'" alt="' . get_post_meta($thumb, '_wp_attachment_image_alt', true ) . '" />';
				$output .= '<div class="item-overlay"></div>';
				$output .= '<div class="item-overlay-text"'.$custom_color.'>';
				$output .= '<div class="item-overlay-text-wrap">';
				$output .= '<span class="plus-sign"></span>';
				$output .= '</div>';
				$output .= '</div>';
				$output .= '</a>';
				$output .= '</div>';
			}

			$output .= '<div class="hb-gal-standard-description portfolio-description">';
			$output .= '<h3><a href="' . $perma . '"><span class="hb-gallery-item-name">' . get_the_title() . '</span></a></h3>';
			
			if ( $filters_names_string ) $output .= '<div class="hb-gal-standard-count">' . $filters_names_string . '</div>';
			if ( hb_options('hb_portfolio_enable_likes') ) $output .= hb_print_portfolio_likes(get_the_ID()); 

			if ( $style == "descriptive" ) {
				if ( has_excerpt() )
					$output .= '<p>' . get_the_excerpt() . '</p>';
				else 
					$output .= wp_trim_words( strip_shortcodes( get_the_content() ) , 10 , NULL);
				$output .= '<div class="portfolio-small-meta clearfix">';
				$output .= '<span class="float-left project-date">' . get_the_time('F d, Y') . '</span>';
				$output .= '<a href="' . $perma . '" class="float-right details-link">' . __('Details' , 'hbthemes') . ' <i class="icon-angle-right"></i></a>';
				$output .= '</div>';
			}

			$output .= '</div>';

			$output .= '</div>';
		endwhile;

		$output .= '</div>';
		$output .= '</div>';

		$output .= '</div>';

		endif;

		wp_reset_query();
		
		return $output;  
	}
}
add_shortcode('portfolio_carousel', 'hb_portfolio_carousel_shortcode');

/* IMAGE FRAME
-------------------------------------------------- */
if ( !function_exists('hb_image_frame')) {
	function hb_image_frame($params = array()) {

		extract(shortcode_atts(array(
			'url' => '',
			'border_style' => 'no-frame',
			'action' => 'none',
			'link' => '',
			'rel' => '',
			'image_caption' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$overlay='';
		$img_url='';

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$img_wdth = "";
		$img_hght = "";

		if ($url != ''){
			$alt = __("Image", "hbthemes");
			if( strpos($url, "http" ) !== false){
				// Image URL
				$img_url = $url;
			} else {
				// Image ID
				$url = wp_get_attachment_image_src($url, 'full');
				$img_wdth = $url[1];
				$img_hght = $url[2];
				$url = $url[0];
				$img_url = $url;
			}

			$url = '<img src="'.$url.'" width="' . $img_wdth . '" height="' . $img_hght . '" alt="' . $alt . '"/>';
		}

		if ($border_style != 'circle-frame' && $border_style != 'boxed-frame' && $border_style != 'boxed-frame-hover' && $border_style != 'no-frame'){
			$border_style = 'no-frame';
		}

		if ($border_style == 'boxed-frame-hover'){
			$overlay = '<div class="overlay"><div class="plus-sign"></div></div>';
		}

		if ($rel != ''){
			$rel = '&#91;' . $rel . '&#93;';
		}

		$output = "";

		if ($border_style == 'boxed-frame' || $border_style == 'boxed-frame-hover'){
			$border_style = "box-frame";
		}

		$output .= '<div class="hb-'.$border_style.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<span>';

		if ( $action == 'open-url' ){
			$output .= '<a href="'.$link.'">';
			$output .= $url;
			$output .= $overlay;
			$output .= '</a>';
		} else if ($action == 'open-lightbox'){
			$output .= '<a href="'.$img_url.'" rel="prettyPhoto'.$rel.'" data-title="' . $image_caption . '">';
			$output .= $url;
			$output .= $overlay;
			$output .= '</a>';
		} else {
			$output .= '<a>';
			$output .= $url;
			$output .= $overlay;
			$output .= '</a>';
		}

		$output .= '</span>';
		$output .= '</div>';
		
		return $output;  
	}
}
add_shortcode('image_frame', 'hb_image_frame');

/* COUNTER SHORTCODE
-------------------------------------------------- */
if ( !function_exists('hb_counter_shortcode')) {
	function hb_counter_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'from' => '0',
			'to' => '1250',
			'color' => '',
			'icon' => '',
			'subtitle' => 'My Subtitle',
			'speed' => '700',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($color == 'default'){
			$color = '';
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( $class != "" ) 
			$class  = " " . $class;

		if ($color != ''){
			if ($color[0] == '#'){
				$color = ' style="color:'.$color.'"';
			} else {
				$color = ' style="color:#'.$color.'"';
			}
		}

		if ($subtitle != ''){
			$subtitle = '<div class="count-separator"><span></span></div><h3 class="count-subject"'.$color.'>'.$subtitle.'</h3>';
		}

		if ($icon != ''){
			$icon = '<p class="aligncenter"><i class="'.$icon.' hb-icon hb-icon-float-none"'.$color.'></i></p>';
		}

		$output = '';
		$output = '<div class="shortcode-wrapper shortcode-milestone-counter clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= $icon;
		$output .= '<div class="hb-counter">';
		$output .= '<div class="count-number" data-from="'.$from.'" data-to="'.$to.'" data-speed="'.$speed.'"'.$color.'></div>';
		$output .= $subtitle;
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('counter', 'hb_counter_shortcode');

/* LAPTOP SLIDER
-------------------------------------------------- */
if ( !function_exists('hb_laptop_slider_shortcode')) {
	function hb_laptop_slider_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'speed' => '7000',
			'bullets' => 'yes',
			'images' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( $class != "" ) 
			$class  = " " . $class;

		$unique_id = 'flexslider_' . rand ( 1, 100000 );
		
		if ( $bullets == "yes" ) 
			$bullets = "true";
		else
			$bullets = "false";

		$laptop_url = get_template_directory_uri() . '/images/laptop-mockup.png';

		$output = '<div class="col-12">';
		$output .= '<div class="shortcode-wrapper shortcode-laptop-slider laptop-slider-wrapper' . $class . $animation . '"' . $animation_delay . '>';
		$output .= '<div class="laptop-mockup"><img src="'.$laptop_url.'" alt="Laptop Mockup" width="1240" height="500"/>';
		$output .= '<div class="hb-flexslider-wrapper">';

		$output .= '<div class="hb-flexslider clearfix loading init-flexslider" id="' . $unique_id . '" data-speed="'.$speed.'" data-control-nav="'.$bullets.'">';
		$output .= '<ul class="hb-flex-slides clearfix">';

		if ( !empty($images) ) {
			$all_images = "";
			$images = explode ( ',' , $images ) ;
			foreach ($images as $image_id ) {
				$image_link = wp_get_attachment_image_src($image_id, 'full');
				$att_post = get_post($image_id);
				$all_images .= '[slider_item img="' . $image_link[0] . '" title="' . $att_post->post_title . '" rel="prettyPhoto&#91;' . $unique_id . '&#93;"]';
			}
			$output .= do_shortcode( $all_images );
		} else if ( $content ) {
			$output .= do_shortcode ( $content );
		}

		$output .= '</ul>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('laptop_slider', 'hb_laptop_slider_shortcode');

if ( !function_exists('hb_slider_item_shortcode')) {
	function hb_slider_item_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'img' => '',
			'title' => '',
			'rel' => '',
		), $params));

		if ( $rel != "" )
			$rel = ' rel="' . $rel . '"';
		else 
			$rel = ' rel="prettyPhoto&#91;' . rand(1,100000) . '&#93;"'; 

		$image = hb_resize(null, $img, 900, 565, true);
		if ( $image )
		return '<li><a href="' . $img . '"' . $rel . ' data-title="' . $title . '" alt="Slider Image"><img src="' . $image['url'] . '"/></a></li>';
	}
}
add_shortcode('slider_item', 'hb_slider_item_shortcode');

/* SIMPLE SLIDER
-------------------------------------------------- */
if ( !function_exists('hb_simple_slider_shortcode')) {
	function hb_simple_slider_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'speed' => '7000',
			'pause_on_hover' => 'yes',
			'bullets' => 'yes',
			'border' => 'yes',
			'arrows' => 'yes',
			'images' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( $class != "" ) 
			$class  = " " . $class;

		$unique_id = 'flexslider_' . rand ( 1, 100000 );
		if ( $pause_on_hover == "yes" )
			$pause_on_hover = "true";
		else
			$pause_on_hover = "false";

		if ( $bullets == "yes" ) 
			$bullets = "true";
		else
			$bullets = "false";

		if ( $arrows == "yes" )
			$arrows = "true";
		else
			$arrows = "false";

		if ( $border == "yes" )
			$class .= ' bordered-wrapper';

		$output = "";

		$output .= '<div class="shortcode-wrapper shortcode-simple-slider hb-flexslider-wrapper' . $class . $animation . '"' . $animation_delay . '>';
		$output .= '<div class="hb-flexslider init-flexslider clearfix loading" id="' . $unique_id . '" data-speed="'.$speed.'" data-pause-on-hover="'.$pause_on_hover.'" data-control-nav="'.$bullets.'" data-direction-nav="'.$arrows.'">';
		$output .= '<ul class="hb-flex-slides clearfix">';

		$all_images = "";
		if ( $images != '' ) {
			$images = explode ( ',' , $images ) ;
			foreach ($images as $image_id ) {
				$image_link = wp_get_attachment_image_src($image_id, 'full');
				$att_post = get_post($image_id);
				$all_images .= '[simple_slide img="' . $image_link[0] . '" title="' . $att_post->post_excerpt . '" subtitle="' . $att_post->post_content . '" rel="prettyPhoto&#91;' . $unique_id . '&#93;"]';
			}
			$output .= do_shortcode( $all_images );
		} else if ( $content ) {
			$output .= do_shortcode ( $content );
		}

		$output .= '</ul>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('simple_slider', 'hb_simple_slider_shortcode');

if ( !function_exists('hb_slider_simple_item_shortcode')) {
	function hb_slider_simple_item_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'img' => '',
			'title' => '',
			'subtitle' => '',
			'lightbox' => 'yes',
			'rel' => '',
		), $params));

		if ( $rel != "")
			$rel = ' rel="' . $rel . '"';
		else 
			$rel = ' rel="prettyPhoto&#91;' . rand(1,100000) . '&#93;"'; 

		if ( $lightbox == 'yes' ) 
			$output = '<li><a href="' . $img . '"' . $rel . ' data-title="' . $title . '" alt="Slider Image"><img src="' . $img . '"/></a>';
		else
			$output = '<li><a data-title="' . $title . '" alt="Slider Image"><img src="' . $img . '"/></a>';	
		
		if ( $title ) 
			$output .= '<p class="flex-caption">' . $title . '</p>';
		if ( $subtitle )
			$output .= '<p class="flex-subtitle">' . $subtitle . '</p>';
		$output .= '</li>';

		return $output;
	}
}
add_shortcode('simple_slide', 'hb_slider_simple_item_shortcode');

/* FULLWIDTH SECTION
-------------------------------------------------- */
if ( !function_exists('hb_fw_section')) { 
	function hb_fw_section($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'background_type' => 'color',
			'border' => '',
			'text_color' => 'dark',
			'bg_color' => '',
			'image' => '',
			'parallax' => '',
			'scissors_icon' => '',
			'bg_video_mp4' => '',
			'bg_video_ogv' => '',
			'waved_border_top' => '',
			'waved_border_bottom' => '',
			'bg_video_poster' => '',
			'overlay' => '',
			'margin_top' => '',
			'margin_bottom' => '',
			'padding_top' => '30',
			'padding_bottom' => '30',
			'class' => '',
			'id' => ''
		), $params));
		
		$output = "";
		$img_to_print = "";
		$waved_border = "";
		$waved_css = "";
		$wave_color = "";
		$unique_class = uniqid('hb-fw-');

		$background_texture = "";

		if ($border == 'yes'){
			$border = ' with-border';
		} else {
			$border = ' without-border';		
		}

		if ($text_color == 'light'){
			$text_color = ' light-style';
		} else {
			$text_color = ' dark-text-color';
		}

		if ($bg_color != ''){
			if ( substr($bg_color, 1) == '#' ){
				$bg_color = substr($bg_color, 0, 1);
			}
			$wave_color = $bg_color;
			$bg_color = 'background-color:'.$bg_color.';';
		}

		if ($waved_border_top == "yes" || $waved_border_bottom == "yes"){
			$waved_border = " waved-border";
			?>

			<style type="text/css">
				<?php
				if ( $waved_border_top == "yes" && $waved_border_bottom != "yes" ) {
					echo "." . $unique_class .":before";
				} else if ( $waved_border_top != "yes" && $waved_border_bottom == "yes" ){
					echo "." . $unique_class .":after";
				} else if ( $waved_border_top == "yes" && $waved_border_bottom == "yes" ) {
					echo "." . $unique_class .":before,";
					echo "." . $unique_class .":after";
				}
				?>{background-image:url("data:image/svg+xml;utf8,<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 19 14' width='19' height='14' enable-background='new 0 0 19 14' xml:space='preserve' preserveAspectRatio='none slice'><g><path fill='<?php echo $wave_color; ?>' d='M0,0c4,0,6.5,5.9,9.5,5.9S15,0,19,0v7H0V0z'/><path fill='<?php echo $wave_color; ?>' d='M19,14c-4,0-6.5-5.9-9.5-5.9S4,14,0,14l0-7h19V14z'/></g></svg>")}
			</style>

		<?php }

		if ($image != ''){
			if( strpos($image, "http" ) !== false){
				// Image URL
			} else {
				// Image ID
				$image = wp_get_attachment_image_src($image, 'full');
				$image = $image[0];
			}
			$image = 'background-image:url('.$image.');';
			$img_to_print = $image;
		}

		if ($parallax == 'yes'){
			$parallax = " parallax";
			$img_to_print = "";
		} else {
			$parallax = "";
		}

		if ($scissors_icon == 'yes'){
			$scissors_icon = '<i class="hb-scissors icon-scissors"></i>';
		} else {
			$scissors_icon = "";
		}

		if ($bg_video_poster != ''){
			if( strpos($bg_video_poster, "http" ) !== false){
				// Image URL
			} else {
				// Image ID
				$bg_video_poster = wp_get_attachment_image_src($image, 'full');
				$bg_video_poster = $bg_video_poster[0];
			}
			$bg_video_poster = ' poster="'.$bg_video_poster.'"';
		}

		if ($bg_video_ogv != ''){
			if( strpos($bg_video_ogv, "http" ) !== false){
				// Video URL
			} else {
				// Video ID
				$bg_video_ogv = wp_get_attachment_url( $bg_video_ogv );
			}
			$bg_video_ogv = '<source src="' . $bg_video_ogv . '" type="video/ogg">';
		}

		if ($overlay != 'yes'){
			$overlay = ' no-overlay';
		} else {
			$overlay = '';
		}

		if ($bg_video_mp4 != ''){

			if( strpos($bg_video_mp4, "http" ) !== false){
				// Video URL
			} else {
				// Video ID
				$bg_video_mp4 = wp_get_attachment_url( $bg_video_mp4 );
			}

			$bg_video_mp4 = '
			<div class="video-wrap">
				<video class="hb-video-element"'.$bg_video_poster.' autoplay loop="loop" muted="muted">
					<source src="'.$bg_video_mp4.'" type="video/mp4">
					'.$bg_video_ogv.'
				</video>
				<div class="video-overlay'.$overlay.'"></div>
			</div>';
		}

		if ( $margin_top != '' )
		{
			if ( is_numeric ( $margin_top ) ) $margin_top .= 'px';
			$margin_top = 'margin-top:' . $margin_top . ';';
		}

		if ( $margin_bottom != '' ) {
			if ( is_numeric ( $margin_bottom ) ) $margin_bottom .= 'px';
			$margin_bottom = 'margin-bottom:' . $margin_bottom . ';';
		}

		if ( $padding_top != '' ) {
			if ( is_numeric ( $padding_top ) ) $padding_top .= 'px';
			$padding_top = 'padding-top:' . $padding_top . ';';
		}

		if ( $padding_bottom != '' ) {
			if ( is_numeric ( $padding_bottom ) ) $padding_bottom .= 'px';
			$padding_bottom = 'padding-bottom:' . $padding_bottom . ';';
		}


		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($id != ''){
			$id = ' id="'.$id.'"';
		}

		// OUTPUT BUILD
		if ($background_type == 'video'){
			$output .= '<div class="fw-section video-fw-section '.$unique_class.$border.$text_color.$waved_border.$class.'" style="'.$bg_color.$margin_top.$margin_bottom.$padding_top.$padding_bottom.$waved_css.'"'.$id.'>';
			$output .= $scissors_icon;
			$output .= '<div class="row video-content">';
			$output .= '<div class="col-12 nbm">';
			$output .= do_shortcode($content);
			$output .= '</div>';
			$output .= '</div>';
			$output .= $bg_video_mp4;
			$output .= '</div>';
		} else {
			if ($background_type == 'texture'){
				$background_texture = " background-texture";
			}

			if ($background_type == 'color'){
				$image="";
			}
			$output .= '<div class="fw-section '.$unique_class.$border.$background_texture.$overlay.$text_color.$waved_border.$class.'" style="'.$bg_color.$img_to_print.$margin_top.$margin_bottom.$padding_top.$padding_bottom.$waved_css.'"'.$id.'>';
			$output .= $scissors_icon;
			$output .= '<div class="row fw-content-wrap">';
			$output .= '<div class="col-12 nbm">';
			$output .= do_shortcode($content);
			$output .= '</div>';
			$output .= '</div>';
			$output .='<div class="video-overlay'.$overlay.'"></div>';

			if ( $parallax != '' && $image != '' ){
				$output .= '<div class="hb-parallax-wrapper" style="'.$image.'"></div>';
			}

			$output .= '</div>';
		}

		return $output;
	}
}
add_shortcode('fullwidth_section', 'hb_fw_section');

/* ONE PAGE SECTION
-------------------------------------------------- */
if ( !function_exists('hb_onepage_section')) {
	function hb_onepage_section($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'background_type' => 'color',
			'border' => '',
			'text_color' => 'dark',
			'bg_color' => '',
			'image' => '',
			'parallax' => '',
			'scissors_icon' => '',
			'bg_video_mp4' => '',
			'bg_video_ogv' => '',
			'bg_video_poster' => '',
			'overlay' => '',
			'margin_top' => '',
			'margin_bottom' => '',
			'padding_top' => '30',
			'padding_bottom' => '30',
			'class' => '',
			'id' => '',
			'name' => ''
		), $params));
		
		$output = "";
		$img_to_print = "";

		$background_texture = "";

		if ($border == 'yes'){
			$border = ' with-border';
		} else {
			$border = ' without-border';
		}

		if ($text_color == 'light'){
			$text_color = ' light-style';
		} else {
			$text_color = ' dark-text-color';
		}

		if ($bg_color != ''){
			if ( substr($bg_color, 1) == '#' ){
				$bg_color = substr($bg_color, 0, 1);
			}
			$bg_color = 'background-color:'.$bg_color.';';
		}

		if ($image != ''){
			if( strpos($image, "http" ) !== false){
				// Image URL
			} else {
				// Image ID
				$image = wp_get_attachment_image_src($image, 'full');
				$image = $image[0];
			}
			$image = 'background-image:url('.$image.');';
			$img_to_print = $image;
		}

		if ($parallax == 'yes'){
			$parallax = " parallax";
			$img_to_print = "";
		} else {
			$parallax = "";
		}

		if ($scissors_icon == 'yes'){
			$scissors_icon = '<i class="hb-scissors icon-scissors"></i>';
		} else {
			$scissors_icon = "";
		}

		if ($bg_video_poster != ''){
			if( strpos($bg_video_poster, "http" ) !== false){
				// Image URL
			} else {
				// Image ID
				$bg_video_poster = wp_get_attachment_image_src($image, 'full');
				$bg_video_poster = $bg_video_poster[0];
			}
			$bg_video_poster = ' poster="'.$bg_video_poster.'"';
		}

		if ($bg_video_ogv != ''){
			if( strpos($bg_video_ogv, "http" ) !== false){
				// Video URL
			} else {
				// Video ID
				$bg_video_ogv = wp_get_attachment_url( $bg_video_ogv );
			}
			$bg_video_ogv = '<source src="' . $bg_video_ogv . '" type="video/ogg">';
		}

		if ($overlay != 'yes'){
			$overlay = ' no-overlay';
		} else {
			$overlay = '';
		}

		if ($bg_video_mp4 != ''){

			if( strpos($bg_video_mp4, "http" ) !== false){
				// Video URL
			} else {
				// Video ID
				$bg_video_mp4 = wp_get_attachment_url( $bg_video_mp4 );
			}

			$bg_video_mp4 = '
			<div class="video-wrap">
				<video class="hb-video-element"'.$bg_video_poster.' autoplay loop="loop" muted="muted">
					<source src="'.$bg_video_mp4.'" type="video/mp4">
					'.$bg_video_ogv.'
				</video>
				<div class="video-overlay'.$overlay.'"></div>
			</div>';
		}

		if ( $margin_top != '' )
		{
			if ( is_numeric ( $margin_top ) ) $margin_top .= 'px';
			$margin_top = 'margin-top:' . $margin_top . ';';
		}

		if ( $margin_bottom != '' ) {
			if ( is_numeric ( $margin_bottom ) ) $margin_bottom .= 'px';
			$margin_bottom = 'margin-bottom:' . $margin_bottom . ';';
		}

		if ( $padding_top != '' ) {
			if ( is_numeric ( $padding_top ) ) $padding_top .= 'px';
			$padding_top = 'padding-top:' . $padding_top . ';';
		}

		if ( $padding_bottom != '' ) {
			if ( is_numeric ( $padding_bottom ) ) $padding_bottom .= 'px';
			$padding_bottom = 'padding-bottom:' . $padding_bottom . ';';
		}

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($id != ''){
			$id = ' id="'.$id.'"';
		}

		if ($name != ''){
			$name = ' data-title="'.$name.'"';
		}

		// OUTPUT BUILD
		if ($background_type == 'video'){
			$output .= '<div class="fw-section hb-one-page-section video-fw-section'.$border.$text_color.$class.'" style="'.$bg_color.$margin_top.$margin_bottom.$padding_top.$padding_bottom.'"'.$name.$id.'>';
			$output .= $scissors_icon;
			$output .= '<div class="row video-content">';
			$output .= '<div class="col-12 nbm">';
			$output .= do_shortcode($content);
			$output .= '</div>';
			$output .= '</div>';
			$output .= $bg_video_mp4;
			$output .= '</div>';
		} else {
			if ($background_type == 'texture'){
				$background_texture = " background-texture";
			}

			if ($background_type == 'color'){
				$image="";
			}
			$output .= '<div class="fw-section hb-one-page-section'.$border.$background_texture.$overlay.$text_color.$class.'" style="'.$bg_color.$img_to_print.$margin_top.$margin_bottom.$padding_top.$padding_bottom.'"'.$name.$id.'>';
			$output .= $scissors_icon;
			$output .= '<div class="row fw-content-wrap">';
			$output .= '<div class="col-12 nbm">';
			$output .= do_shortcode($content);
			$output .= '</div>';
			$output .= '</div>';
			$output .='<div class="video-overlay'.$overlay.'"></div>';

			if ( $parallax != '' && $image != '' ){
				$output .= '<div class="hb-parallax-wrapper" style="'.$image.'"></div>';
			}

			$output .= '</div>';
		}

		return $output;
	}
}
add_shortcode('onepage_section', 'hb_onepage_section');

/* SOCIAL LIST
-------------------------------------------------- */
if ( !function_exists('hb_social_list')) {
	function hb_social_list($params = array(), $content = null) {
		
		extract(shortcode_atts(array(   
			'size' => 'small',
			'style' => 'dark',
			'new_tab' => 'no',
			'animation' => '',
			'animation_delay' => '',
			'class' => '',
			'twitter' => '',
			'facebook' => '',
			'skype' => '',
			'instagram' => '',
			'pinterest' => '',
			'google_plus' => '',
			'dribbble' => '',
			'soundcloud' => '',
			'youtube' => '',
			'vimeo' => '',
			'flickr' => '',
			'tumblr' => '',
			'yahoo' => '',
			'foursquare' => '',
			'blogger' => '',
			'wordpress' => '',
			'lastfm' => '',
			'github' => '',
			'linkedin' => '',
			'yelp' => '',
			'forrst' => '',
			'deviantart' => '',
			'stumbleupon' => '',
			'delicious' => '',
			'reddit' => '',
			'xing' => '',
			'behance' => '',
			'vk' => '',
			'twitch' => '',
			'sn500px' => '',
			'weibo' => '',
			'tripadvisor' => '',
			'envelop' => '',
			'feed_2' => '',
			'custom_url' => '',
		), $params));

		$all_socials = array(
			'twitter',
			'facebook',
			'skype',
			'instagram',
			'pinterest',
			'google_plus',
			'dribbble',
			'soundcloud',
			'youtube',
			'vimeo',
			'flickr',
			'tumblr',
			'yahoo',
			'foursquare',
			'blogger',
			'wordpress',
			'lastfm',
			'github',
			'linkedin',
			'yelp',
			'forrst',
			'deviantart',
			'stumbleupon',
			'delicious',
			'reddit',
			'xing',
			'behance',
			'vk',
			'twitch',
			'sn500px',
			'weibo',
			'tripadvisor',
			'envelop',
			'feed_2',
			'custom_url'
		);

		if ( !$style ) $style = "dark";

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ( $new_tab == "yes" )
			$new_tab = "_blank";
		else
			$new_tab = "_self";

		if ( $size != "" ) 
			$size = " " . $size;

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = "";
		$output .= '<div class="shortcode-wrapper shortcode-social-icons">';
		$output .= '<ul class="social-icons ' . $style . $size . $animation . $class . '"' . $animation_delay . '>';

		foreach ($all_socials as $social_network) {
			if ( $$social_network ) {
				if ($social_network == 'google_plus') {
					$new_soc_net = 'google-plus';
				} elseif ($social_network == 'custom_url' || $social_network == 'custom-url') {
					$new_soc_net = 'link-5';
				} elseif ($social_network == 'feed_2') {
					$new_soc_net = 'feed-2';
				} else {
					$new_soc_net = $social_network;
				}
				
				if ( $new_soc_net != 'behance' && $new_soc_net != 'vk' && $new_soc_net != 'sn500px' && $new_soc_net != 'twitch' && $new_soc_net != "weibo" && $new_soc_net != "tripadvisor" ){
					$output .= '<li class="' . $new_soc_net . '"><a href="' . $$social_network . '" target="' . $new_tab . '"><i class="hb-moon-' . $new_soc_net . '"></i><i class="hb-moon-' . $new_soc_net . '"></i></a></li>';
				} else {
					$output .= '<li class="' . $new_soc_net . '"><a href="' . $$social_network . '" target="' . $new_tab . '"><i class="icon-' . $new_soc_net . '"></i><i class="icon-' . $new_soc_net . '"></i></a></li>';
				}
			}
		}
		$output .= '</ul>';
		$output .= '</div>';
		return $output;
	}
}
add_shortcode('social_icons', 'hb_social_list');

/* CIRCLE CHART
-------------------------------------------------- */
if ( !function_exists('hb_circle_chart')) {
	function hb_circle_chart($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'type' => 'with-icon',
			'color' => '#336699',
			'percent' => '60',
			'icon' => '',
			'text' => '',
			'caption' => '',
			'size' => '220',
			'weight' => '3',
			'track_color' => '#e1e1e1',
			'animation_speed' => '1000',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$to_return = "";

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($size != ''){
			if ( substr($size, -2) == 'px' ){
				$size = substr($size, 0, -2);
			}
		} else {
			$size = '220';
		}


		if ($percent != ''){
			if ( substr($percent, -1) == '%' ){
				$percent = substr($percent, 0, -1);
			}
		} else {
			$percent = 60;
		}

		// If with-percent or unknown
		if ($type != 'with-text' && $type != 'with-icon'){
			$to_return = '<div class="chart-percent"><span>'.$percent.'</span>%</div>';
		}

		if ($type == 'with-text'){
			$to_return = '<span class="chart-custom-text">'.$text.'</span>';
		}

		if ($type == 'with-icon'){
			if ($icon != ''){
				$to_return = '<i style="line-height:'.$size.'px; font-size:43px" class="'.$icon.'"></i>';
			} else {
				$to_return = '<i style="line-height:'.$size.'px; font-size:43px" class="hb-moon-brain"></i>';
			}
		}

		if ($caption != ''){
			$caption = '<div class="hb-chart-desc">'.$caption.'</div>';
		}

		$output = '<div class="shortcode-wrapper shortcode-circle-chart clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="hb-chart" data-percent="'.$percent.'" data-barColor="'.$color.'" data-trackColor="'.$track_color.'" data-lineWidth="'.$weight.'" data-barSize="'.$size.'" data-animation-speed="'.$animation_speed.'">';

		$output .= $to_return;	

		$output .= '</div>';
		$output .= $caption;
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('circle_chart', 'hb_circle_chart');

/* GOOGLE MAP
-------------------------------------------------- */
if ( !function_exists('hb_map_embed_shortcode')) {
	function hb_map_embed_shortcode($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'latitude' => '48.856614',
			'longitude' => '2.352222',
			'zoom' => '16',
			'from_to' => '',
			'custom_pin' => '',
			'height' => '350',
			'styled' => 'yes',
			'border' => 'yes',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$classes = "map-wrapper shadow";

		if ( $border == "yes" ) 
			$classes .= " bordered-wrapper";

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ( $custom_pin == "" )
			$custom_pin = hb_options('hb_custom_marker_image'); 
		else if ( is_numeric($custom_pin)) {
			$custom_pin = wp_get_attachment_image_src ( $custom_pin, 'full');
			$custom_pin = $custom_pin[0];
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$data_show_location = 0;

		if ( $from_to == "yes" ) { 
			$latitude = hb_options('hb_map_latitude');
			$longitude = hb_options('hb_map_longitude');
			$data_show_location = "-1";
		}


		$style = "";
		if ( $height != "" ) 
			$style = ' style="height:' . $height . 'px;"';

		$output = '<div class="shortcode-wrapper shortcode-map-embed clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="' . $classes . '">';
		$output .= '<div class="hb-gmap-map" data-api-key="' . hb_options('hb_gmap_api_key') . '" data-show-location="' . $data_show_location . '" data-map-level="' . $zoom . '" data-map-lat="' . $latitude . '" data-map-lng="' . $longitude . '" data-map-img="' . $custom_pin . '" data-overlay-color="';
		
			if ( hb_options('hb_enable_map_color') && $styled != 'no' )	
			{ 
				$output .= hb_options('hb_map_focus_color');
			} 
			else { $output .= 'none'; }

		$output .= '"' . $style . '></div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('map_embed', 'hb_map_embed_shortcode');

/* BUTTON
-------------------------------------------------- */
if ( !function_exists('hb_button_shortcode')) {
	function hb_button_shortcode($params = array()) {
		extract(shortcode_atts(array(   
			'icon' => '',
			'special_style' => 'no',
			'color' => 'default',
			'size' => 'default',
			'three_d' => 'no',
			'title' => '',
			'link' => '',
			'border_radius' => '2',
			'icon_position' => 'left',
			'new_tab' => 'no',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$classes = "hb-button";
		if ( $special_style == "yes" && $icon != "" && $icon_position != 'push_down' ) {
			$classes .= " special-icon";
		}
		if ( $color != "" ) {
			$classes .= " hb-" . $color; 
		}
		if ( $size != "" ) {
			$classes .= " hb-" . $size . "-button";
		}
		if ( $three_d == "no" ) {
			$classes .= " no-three-d";
		}
		if ($animation != ''){
			$classes .= ' hb-animate-element ' . $animation;
		}

		if ($border_radius != ''){
			if ( substr($border_radius, -2) == 'px' ){
				$border_radius = substr($border_radius, 0, -2);
			}

			if ($border_radius > 100){
				$border_radius = 100;
			} else if ( $border_radius < 0 ){
				$border_radius = 0;
			}

			$border_radius = ' style="-webkit-border-radius: '.$border_radius.'px;-moz-border-radius: '.$border_radius.'px;border-radius: '.$border_radius.'px;"';
		}


		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( $new_tab == "yes" ) 
			$new_tab = "_blank";
		else
			$new_tab = "_self";

		$output = "";

		if ($class != ''){
			$output .= '<div class="'.$class.'">';
		}

		if ( $icon_position == 'push_down' ){
			$output .= '<a href="'. $link .'" class="'. $classes .' hb-push-button" target="' . $new_tab . '"' . $animation_delay . $border_radius . '>';
			if ($icon != ''){
				$output .= '<span class="hb-push-button-icon"><i class="'. $icon .'"></i></span>';
			}
			$output .= '<span class="hb-push-button-text">'.$title.'</span></a>';

		} else {
			$output .= '<a href="' . $link . '" class="' . $classes . '" target="' . $new_tab . '"' . $animation_delay . $border_radius . '>';
			if ($icon != ''){
				$output .= '<i class="' . $icon . '"></i>';
			}
			$output .=  $title . '</a>';
		}

		if ($class != ''){
			$output .= '</div>';
		}

		return $output;
	}
}
add_shortcode('button', 'hb_button_shortcode');

/* READ MORE BUTTON
-------------------------------------------------- */
if ( !function_exists('hb_read_more_shortcode')) {
	function hb_read_more_shortcode($params = array()) {
		extract(shortcode_atts(array(   
			'title' => '',
			'link' => '',
			'new_tab' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$classes = "simple-read-more";

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ( $new_tab == "yes" ) 
			$new_tab = "_blank";
		else
			$new_tab = "_self";

		$output = "";

		if ($class != ''){
			$output .= '<div class="'.$class.'">';
		}

		$output .= '<a href="' . $link . '" class="' . $classes . '" target="' . $new_tab . '"' . $animation_delay . '>';
		if ($icon != ''){
			$output .= '<i class="' . $icon . '"></i>';
		}
		$output .=  $title . '</a>';

		if ($class != ''){
			$output .= '</div>';
		}

		return $output;
	}
}
add_shortcode('read_more', 'hb_read_more_shortcode');

/* MODAL WINDOW
-------------------------------------------------- */
if ( !function_exists('hb_modal_window')) {
	function hb_modal_window($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'title' => '',
			'invoke_title' => '',
			'id' => '',
			'show_on_load' => 'yes'
		), $params));

		$id_flag = false;

		$output_footer = "";

		if ($id != ''){
			$id_flag = true;
		} else {
			$digits = 4;
			$unique_id = rand(pow(10, $digits-1), pow(10, $digits)-1);
			$id = 'hb-modal-' . $unique_id;
			$id_flag = false;
		}

		if ($show_on_load == 'yes'){
			$show_on_load = ' modal-show-on-load';
		} else {
			$show_on_load = '';
		}

		if ($title != ''){
			$title = '<div class="hb-box-cont-header">'.$title.'<a href="#" class="close-modal" data-close-id="'.$id.'"><i class="hb-moon-close-2"></i></a></div>';
		} else {
			$title = '<div class="hb-box-cont-header">&nbsp;<a href="#" class="close-modal" data-close-id="'.$id.'"><i class="hb-moon-close-2"></i></a></div>';
		}

		$output = "";

		// Modal Invoker
		if ($invoke_title != ''){
			$output .= '<a href="#" class="modal-open hb-button" data-modal-id="'.$id.'">'.$invoke_title.'</a>';
		}

		// Modal
		$output_footer .= '<div class="crop-here"><div class="hb-modal-window'. $show_on_load .'" id="'.$id.'">';

		$output_footer .= '<div class="hb-box-cont clearfix">';
		$output_footer .= $title;
		
		$output_footer .= '<div class="hb-box-cont-body">';
		$output_footer .= do_shortcode($content);
		$output_footer .= '</div>'; // END body
		
		$output_footer .= '</div>'; // END cont
		$output_footer .= '</div>'; // END crop
		$output_footer .= '</div>'; // END

		add_action('wp_footer', create_function( '', 'echo(\''.$output_footer.'\' );' ), 100, 0);

		return $output;
	}
}
add_shortcode('modal_window', 'hb_modal_window');

/* CALLOUT
-------------------------------------------------- */
if ( !function_exists('hb_callout')) {
	function hb_callout($params = array(), $content = null) {
		extract(shortcode_atts(array(   
			'title' => '',
			'link' => '#',
			'new_tab' => 'no',
			'icon' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$with_button="clear-r-margin";

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($icon != ''){
			$icon = '<i class="' . $icon . '"></i>';
		}

		if ($new_tab == 'yes'){
			$new_tab = ' target="_blank"';
		}

		if ($title != '' || $icon != ''){
			$title = '<a href="'.$link.'" class="hb-button"'.$new_tab.'>'.$icon.$title.'</a>';
			$with_button="";
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = '<div class="shortcode-wrapper shortcode-callout clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="hb-callout-box"><h3 class="'.$with_button.'">'.do_shortcode($content).'</h3>'.$title.'</div>';
		$output .= '</div>';

		return $output;
	}
}
add_shortcode('callout', 'hb_callout');

/* CONTENT BOX
-------------------------------------------------- */
if ( !function_exists('hb_content_box')) {
	function hb_content_box($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'type' => 'with-header',
			'title' => '',
			'icon' => '',
			'color' => '',
			'text_color' => 'dark',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));
		    
		if ( $class != '' ){
			$class = ' ' . $class;
		}

		$hex_color='';

		if ($color != 'default' && $color != ''){
			if ( $color[0] == '#' ){
				$hex_color = ' style="background-color: '.$color.'"'; // hex color specified, hidden feature
				$color = "";
			} else {
				$color = ' ' . $color; // alt-color class
			}
		} else {
			$color = "";
		}

		if ($icon != ''){
			$icon = '<i class="' . $icon . '"></i>';
		}

		if ($title != ''){
			$title = $icon . $title;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($text_color == 'light'){
			$text_color = ' light-style';
		} else {
			$text_color = "";
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		$output = '<div class="shortcode-wrapper shortcode-content-box clearfix'.$class.$animation.'"'.$animation_delay.'>';
		$output .= '<div class="hb-box-cont'.$color.$text_color.'"'.$hex_color.'>';

		// BEGIN CONTENT HEADER
		if ($type != 'without-header'){
			$output .= '<div class="hb-box-cont-header">';
			$output .= $title;
			$output .= '</div>';
		}
		// END CONTENT HEADER

		// BEGIN CONTENT BODY
		$output .= '<div class="hb-box-cont-body">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		// END CONTENT BODY

		$output .= '</div>'; // END .hb-box-cont
		$output .= '</div>'; // END .shortcode-wrapper

		return $output;
	}  
}
add_shortcode('content_box', 'hb_content_box');

/* SITEMAP
-------------------------------------------------- */
if ( !function_exists('hb_sitemap')) {
	function hb_sitemap($params = array()) {  

		extract(shortcode_atts(array(   
			'depth' => 2,
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}
		    
		$output = '<div class="shortcode-wrapper shortcode-sitemap clearfix'.$class.'">';
		$output .= '<div class="row">';

		$output .= '<div class="col-4">';
			$output .= '<h3>'.__("Pages", "hbthemes").'</h3>';
			$page_list = wp_list_pages("title_li=&depth=$depth&sort_column=menu_order&echo=0");  
			
			if ($page_list != '') {  
				$output .= '<ul class="hb-ul-list special-list">'.$page_list.'</ul>';  
			}
		$output .= '</div>';
		        
		$output .= '<div class="col-4">';	
			$output .= '<h3>'.__("Posts", "hbthemes").'</h3>';
			        	  
			$post_list = wp_get_archives('type=postbypost&limit=20&echo=0');
			if ($post_list != '') {  
				$output .= '<ul class="hb-ul-list special-list">'.$post_list.'</ul>';  
			}	  		
		$output .= '</div>';
		        	
		$output .= '<div class="col-4">';      	
			$output .= '<h3>'.__("Categories", "hbthemes").'</h3>';
			        	  
			$category_list = wp_list_categories('sort_column=name&title_li=&depth=1&number=10&echo=0');
			if ($category_list != '') {  
				$output .= '<ul class="hb-ul-list special-list">'.$category_list.'</ul>';  
			}
		$output .= '</div>';	
		        

		$output .= '<div class="col-4">';
			$output .= '<h3>'.__("Archives", "hbthemes").'</h3>';
			        	  
			$archive_list =  wp_get_archives('type=monthly&limit=12&echo=0');
			if ($archive_list != '') {  
				$output .= '<ul class="hb-ul-list special-list">'.$archive_list.'</ul>';  
			} 	
		$output .= '</div>';
		    	
		$output .= '</div>'; // end row
		$output .= '</div>'; // end sitemap-wrap
		    
		return $output;      
	}  
}
add_shortcode('sitemap', 'hb_sitemap');

/* SPACER
-------------------------------------------------- */
if ( !function_exists('hb_spacer')) {
	function hb_spacer($params = array()) {

		extract(shortcode_atts(array(   
			'height' => '',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($height != ''){
			
			/*// Remove px, if entered in the attribute
			if ( substr($height, -2) == 'px' ){
				$height = substr($height, 0, -2);
			}

			$height = ' style="height:'. $height .'px;"';
			*/
			if ( is_numeric( $height ) ) $height .= 'px';
			$height = ' style="height:'. $height .';"';
		}

		$output = '<div class="shortcode-wrapper shortcode-spacer clearfix' . $class . '">';
		$output .= '<div class="spacer"'.$height.'></div>';
		$output .= '</div>';
		
		return $output;  
	}
}
add_shortcode('spacer', 'hb_spacer');

/* TOOLTIP
-------------------------------------------------- */
if ( !function_exists('hb_tooltip')) {
	function hb_tooltip($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'text' => '',
			'position' => 'top',
			'class' => ''
		), $params));

		if ( $class != '' ){
			$class = ' class="' . $class . '"';
		}

		if ($text == ''){
			$text = "Tooltip Title";
		}

		$output = '<span rel="tooltip" data-original-title="'.$text.'" data-placement="'.$position.'"'.$class.'>';
		$output .= do_shortcode($content);
		$output .= '</span>';

		return $output;
	}
}
add_shortcode('tooltip', 'hb_tooltip');

/* DROPCAP
-------------------------------------------------- */
if ( !function_exists('hb_dropcap')) {
	function hb_dropcap($params = array(), $content = null) {

		extract(shortcode_atts(array(
			'style' => '',
			'class' => ''
		), $params));

		if ( $style ) $style = ' ' . $style;

		$output = '<span class="dropcap' . $style . '">' . do_shortcode($content) . '</span>';
		
		return $output;  
	}
}
add_shortcode('dropcap', 'hb_dropcap');

/* CLEAR
-------------------------------------------------- */
if ( !function_exists('hb_clear')) {
	function hb_clear($params = array()) {

		extract(shortcode_atts(array(
		), $params));

		$output = '<div class="clear"></div>';
		
		return $output;  
	}
}
add_shortcode('clear', 'hb_clear');

/* HIGHLIGHT
-------------------------------------------------- */
if ( !function_exists('hb_highlight')) {
	function hb_highlight($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'style' => 'highlight',
			'class' => ''
		), $params));

		if ($style == 'alt'){
			$style = 'highlight alt';
		} else {
			$style = 'highlight';
		}

		$output = '<span class="' .$style. ' ' .$class. '">';
		$output .= do_shortcode($content);
		$output .= '</span>';

		return $output;
	}
}
add_shortcode('highlight', 'hb_highlight');

/* INFO MESSAGE
-------------------------------------------------- */
if ( !function_exists('hb_infomessage')) {
	function hb_infomessage($params = array(), $content = null) {

		extract(shortcode_atts(array(   
			'style' => 'info',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$icon_html = "";

		if ($style != 'info' && $style != 'warning' && $style != 'success' && $style != 'error') {
			$style = 'info';
			$icon_html = "<i class='icon-lightbulb'></i>";
		}

		if ($style == 'info'){
			$icon_html = "<i class='icon-lightbulb'></i>";
		} else if ($style == 'error'){
			$icon_html = "<i class='hb-moon-blocked'></i>";
		} else if ($style == 'warning'){
			$icon_html = "<i class='hb-moon-warning-2'></i>";
		} else if ($style == 'success'){
			$icon_html = "<i class='hb-moon-thumbs-up-3'></i>";
		}

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = 'data-delay="' . $animation_delay . '"';
		}

		$output = '<div class="shortcode-wrapper shortcode-infomessage clearfix' . $class . '">';

		$output .= '<div class="hb-notif-box ' . $style . $animation .'" '. $animation_delay .'>';
		$output .= '<div class="message-text">';
		
		$output .= '<p>' . $icon_html . do_shortcode($content) . '</p>';

		$output .= '</div>';
		$output .= '</div>';

		$output .= '</div>';

		return $output;
	}
}
add_shortcode('info_message', 'hb_infomessage');

/* COUNTDOWN
-------------------------------------------------- */
if ( !function_exists('hb_countdown')) {
	function hb_countdown($params = array()) {

		extract(shortcode_atts(array(   
			'date' => '24 april 2016 16:00:00',
			'animation' => '',
			'animation_delay' => '',
			'aligncenter' => '',
			'class' => ''
		), $params));

		if ($date == '') {
			$date = '31 december 2020 12:00:00';
		}

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ( $aligncenter == 'yes' ){
			$aligncenter = ' aligncenter';
		} else {
			$aligncenter = '';
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = 'data-delay="' . $animation_delay . '"';
		}

		$output = '<div class="shortcode-wrapper shortcode-countdown clearfix' . $aligncenter . $class . $animation .'" '. $animation_delay .'>';

		$digits = 4;
		$unique_id = rand(pow(10, $digits-1), pow(10, $digits)-1);

		$output .= '<ul id="hb-countdown-'.$unique_id.'" class="hb-countdown-unit clearfix" data-date="'.$date.'">
						<li>
							<span class="days timestamp">0</span>
							<span class="timeRef">' . __('days','hbthemes') . '</span>
						</li>
						<li>
							<span class="hours timestamp">0</span>
							<span class="timeRef">' . __('hours','hbthemes') . '</span>
						</li>
						<li>
							<span class="minutes timestamp">0</span>
							<span class="timeRef">' . __('minutes','hbthemes') . '</span>
						</li>
						<li>
							<span class="seconds timestamp">0</span>
							<span class="timeRef">' . __('seconds','hbthemes') . '</span>
						</li>
					</ul>';

		$output .= '</div>';

		return $output;
	}
}
add_shortcode('countdown', 'hb_countdown');

/* SEPARATOR
-------------------------------------------------- */
if ( !function_exists('hb_separator')) {
	function hb_separator($params = array()) {

		extract(shortcode_atts(array(   
			'type' => 'default',
			'scissors_icon' => '',
			'go_to_top' => '',
			'margin_top' => '',
			'margin_bottom' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$separator_icon = "";
		$gototop = "";
		$style_html = "";

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ( $type == 'default' ){
			$type = "hb-separator";
		} else if ( $type == 'default-double' ){
			$type = "hb-separator double-border";
		} else if ( $type == 'dashed' ){
			$type = "hb-separator dashed-border";
		} else if ( $type == 'dashed-double' ) {
			$type = "hb-separator double-border dashed-border";
		} else if ( $type == 'small' ){
			$type = "hb-separator-25";
		} else if ( $type == 'small-break') {
			$type = "hb-small-break";
		} else if ($type == 'hb-fw-separator'){
			$type = "hb-fw-separator";
		} else if ($type == 'hb-fw-dashed'){
			$type = "hb-fw-separator dashed-border";
		}
		 else {
			$type = 'hb-separator';
		}

		if ($scissors_icon == 'yes'){
			$separator_icon = '<i class="hb-scissors icon-scissors"></i>';
		}

		if ($go_to_top == 'yes'){
			$gototop = '<a href="#" class="go-to-top">' . __("Go to top","hbthemes") . '</a>';
		}

		if ( $margin_top != '' )
		{
			if ( is_numeric ( $margin_top ) ) $margin_top .= 'px';
			$margin_top = 'margin-top:' . $margin_top . ';';
		}

		if ( $margin_bottom != '' ) {
			if ( is_numeric ( $margin_bottom ) ) $margin_bottom .= 'px';
			$margin_bottom = 'margin-bottom:' . $margin_bottom . ';';
		}

		if ($margin_top != '' || $margin_bottom != ''){
			$style_html = ' style="' . $margin_top . $margin_bottom . '"';
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = 'data-delay="' . $animation_delay . '"';
		}

		if ($type != 'hb-fw-separator' && $type != 'hb-fw-separator dashed-border'){

			$output = '<div class="shortcode-wrapper shortcode-separator clearfix' . $class . $animation .'" '. $animation_delay .'>';
			$output .= '<div class="' .$type. '" '. $style_html .'>'; 
			$output .= $separator_icon . $gototop;
			$output .= '</div>';
			$output .= '</div>';

		} else if ($type == 'hb-fw-separator') {
			$output = '<div class="shortcode-wrapper shortcode-separator clearfix' . $class . $animation .'" '. $animation_delay .'>';
			$output .= '<div class="hb-separator extra-space"'. $style_html .'>'. $separator_icon . $gototop . '<div class="hb-fw-separator"></div></div>';
			$output .= '</div>';

		} else if ($type == 'hb-fw-separator dashed-border'){
			$output = '<div class="shortcode-wrapper shortcode-separator clearfix' . $class . $animation .'" '. $animation_delay .'>';
			$output .= '<div class="hb-separator extra-space"'. $style_html .'>'. $separator_icon . $gototop . '<div class="hb-fw-separator dashed-border"></div></div>';
			$output .= '</div>';
		}

		return $output;
	}
}
add_shortcode('separator', 'hb_separator');

/* VIDEO EMBED
-------------------------------------------------- */
if ( !function_exists('hb_video_embed')) {
	function hb_video_embed($params = array()) {

		extract(shortcode_atts(array(  
			'embed_style' => 'default',	
			'url' => '',
			'border' => '',
			'width' => '',
			'animation' => '',
			'animation_delay' => '',
			'class' => ''
		), $params));

		$border_html = "";

		if ( $class != '' ){
			$class = ' ' . $class;
		}

		if ($width != ''){
			
			/*// Remove px, if entered in the attribute
			if ( substr($width, -2) == 'px' ){
				$width = substr($height, 0, -2);
			}

			$width = ' style="width:'. $width .'px;"';
			*/
			if ( is_numeric( $width ) ) $width .= 'px';
			$width = ' style="width:'. $width .';"';
		}

		if ($width != ''){
			$embed_code = wp_oembed_get($url, array('width'=>$width));
		} else {
			$embed_code = wp_oembed_get($url);
		}
		if (!$embed_code){
			$embed_code = __('Failed to load media. URL not valid. Please check <a href="http://codex.wordpress.org/Embeds">WordPress Codex</a>.');
		}
		if ($border == 'yes'){
			$border_html = " hb-box-frame";
		}

		if ($animation != ''){
			$animation = ' hb-animate-element ' . $animation;
		}

		if ($animation_delay != ''){
			// Remove ms or s, if entered in the attribute
			if ( substr($animation_delay, -2) == 'ms' ){
				$animation_delay = substr($animation_delay, 0, -2);
			}

			if ( substr($animation_delay, -1) == 's' ){
				$animation_delay = substr($animation_delay, 0, -1);
			}

			$animation_delay = ' data-delay="' . $animation_delay . '"';
		}

		if ($embed_style == 'in_lightbox'){
			$output = "<a href='" . $url . "' rel='prettyPhoto'><i class='hb-moon-play-2 hb-icon hb-icon-float-none hb-icon-medium hb-icon-container'></i></a>";
		} else {
			$output = '<div class="shortcode-wrapper shortcode-video fitVids clearfix' . $border_html . $class . $animation .'"'.$width. $animation_delay .'>';
			$output .= '<span>' . $embed_code . '</span>';
			$output .= '</div>';
		}
		
		return $output;  
	}
}
add_shortcode('video_embed', 'hb_video_embed');


/*	VC MAPPING
	========================================== */
	if (function_exists('wpb_map')){

		// Useful
		$script_path = get_template_directory_uri() . '/scripts/';
		
		$target_arr = array(__("Same window", "js_composer") => "_self", __("New window", "js_composer") => "_blank");
		
		$alt_colors_arr = array(
			__('None / Default', 'js_composer') => "default",
			__('Alt Color 1', 'js_composer') => "alt-color-1",
			__('Alt Color 2', 'js_composer') => "alt-color-2",
			__('Alt Color 3', 'js_composer') => "alt-color-3",
			__('Alt Color 4', 'js_composer') => "alt-color-4",
			__('Alt Color 5', 'js_composer') => "alt-color-5",
			__('Alt Color 6', 'js_composer') => "alt-color-6",
		);

		$alt_bgcolors_arr = array(
			__('None / Default', 'js_composer') => "default",
			__('Alt Color 1', 'js_composer') => "alt-color-1-bg",
			__('Alt Color 2', 'js_composer') => "alt-color-2-bg",
			__('Alt Color 3', 'js_composer') => "alt-color-3-bg",
			__('Alt Color 4', 'js_composer') => "alt-color-4-bg",
			__('Alt Color 5', 'js_composer') => "alt-color-5-bg",
			__('Alt Color 6', 'js_composer') => "alt-color-6-bg",
		);

		$add_icon = array(
			"type" => "textfield",
			"heading" => __("Icon", "js_composer"),
			"param_name" => "icon",
			"admin_label" => true,
			"description" => __("Enter a name of the icon you would like to use. Leave empty if you don't want an icon. You can find list of icons here: <a href='http://documentation.hb-themes.com/icons/' target='_blank'>Icon List</a>.
Example: hb-moon-apple-fruit", "js_composer")
		);

		$add_icon_or_char = array(
			"type" => "textfield",
			"heading" => __("Icon or Character", "js_composer"),
			"param_name" => "icon",
			"admin_label" => true,
			"description" => __("Enter a name of icon you would like to use or enter just a single character. Leave empty to exclude. You can find list of icons here: <a href='http://documentation.hb-themes.com/icons/' target='_blank'>Icon List</a>
Example: hb-moon-apple-fruit. Example for character: $", "js_composer")
		);

		$add_class = array(
			"type" => "textfield",
			"heading" => __("Extra class name", "js_composer"),
			"param_name" => "class",
			"admin_label" => true,
			"group" => "Advanced",
			"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file. You can find the list of useful class on the <a href='http://documentation.hb-themes.com/highend/#additional-classes' target='_blank'>theme documentation page.</a>", "js_composer")
		);

		$add_margin_top = array(
			"type" => "textfield",
			"heading" => __("Margin Top", "js_composer"),
			"param_name" => "margin_top",
			"group" => "Position",
			"description" => __("Enter top margin. You can use px, em, %, etc. or enter just number and it will use pixels. Leave empty for the default value.", "js_composer")
		);

		$add_margin_bottom = array(
			"type" => "textfield",
			"heading" => __("Margin Bottom", "js_composer"),
			"param_name" => "margin_bottom",
			"group" => "Position",
			"description" => __("Enter bottom margin. You can use px, em, %, etc. or enter just number and it will use pixels. Leave empty for the default value.", "js_composer")
		);

		$add_css_animation = array(
			"type" => "dropdown",
		  	"heading" => __("Entrance Animation", "js_composer"),
		  	"param_name" => "animation",
		  	"admin_label" => true,
		  	"group" => "Animation",
		  	"value" => array(__("None", "js_composer") => '', __("Fade In", "js_composer") => "fade-in", __("Scale Up", "js_composer") => "scale-up", __("Scale Down", "js_composer") => "scale-down", __("Right to Left", "js_composer") => "right-to-left", __("Left to Right", "js_composer") => "left-to-right", __("Top to Bottom", "js_composer") => "top-to-bottom", __("Bottom to Top", "js_composer") => "bottom-to-top", __("Helix", "js_composer") => "helix", __("Flip-X", "js_composer") => "flip-x",  __("Flip-Y", "js_composer") => "flip-y",  __("Spin", "js_composer") => "spin"),
		  	"description" => __("Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "js_composer")
		);

		$add_animation_delay = array(
			"type" => "textfield",
			"heading" => __("Entrance Delay", "js_composer"),
			"param_name" => "animation_delay",
			"group" => "Animation",
			"description" => __("Enter delay in miliseconds before the animation starts. Useful for creating timed animations. No need to enter ms. Eg: 300 (300 stands for 0.3 seconds)", "js_composer")
		);

		$all_socials = array(
			'twitter' => '',
			'facebook' => '',
			'skype' => '',
			'instagram' => '',
			'pinterest' => '',
			'google_plus' => '',
			'dribbble' => '',
			'soundcloud' => '',
			'youtube' => '',
			'vimeo' => '',
			'flickr' => '',
			'tumblr' => '',
			'yahoo' => '',
			'foursquare' => '',
			'blogger' => '',
			'wordpress' => '',
			'lastfm' => '',
			'github' => '',
			'linkedin' => '',
			'yelp' => '',
			'forrst' => '',
			'deviantart' => '',
			'stumbleupon' => '',
			'delicious' => '',
			'reddit' => '',
			'envelop' => '',
			'feed_2' => '',
			'custom_url' => ''
		);

		$pricing_table_items = array();
		$pricing_table_items["None"] = "";
		$pricing_query = get_posts('post_type=hb_pricing_table&status=publis&posts_per_page=-1');
		if ( !empty ($pricing_query ) ) {
			foreach ($pricing_query as $pricing_item) {
				$pricing_table_items[$pricing_item->post_title] = $pricing_item->ID;
			}
		}

		// FAQ ---------------------------------
		vc_map( array(
			"name" => __("FAQ", "js_composer"),
			"base" => "faq",
		  	"icon" => "icon-faq",
		  	"wrapper_class" => "hb-wrapper-faq",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Inserts a FAQ Module.', 'js_composer'),
		    "params"	=> array(
		        array(
					"type" => "dropdown",
					"heading" => __("Show Filter", "js_composer"),
					"param_name" => "filter",
					"value" => array(
		               	__("No", "js_composer") => 'no',
						__("Yes", "js_composer") => 'yes',
					),
					"default" => "yes",
					"description" => __("Choose in which order to show testimonials.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which faq categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show testimonials.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END TESTIMONIAL SLIDER ------------------------

		// PRICING TABLE --------------------------------
		vc_map( array(
			"name" => __("Pricing Table", "js_composer"),
			"base" => "pricing_table",
		  	"icon" => "icon-pricing-table",
		  	"wrapper_class" => "hb-wrapper-pricing-table",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Display your pricing tables.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Pricing Item", "js_composer"),
					"param_name" => "pricing_item",
					"value" => $pricing_table_items,
					"admin_label" => true,
					"description" => __("Choose the style of your pricing table.", "js_composer")
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Style", "js_composer"),
					"param_name" => "style",
					"value" => array(
						__("Standard", "js_composer") => 'standard',
						__("Colored", "js_composer") => 'colored',
					),
					"admin_label" => true,
					"description" => __("Choose the style of your pricing table.", "js_composer"),
					"default" => 'standard'
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Columns", "js_composer"),
					"param_name" => "columns",
					"value" => array(
						__("1", "js_composer") => '1',
						__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
					),
					"admin_label" => true,
					"description" => __("Choose in how many columns to display your pricing table.", "js_composer"),
					"default" => "3",
		        ),
				$add_css_animation,
				$add_animation_delay,
				$add_class,
		    ),
		));
		// END ICON COLUMN -----------------------------

		// ICON COLUMN --------------------------------
		vc_map( array(
			"name" => __("Icon Column", "js_composer"),
			"base" => "icon_column",
		  	"icon" => "icon-icon-column",
		  	"wrapper_class" => "hb-wrapper-icon-column",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('A simple content column with icon.', 'js_composer'),
		    "params"	=> array(
		    	$add_icon_or_char,
		    	array(
					"type" => "textfield",
					"heading" => __("Link", "js_composer"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter a link for this icon. Leave empty if you do not want to use a link. Please use http:// prefix. Example: http://hb-themes.com", "js_composer")
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Open link in new tab?", "js_composer"),
					"param_name" => "new_tab",
					"value" => array(
						__("Yes", "js_composer") => 'yes',
						__("No", "js_composer") => 'no',
					),
					"admin_label" => true
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Alignment", "js_composer"),
					"param_name" => "align",
					"value" => array(
						__("Left", "js_composer") => 'left',
						__("Center", "js_composer") => 'center',
						__("Right", "js_composer") => 'right',
					),
					"admin_label" => true,
					"description" => __("Choose the alignment of the content.", "js_composer")
		        ),
			    array(
					"type" => "textfield",
					"heading" => __("Title", "js_composer"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => "My column title",
					"description" => __("Enter your icon column title. Leave empty to exclude. Example: My Feature", "js_composer")
		        ),
		        array(
			      "type" => "textarea_html",
			      "heading" => __("Content", "js_composer"),
			      "param_name" => "content",
			      "value" => __("<p>Mauris rhoncus pretium porttitor. Cras scelerisque commodo odio. Phasellus dolor enim, faucibus egestas scelerisque hendrerit, aliquet sed lorem.</p>", "js_composer")
			    ),
				$add_css_animation,
				$add_animation_delay,
				$add_class,
		    ),
		));
		// END ICON COLUMN -----------------------------

		// ICON FEATURE --------------------------------
		vc_map( array(
			"name" => __("Icon Feature", "js_composer"),
			"base" => "icon_feature",
		  	"icon" => "icon-icon-feature",
		  	"wrapper_class" => "hb-wrapper-icon-feature",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Fancy icon feature.', 'js_composer'),
		    "params"	=> array(
		    	$add_icon_or_char,
		    	array(
					"type" => "textfield",
					"heading" => __("Link", "js_composer"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter a link for this icon. Leave empty if you do not want to use a link. Please use http:// prefix. Example: http://hb-themes.com", "js_composer")
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Open link in new tab?", "js_composer"),
					"param_name" => "new_tab",
					"value" => array(
						__("Yes", "js_composer") => 'yes',
						__("No", "js_composer") => 'no',
					),
					"admin_label" => true
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Icon Position", "js_composer"),
					"param_name" => "icon_position",
					"value" => array(
						__("Center", "js_composer") => 'center',
						__("Left", "js_composer") => 'left',
						__("Right", "js_composer") => 'right',
					),
					"admin_label" => true,
					"description" => __("Choose where will be icon positioned.", "js_composer")
		        ),
			    array(
					"type" => "dropdown",
					"heading" => __("Border around icon?", "js_composer"),
					"param_name" => "border",
					"value" => array(
						__("Yes", "js_composer") => 'yes',
						__("No", "js_composer") => 'no',
					),
					"admin_label" => true,
					"description" => __("Display border around icon with effect on hover", "js_composer")
		        ),

			    array(
					"type" => "textfield",
					"heading" => __("Title", "js_composer"),
					"param_name" => "title",
					"admin_label" => true,
					"description" => __("Enter your icon box title. Leave empty to exclude. Example: My Feature", "js_composer")
		        ),
		        array(
			      "type" => "textarea_html",
			      "heading" => __("Box Content", "js_composer"),
			      "param_name" => "content",
			      "value" => __("<p>Mauris rhoncus pretium porttitor. Cras scelerisque commodo odio. Phasellus dolor enim, faucibus egestas scelerisque hendrerit, aliquet sed lorem.</p>", "js_composer")
			    ),
			    array(
			      	"type" => "attach_image",
					"heading" => __("Custom Image", "js_composer"),
					"param_name" => "image",
					"value" => "",
					"description" => __("Upload custom image for this element. If this field is used, the icon will be discarded. We suggest using 64x64 pixels images.", "js_composer")
			    ),
				$add_css_animation,
				$add_animation_delay,
				$add_class,
		    ),
		));
		// END ICON FEATURE --------------------------------

		// BOX ICON ---------------------------------
		vc_map( array(
			"name" => __("Icon Box", "js_composer"),
			"base" => "icon_box",
		  	"icon" => "icon-icon-box",
		  	"wrapper_class" => "hb-wrapper-icon-box",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('A styled content box with icon.', 'js_composer'),
		    "params"	=> array(
		    	$add_icon,
		    	array(
					"type" => "textfield",
					"heading" => __("Title", "js_composer"),
					"param_name" => "title",
					"admin_label" => true,
					"description" => __("Enter your icon box title. Leave empty to exclude. Example: My Feature", "js_composer")
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Title Link", "js_composer"),
					"param_name" => "link",
					"admin_label" => true,
					"description" => __("Enter your icon box title link. Leave empty to exclude. Example: My Feature", "js_composer")
		        ),    
		        array(
		                "type" => "dropdown",
		                "heading" => __("Open links in", "js_composer"),
		                "param_name" => "new_tab",
		                "value" => array(
		                	__("Same tab", "js_composer") => 'no',
		                	__("New tab", "js_composer") => 'yes',
		                ),
		        ),
		    	array(
			      "type" => "colorpicker",
			      "heading" => __("Icon Background", "js_composer"),
			      "param_name" => "icon_color",
			      "description" => __("Choose a background color for the icon. Leave empty for default color.", "js_composer")
			    ),
			    array(
					"type" => "dropdown",
					"heading" => __("Icon Position", "js_composer"),
					"param_name" => "icon_position",
					"value" => array(
						__("Top", "js_composer") => 'top',
						__("Left", "js_composer") => 'left',
					),
					"admin_label" => true,
					"description" => __("Choose where will be icon positioned.", "js_composer")
		        ),
		        array(
			      "type" => "textarea_html",
			      "heading" => __("Box Content", "js_composer"),
			      "param_name" => "content",
			      "value" => __("<p>I am message box. Click edit button to change this text.</p>", "js_composer")
			    ),
		        array(
					"type" => "dropdown",
					"heading" => __("Content Alignment", "js_composer"),
					"param_name" => "align",
					"value" => array(
						__("Left", "js_composer") => 'left',
						__("Center", "js_composer") => 'center',
						__("Right", "js_composer") => 'right',
					),
					"admin_label" => true,
					"description" => __("Choose a content alignment.", "js_composer")
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END BOX ICON -----------------------------

		// BLOG CAROUSEL ---------------------------------
		vc_map( array(
			"name" => __("Blog Carousel", "js_composer"),
			"base" => "blog_carousel",
		  	"icon" => "icon-blog-carousel",
		  	"wrapper_class" => "hb-wrapper-blog-carousel",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Carousel with blog posts.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Visible items", "js_composer"),
					"param_name" => "visible_items",
					"admin_label" => true,
					"value" => array(
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
						__("7", "js_composer") => '7',
						__("8", "js_composer") => '8',
					),
					"description" => __("Choose how many posts are visible at a time.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "total_items",
					"admin_label" => true,
					"value" => "10",
					"description" => __("Choose how many client logos to include in the carousel. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Excerpt Length", "js_composer"),
					"param_name" => "excerpt_length",
					"admin_label" => true,
					"value" => "",
					"description" => __("Specify how many words will show in the post excerpt, enter just a number. Example: 15.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Speed", "js_composer"),
					"param_name" => "carousel_speed",
					"admin_label" => true,
					"value" => "3000",
					"description" => __("Specify the carousel speed in miliseconds, enter just a number. Example: 2000.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Auto Rotate?", "js_composer"),
					"param_name" => "auto_rotate",
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Read More Link?", "js_composer"),
					"param_name" => "read_more",
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which client categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show client logos.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END CLIENT CAROUSEL ------------------------
	
		// TEAM MEMBER BOX ---------------------------------
		vc_map( array(
			"name" => __("Team Members Box", "js_composer"),
			"base" => "team_member_box",
		  	"icon" => "icon-team-member-box",
		  	"wrapper_class" => "hb-wrapper-team-member-box",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Carousel with team members.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Columns", "js_composer"),
					"param_name" => "columns",
					"admin_label" => true,
					"value" => array(
		               	__("1", "js_composer") => '1',
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
					),
					"description" => __("Choose how many team members are visible at a time.", "js_composer"),
		        ),
		        array(
			      "type" => "dropdown",
			      "heading" => __("Box style", "js_composer"),
			      "param_name" => "style",
			      "value" => array(__('Normal', "js_composer") => "", __('Boxed', "js_composer") => "boxed" ),
			      "description" => __("Choose a style for this message.", "js_composer")
			    ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose how many team members items to include in the carousel. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which team member categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Excerpt Length", "js_composer"),
					"param_name" => "excerpt_length",
					"admin_label" => false,
					"value" => "",
					"description" => __("Specify how many words will show in the excerpt, enter just a number. Example: 15.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show team members.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END TEAM MEMBER BOX ------------------------

		// TESTIMONIAL BOX ---------------------------------
		vc_map( array(
			"name" => __("Testimonial Box", "js_composer"),
			"base" => "testimonial_box",
		  	"icon" => "icon-testimonial-box",
		  	"wrapper_class" => "hb-wrapper-testimonial-box",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Testimonial Items.', 'js_composer'),
		    "params"	=> array(
		    	array(
		            "type" => "dropdown",
		            "heading" => __("Testimonial Style", "js_composer"),
		            "param_name" => "type",
		            "admin_label" => true,
		            "value" => array(
		               	__("Normal", "js_composer") => 'normal',
						__("Large", "js_composer") => 'large',
					),
					"description" => __("Choose between a Large or Normal Testimonial Style.", "js_composer"),
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Columns", "js_composer"),
					"param_name" => "columns",
					"admin_label" => true,
					"value" => array(
		               	__("1", "js_composer") => '1',
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
					),
					"description" => __("Choose how in many columns are team members displayed.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose how many team members items to include in the carousel. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which team member categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show team members.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END TEAM MEMBER BOX ------------------------

		// TESTIMONIAL SLIDER ---------------------------------
		vc_map( array(
			"name" => __("Testimonial Slider", "js_composer"),
			"base" => "testimonial_slider",
		  	"icon" => "icon-testimonial-slider",
		  	"wrapper_class" => "hb-wrapper-testimonial-slider",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Testimonial Slider.', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "dropdown",
		            "heading" => __("Slider Type", "js_composer"),
		            "param_name" => "type",
		            "admin_label" => true,
		            "value" => array(
		               	__("Normal", "js_composer") => 'normal',
						__("Large", "js_composer") => 'large',
					),
					"description" => __("Choose between a Large or Normal Testimonial Slider.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter how many testimonials to show in the slider. Leave empty to display all testimonials. Example: 5.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which client categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Slideshow Speed", "js_composer"),
					"param_name" => "slideshow_speed",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter time in ms. This is the time an item will be visible before switching to another testimonial. Example: 5000.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Animation Speed", "js_composer"),
					"param_name" => "animation_speed",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter time in ms. This is the transition time between two testimonials. Example: 350.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show testimonials.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END TESTIMONIAL SLIDER ------------------------

		// CLIENT CAROUSEL ---------------------------------
		vc_map( array(
			"name" => __("Client Carousel", "js_composer"),
			"base" => "client_carousel",
		  	"icon" => "icon-client-carousel",
		  	"wrapper_class" => "hb-wrapper-client-carousel",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Carousel with client logos.', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "dropdown",
		            "heading" => __("Style", "js_composer"),
		            "param_name" => "style",
		            "admin_label" => true,
		            "value" => array(
		               	__("Simple", "js_composer") => 'simple',
						__("Focus", "js_composer") => 'focus',
						__("Greyscale", "js_composer") => 'greyscale',
						__("White Boxed", "js_composer") => 'simple-white',
					),
					"description" => __("Choose how the client logos are styled.", "js_composer"),
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Visible items", "js_composer"),
					"param_name" => "visible_items",
					"admin_label" => true,
					"value" => array(
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
						__("7", "js_composer") => '7',
						__("8", "js_composer") => '8',
					),
					"description" => __("Choose how many posts are visible at a time.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "total_items",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose how many client logos to include in the carousel. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Speed", "js_composer"),
					"param_name" => "carousel_speed",
					"admin_label" => true,
					"value" => "",
					"description" => __("Specify the carousel speed in miliseconds, enter just a number. Example: 2000.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Auto Rotate?", "js_composer"),
					"param_name" => "auto_rotate",
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which client categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show client logos.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
			     array(
					"type" => 'checkbox',
					"heading" => __("Open links in new tab?", "js_composer"),
					"param_name" => "new_tab",
					"value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END CLIENT CAROUSEL ------------------------

		// TEAM MEMBER CAROUSEL ---------------------------------
		vc_map( array(
			"name" => __("Team Members Carousel", "js_composer"),
			"base" => "team_carousel",
		  	"icon" => "icon-team-carousel",
		  	"wrapper_class" => "hb-wrapper-team-carousel",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Carousel with team members.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Visible items", "js_composer"),
					"param_name" => "visible_items",
					"admin_label" => true,
					"value" => array(
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
						__("7", "js_composer") => '7',
						__("8", "js_composer") => '8',
					),
					"description" => __("Choose how many team members are visible at a time.", "js_composer"),
		        ),

		        array(
			      "type" => "dropdown",
			      "heading" => __("Member Box style", "js_composer"),
			      "param_name" => "style",
			      "value" => array(__('Normal', "js_composer") => "", __('Boxed', "js_composer") => "boxed" ),
			      "description" => __("Choose a style for this message.", "js_composer")
			    ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "total_items",
					"admin_label" => true,
					"value" => "4",
					"description" => __("Choose how many team members items to include in the carousel. To get all items enter -1.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Excerpt Length", "js_composer"),
					"param_name" => "excerpt_length",
					"admin_label" => false,
					"value" => "",
					"description" => __("Specify how many words will show in the excerpt, enter just a number. Example: 15.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Speed", "js_composer"),
					"param_name" => "carousel_speed",
					"admin_label" => true,
					"value" => "5000",
					"description" => __("Specify the carousel speed in miliseconds, enter just a number. Example: 2000.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Auto Rotate?", "js_composer"),
					"param_name" => "auto_rotate",
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which team member categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show team members.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END TEAM MEMBER CAROUSEL ------------------------

		// PORTFOLIO CAROUSEL ---------------------------------
		vc_map( array(
			"name" => __("Portfolio Carousel", "js_composer"),
			"base" => "portfolio_carousel",
		  	"icon" => "icon-portfolio-carousel",
		  	"wrapper_class" => "hb-wrapper-portfolio-carousel",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Carousel with portfolio items.', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "dropdown",
		            "heading" => __("Style", "js_composer"),
		            "param_name" => "style",
		            "admin_label" => true,
		            "value" => array(
		               	__("Standard", "js_composer") => 'standard',
						__("Descriptive", "js_composer") => 'descriptive',
					),
					"description" => __("Choose how the portfolio items are styled.", "js_composer"),
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Visible items", "js_composer"),
					"param_name" => "visible_items",
					"admin_label" => true,
					"value" => array(
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
						__("7", "js_composer") => '7',
						__("8", "js_composer") => '8',
					),
					"description" => __("Choose how many posts are visible at a time.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "total_items",
					"admin_label" => true,
					"value" => "8",
					"description" => __("Choose how many portfolio items to include in the carousel. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Speed", "js_composer"),
					"param_name" => "carousel_speed",
					"admin_label" => true,
					"value" => "5000",
					"description" => __("Specify the carousel speed in miliseconds, enter just a number. Example: 2000.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Auto Rotate?", "js_composer"),
					"param_name" => "auto_rotate",
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which portfolio categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show portfolio items.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END PORTFOLIO CAROUSEL ------------------------

		// GALLERY FULLWIDTH ---------------------------------
		vc_map( array(
			"name" => __("Gallery Fullwidth", "js_composer"),
			"base" => "gallery_fullwidth",
		  	"icon" => "icon-gallery-fullwidth",
		  	"wrapper_class" => "hb-wrapper-gallery-fullwidth",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Fullwidth Gallery Section.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Columns", "js_composer"),
					"param_name" => "columns",
					"admin_label" => true,
					"value" => array(
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
					),
					"description" => __("Choose how many in how many columns to show your gallery items.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "8",
					"description" => __("Choose how many gallery items to include in the section. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which gallery categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Image Orientation", "js_composer"),
					"param_name" => "orientation",
					"admin_label" => true,
					"value" => array(
		               	__("Landscape", "js_composer") => 'landscape',
						__("Portrait", "js_composer") => 'portrait',
					),
					"description" => __("Choose orientation of the gallery thumbnails.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Image Ratio", "js_composer"),
					"param_name" => "ratio",
					"admin_label" => true,
					"value" => array(
		               	__("16:9", "js_composer") => 'ratio1',
						__("4:3", "js_composer") => 'ratio2',
						__("3:2", "js_composer") => 'ratio4',
						__("3:1", "js_composer") => 'ratio5',
						__("1:1", "js_composer") => 'ratio3',
					),
					"description" => __("Choose ratio of the gallery thumbnails.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show gallery items.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END GALLERY FULLWIDTH ------------------------

		// PORTFOLIO FULLWIDTH ---------------------------------
		if ( hb_module_enabled('hb_module_portfolio') ) {

		vc_map( array(
			"name" => __("Portfolio Fullwidth", "js_composer"),
			"base" => "portfolio_fullwidth",
		  	"icon" => "icon-portfolio-fullwidth",
		  	"wrapper_class" => "hb-wrapper-portfolio-fullwidth",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Fullwidth Portfolio Section.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Columns", "js_composer"),
					"param_name" => "columns",
					"admin_label" => true,
					"value" => array(
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
					),
					"description" => __("Choose how many in how many columns to show your portfolio items.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "8",
					"description" => __("Choose how many portfolio items to include in the section. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which portfolio categories will be shown in the section. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Image Orientation", "js_composer"),
					"param_name" => "orientation",
					"admin_label" => true,
					"value" => array(
		               	__("Landscape", "js_composer") => 'landscape',
						__("Portrait", "js_composer") => 'portrait',
					),
					"description" => __("Choose orientation of the portfolio thumbnails.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Image Ratio", "js_composer"),
					"param_name" => "ratio",
					"admin_label" => true,
					"value" => array(
		               	__("16:9", "js_composer") => 'ratio1',
						__("4:3", "js_composer") => 'ratio2',
						__("3:2", "js_composer") => 'ratio4',
						__("3:1", "js_composer") => 'ratio5',
						__("1:1", "js_composer") => 'ratio3',
					),
					"description" => __("Choose ratio of the portfolio thumbnails.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show portfolio items.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		       	$add_margin_top,
		       	$add_margin_bottom,
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		
		}
		// END PORTFOLIO FULLWIDTH ------------------------

		// Narrow data taxonomies
		add_filter( 'vc_autocomplete_blog_images_carousel_category_callback',
			'hb_vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_blog_images_carousel_category_render',
			'hb_vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_hb_blog_minimal_category_callback',
			'hb_vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_hb_blog_minimal_category_render',
			'hb_vc_autocomplete_taxonomies_field_render', 10, 1 );

		function hb_vc_autocomplete_taxonomies_field_render( $term ) {
			$terms = get_terms( 'category' , array(
				'include' => array( $term['value'] ),
				'hide_empty' => false,
			) );
			$data = false;
			if ( is_array( $terms ) && 1 === count( $terms ) ) {
				$term = $terms[0];
				$data = vc_get_term_object( $term );
			}

			return $data;
		}

		function hb_vc_autocomplete_taxonomies_field_search( $search_string ) {
			$data = array();
			$vc_filter_by = vc_post_param( 'vc_filter_by', '' );
			$vc_taxonomies_types = strlen( $vc_filter_by ) > 0 ? array( $vc_filter_by ) : array_keys( vc_taxonomies_types() );
			$vc_taxonomies = get_terms( 'category', array(
				'hide_empty' => false,
				'search' => $search_string
			) );
			if ( is_array( $vc_taxonomies ) && ! empty( $vc_taxonomies ) ) {
				foreach ( $vc_taxonomies as $t ) {
					if ( is_object( $t ) ) {
						$data[] = vc_get_term_object( $t );
					}
				}
			}

			return $data;
		}


		add_filter( 'vc_autocomplete_blog_images_carousel_exclude_callback',
			'hb_vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_blog_images_carousel_exclude_render',
			'hb_vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

		add_filter( 'vc_autocomplete_hb_blog_minimal_exclude_callback',
			'hb_vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_hb_blog_minimal_exclude_render',
			'hb_vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

		function hb_vc_exclude_field_search( $term ) {
			//$query = isset( $data_arr['query'] ) ? $data_arr['query'] : null;
			//$term = isset( $data_arr['term'] ) ? $data_arr['term'] : "";
			$data = array();
			$args = array(
				's' => $term,
				'post_type' => 'post'
			);
			$args['vc_search_by_title_only'] = true;
			$args['numberposts'] = - 1;
			if ( strlen( $args['s'] ) === 0 ) {
				unset( $args['s'] );
			}
			add_filter( 'posts_search', 'vc_search_by_title_only', 500, 2 );
			$posts = get_posts( $args );
			if ( is_array( $posts ) && ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$data[] = array(
						'value' => $post->ID,
						'label' => $post->post_title,
						'group' => $post->post_type,
					);
				}
			}

			return $data;
		}

		function hb_vc_exclude_field_render( $value ) {
			$post = get_post( $value['value'] );

			return is_null( $post ) ? false : array(
				'label' => $post->post_title,
				'value' => $post->ID,
				'group' => $post->post_type
			);
		}

		// BLOG IMAGES CAROUSEL ------------------------
		vc_map( array(
			"name" => __("Blog Images Carousel", "js_composer"),
			"base" => "blog_images_carousel",
		  	"icon" => "icon-blog-images-carousel",
		  	"wrapper_class" => "hb-wrapper-blog-images-carousel",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Blog Carousel with Images.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "10",
					"description" => __("Set max limit for items in the carousel or enter -1 to display all.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Elements per row", "js_composer"),
					"param_name" => "columns",
					"admin_label" => true,
					"value" => array(
		               	__("1", "js_composer") => '1',
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
						__("7", "js_composer") => '7',
						__("8", "js_composer") => '8',
					),
					"std" => "3",
					"description" => __("Select number of visible Blog Elements at a time.", "js_composer"),
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Add padding between posts?", "js_composer"),
			      "param_name" => "with_padding",
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Carousel item height", "js_composer"),
					"param_name" => "height",
					"admin_label" => true,
					"value" => "350",
					"description" => __("Enter the height of post items in the carousel in px. (Number only)", "js_composer"),
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Show Categories?", "js_composer"),
			      "param_name" => "show_categories",
			      "param_holder_class" => 'vc_col-sm-3',
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			      "std" => "true"
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Read More?", "js_composer"),
			      "param_name" => "show_read_more",
			      "param_holder_class" => 'vc_col-sm-3',
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			      "std" => "true"
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Date?", "js_composer"),
			      "param_name" => "show_date",
			      "param_holder_class" => 'vc_col-sm-3',
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Excerpt?", "js_composer"),
			      "param_name" => "show_excerpt",
			      "param_holder_class" => 'vc_col-sm-3',
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Excerpt Length", "js_composer"),
					"param_name" => "excerpt_length",
					"value" => "15",
		       		"dependency" => Array("element" => "show_excerpt", "value" => array("true")),
					"description" => __("Enter how many word will the excerpt display.", "js_composer"),
		        ),
				array(
					'type' => 'autocomplete',
					'heading' => __( 'Categories', 'js_composer' ),
					'param_name' => 'category',
					"group" => "Data Source",
					'description' => __( 'Specify which categories to display.', 'js_composer' ),
					'settings' => array(
						'multiple' => true,
					)
				),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"group" => "Data Source",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"param_holder_class" => 'vc_col-sm-6',
					"description" => __("Choose in which order to show posts.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
					"std" => "data",
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"group" => "Data Source",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"param_holder_class" => 'vc_col-sm-6',
					"description" => __("Descending or Ascending order.", "js_composer"),
					"std" => "DESC",
		        ),
		        array(
					'type' => 'autocomplete',
					'heading' => __( 'Exclude', 'js_composer' ),
					'param_name' => 'exclude',
					"group" => "Data Source",
					'description' => __( 'Exclude posts, pages, etc. by title.', 'js_composer' ),
					'settings' => array(
						'multiple' => true,
					)
				),
				array(
					"type" => "textfield",
					"heading" => __("Offset", "js_composer"),
					"group" => "Data Source",
					"param_name" => "offset",
					"admin_label" => true,
					"value" => "",
					"description" => __("Number of grid elements to displace or pass over. The 'offset' parameter is ignored when Total Items are set to -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Slider Speed", "js_composer"),
					"param_name" => "data_sliderspeed",
					"value" => "650",
					"group" => "Slider Params",
					"description" => __("Enter transition time between slides in milliseconds.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Autoplay Time", "js_composer"),
					"param_name" => "data_autoplay",
					"value" => "5000",
					"group" => "Slider Params",
					"description" => __("Enter time in milliseconds when the next slide would load.<br/><small>Leave 0 to disable Autoplay</small>", "js_composer"),
		        ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Stop on hover?", "js_composer"),
			      "param_name" => "data_stoponhover",
			      "param_holder_class" => 'vc_col-sm-4',
				  "group" => "Slider Params",
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Lazy Load?", "js_composer"),
			      "param_name" => "data_lazyload",
			      "param_holder_class" => 'vc_col-sm-4',
				  "group" => "Slider Params",
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show bullets?", "js_composer"),
			      "param_name" => "data_pagination",
			      "param_holder_class" => 'vc_col-sm-4',
				  "group" => "Slider Params",
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Rewind Navigation?", "js_composer"),
			      "param_name" => "data_rewindnav",
			      "param_holder_class" => 'vc_col-sm-4',
				  "group" => "Slider Params",
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			      "std" => "true"
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Navigation Arrows?", "js_composer"),
			      "param_name" => "data_navigation",
			      "param_holder_class" => 'vc_col-sm-4',
				  "group" => "Slider Params",
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			      "std" => "true"
			    ),
		    	$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));		
		// END BLOG IMAGES CAROUSEL ------------------------

		// BLOG MINIMAL ------------------------
		vc_map( array(
			"name" => __("Blog Minimal", "js_composer"),
			"base" => "hb_blog_minimal",
		  	"icon" => "icon-blog-minimal",
		  	"wrapper_class" => "hb-wrapper-blog-minimal",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Simple Blog List.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "10",
					"description" => __("Set max limit for items in the carousel or enter -1 to display all.", "js_composer"),
		        ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Featured Image?", "js_composer"),
			      "param_name" => "show_featured_image",
			      "param_holder_class" => 'vc_col-sm-3',
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			      "std" => "true"
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Date?", "js_composer"),
			      "param_name" => "show_date",
			      "param_holder_class" => 'vc_col-sm-3',
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			      "std" => "true"
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Excerpt?", "js_composer"),
			      "param_name" => "show_excerpt",
			      "param_holder_class" => 'vc_col-sm-3',
			      "std" => "true",
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Excerpt Length", "js_composer"),
					"param_name" => "excerpt_length",
					"value" => "15",
		       		"dependency" => Array("element" => "show_excerpt", "value" => array("true")),
					"description" => __("Enter how many word will the excerpt display.", "js_composer"),
		        ),
				array(
					'type' => 'autocomplete',
					'heading' => __( 'Categories', 'js_composer' ),
					'param_name' => 'category',
					"group" => "Data Source",
					'description' => __( 'Specify which categories to display.', 'js_composer' ),
					'settings' => array(
						'multiple' => true,
					)
				),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"group" => "Data Source",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"param_holder_class" => 'vc_col-sm-6',
					"description" => __("Choose in which order to show posts.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
					"std" => "data",
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"group" => "Data Source",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"param_holder_class" => 'vc_col-sm-6',
					"description" => __("Descending or Ascending order.", "js_composer"),
					"std" => "DESC",
		        ),
		        array(
					'type' => 'autocomplete',
					'heading' => __( 'Exclude', 'js_composer' ),
					'param_name' => 'exclude',
					"group" => "Data Source",
					'description' => __( 'Exclude posts, pages, etc. by title.', 'js_composer' ),
					'settings' => array(
						'multiple' => true,
					)
				),
				array(
					"type" => "textfield",
					"heading" => __("Offset", "js_composer"),
					"group" => "Data Source",
					"param_name" => "offset",
					"admin_label" => true,
					"value" => "",
					"description" => __("Number of grid elements to displace or pass over. The 'offset' parameter is ignored when Total Items are set to -1.", "js_composer"),
		        ),
		    	$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));		
		// END BLOG MINIMAL ------------------------

		// BLOG BOXES ------------------------

		add_filter( 'vc_autocomplete_hb_blog_boxes_category_callback',
			'hb_vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_hb_blog_boxes_category_render',
			'hb_vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_hb_blog_boxes_exclude_callback',
			'hb_vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_hb_blog_boxes_exclude_render',
			'hb_vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

		vc_map( array(
			"name" => __("Blog Boxes", "js_composer"),
			"base" => "hb_blog_boxes",
		  	"icon" => "icon-hb-blog-boxes",
		  	"wrapper_class" => "hb-wrapper-hb-blog-boxes",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Blog Boxes.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "10",
					"description" => __("Set max limit for items in the carousel or enter -1 to display all.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Elements per row", "js_composer"),
					"param_name" => "columns",
					"admin_label" => true,
					"value" => array(
		               	__("1", "js_composer") => '1',
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
						__("7", "js_composer") => '7',
						__("8", "js_composer") => '8',
					),
					"std" => "3",
					"description" => __("Select number of visible Blog Elements at a time.", "js_composer"),
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Show Categories?", "js_composer"),
			      "param_name" => "show_categories",
			      "param_holder_class" => 'vc_col-sm-4',
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Read More?", "js_composer"),
			      "param_name" => "show_read_more",
			      "param_holder_class" => 'vc_col-sm-4',
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Date?", "js_composer"),
			      "param_name" => "show_date",
			      "param_holder_class" => 'vc_col-sm-4',
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Show Excerpt?", "js_composer"),
			      "param_name" => "show_excerpt",
			      "param_holder_class" => 'vc_col-sm-4',
			      "value" => Array(__("Yes, please", "js_composer") => 'true')
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Excerpt Length", "js_composer"),
					"param_name" => "excerpt_length",
					"value" => "15",
					"description" => __("Enter how many word will the excerpt display.", "js_composer"),
		       		"dependency" => Array("element" => "show_excerpt", "value" => array("true")),
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Show Featured Image?", "js_composer"),
			      "param_name" => "show_featured_image",
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Crop Featured Image?", "js_composer"),
			      "param_name" => "crop_image",
			      "value" => Array(__("Yes, please", "js_composer") => 'true'),
			      "dependency" => Array("element" => "show_featured_image", "value" => array("true")),
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Image Width", "js_composer"),
					"param_name" => "crop_width",
					"value" => "600",
					"description" => __("Crop to this width in px. (Insert number only).", "js_composer"),
		       		"dependency" => Array("element" => "crop_image", "value" => array("true")),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Image Height", "js_composer"),
					"param_name" => "crop_height",
					"value" => "400",
					"description" => __("ECrop to this height in px. (Insert number only).", "js_composer"),
		       		"dependency" => Array("element" => "crop_image", "value" => array("true")),
		        ),
				array(
					'type' => 'autocomplete',
					'heading' => __( 'Categories', 'js_composer' ),
					'param_name' => 'category',
					"group" => "Data Source",
					'description' => __( 'Specify which categories to display.', 'js_composer' ),
					'settings' => array(
						'multiple' => true,
					)
				),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"group" => "Data Source",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"param_holder_class" => 'vc_col-sm-6',
					"description" => __("Choose in which order to show posts.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),
					"std" => "data",
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"group" => "Data Source",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"param_holder_class" => 'vc_col-sm-6',
					"description" => __("Descending or Ascending order.", "js_composer"),
					"std" => "DESC",
		        ),
		        array(
					'type' => 'autocomplete',
					'heading' => __( 'Exclude', 'js_composer' ),
					'param_name' => 'exclude',
					"group" => "Data Source",
					'description' => __( 'Exclude posts, pages, etc. by title.', 'js_composer' ),
					'settings' => array(
						'multiple' => true,
					)
				),
				array(
					"type" => "textfield",
					"heading" => __("Offset", "js_composer"),
					"group" => "Data Source",
					"param_name" => "offset",
					"admin_label" => true,
					"value" => "",
					"description" => __("Number of grid elements to displace or pass over. The 'offset' parameter is ignored when Total Items are set to -1.", "js_composer"),
		        ),
		    	$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));		
		// END BLOG BOXES ------------------------

		// FLIP BOX --------------------------------
		vc_map( array(
			"name" => __("Flip Box", "js_composer"),
			"base" => "hb_flip_box",
		  	"icon" => "icon-hb-flip-box",
		  	"wrapper_class" => "hb-wrapper-hb-flip-boxes",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Box that flips on hover.', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "dropdown",
		            "heading" => __("Direction", "js_composer"),
		            "param_name" => "flip_direction",
		            "admin_label" => true,
		            "value" => array(
		               	__("Horizontal", "js_composer") => 'horizontal',
						__("Vertical", "js_composer") => 'vertical',
					),
					"std" => "horizontal",
					"description" => __("Choose how in which direction the box flips.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Min Height", "js_composer"),
					"param_name" => "min_height",
					"admin_label" => true,
					"value" => "350",
					"description" => __("Set the minimum height of the box.", "js_composer"),
		        ),

		        array(
		            "type" => "dropdown",
		            "heading" => __("Background Type", "js_composer"),
		            "param_name" => "front_background_type",
		            "admin_label" => true,
		            "value" => array(
		               	__("Color", "js_composer") => 'color',
						__("Image", "js_composer") => 'image',
					),
					"std" => "color",
					"group" => "Box Front",
					"description" => __("Choose the type of your Box background.", "js_composer"),
		        ),
		        array(
					"type" => "colorpicker",
					"heading" => __("Background Color", "js_composer"),
					"param_name" => "front_background_color",
					"value" => "#BF5D52",
					"group" => "Box Front",
					"dependency" => Array("element" => "front_background_type", "value" => array("color")),
					"description" => __("Enter color in hex format for this element.", "js_composer")
			    ),
		        array(
					"type" => "attach_image",
					"heading" => __("Background Image", "js_composer"),
					"param_name" => "front_background_image",
					"group" => "Box Front",
					"description" => __("Upload your image here.", "js_composer"),
					"dependency" => Array("element" => "front_background_type", "value" => array("image")),
			    ),
			    array(
					"type" => "dropdown",
					"heading" => __("Icon Type", "js_composer"),
					"param_name" => "icon_type",
					"group" => "Box Front",
					"value" => array(
					   	__("None", "js_composer") => '',
					   	__("Image", "js_composer") => 'image',
						__("Icon", "js_composer") => 'icon',
					),
					"std" => "icon",
					"description" => __("Choose the type of your Icon.", "js_composer"),
		        ),
		        array(
					"type" => "attach_image",
					"heading" => __("Icon Image", "js_composer"),
					"param_name" => "image",
					"group" => "Box Front",
					"description" => __("Upload your image here.", "js_composer"),
					"dependency" => Array("element" => "icon_type", "value" => array("image")),
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Icon", "js_composer"),
					"param_name" => "icon",
					"admin_label" => true,
					"group" => "Box Front",
					"dependency" => Array("element" => "icon_type", "value" => array("icon")),
					"description" => __("Enter a name of icon you would like to use. Leave empty to exclude. You can find list of icons here: <a href='http://documentation.hb-themes.com/icons/' target='_blank'>Icon List</a>
		Example: hb-moon-apple-fruit.", "js_composer")
				),
				array(
					"type" => "textfield",
					"heading" => __("Icon Size", "js_composer"),
					"param_name" => "icon_size",
					"admin_label" => true,
					"group" => "Box Front",
					"dependency" => Array("element" => "icon_type", "value" => array("icon")),
					"description" => __("Enter the Icon size in px.", "js_composer")
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Icon Color", "js_composer"),
					"param_name" => "icon_color",
					"group" => "Box Front",
					"dependency" => Array("element" => "icon_type", "value" => array("icon")),
					"description" => __("Enter color in hex format for this element.", "js_composer")
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Title", "js_composer"),
					"param_name" => "front_title",
					"admin_label" => true,
					"group" => "Box Front",
					"description" => __("Enter title for the Front side of the Box.", "js_composer")
				),
				array(
					"type" => "textfield",
					"heading" => __("Title Size", "js_composer"),
					"param_name" => "front_title_size",
					"admin_label" => true,
					"group" => "Box Front",
					"description" => __("Enter the Front Title size in px.", "js_composer")
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Title Color", "js_composer"),
					"param_name" => "front_title_color",
					"group" => "Box Front",
					"description" => __("Enter color in hex format for this element.", "js_composer")
			    ),
			    array(
					"type" => "textarea",
					"heading" => __("Description", "js_composer"),
					"param_name" => "front_desc",
					"admin_label" => true,
					"group" => "Box Front",
					"description" => __("Enter description for the Front side of the Box.", "js_composer")
				),
				array(
					"type" => "textfield",
					"heading" => __("Description Size", "js_composer"),
					"param_name" => "front_desc_size",
					"admin_label" => true,
					"group" => "Box Front",
					"description" => __("Enter the Front Description size in px.", "js_composer")
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Description Color", "js_composer"),
					"param_name" => "front_desc_color",
					"group" => "Box Front",
					"description" => __("Enter color in hex format for this element.", "js_composer")
			    ),


			    array(
		            "type" => "dropdown",
		            "heading" => __("Background Type", "js_composer"),
		            "param_name" => "back_background_type",
		            "admin_label" => true,
		            "value" => array(
		               	__("Color", "js_composer") => 'color',
						__("Image", "js_composer") => 'image',
					),
					"std" => "color",
					"group" => "Box Back",
					"description" => __("Choose the type of your Box background.", "js_composer"),
		        ),
		        array(
					"type" => "colorpicker",
					"heading" => __("Background Color", "js_composer"),
					"param_name" => "back_background_color",
					"value" => "#5B6C7D",
					"group" => "Box Back",
					"dependency" => Array("element" => "back_background_type", "value" => array("color")),
					"description" => __("Enter color in hex format for this element.", "js_composer")
			    ),
		        array(
					"type" => "attach_image",
					"heading" => __("Background Image", "js_composer"),
					"param_name" => "back_background_image",
					"group" => "Box Back",
					"description" => __("Upload your image here.", "js_composer"),
					"dependency" => Array("element" => "back_background_type", "value" => array("image")),
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Title", "js_composer"),
					"param_name" => "back_title",
					"group" => "Box Back",
					"description" => __("Enter title for the Back side of the Box.", "js_composer")
				),
				array(
					"type" => "textfield",
					"heading" => __("Title Size", "js_composer"),
					"param_name" => "back_title_size",
					"group" => "Box Back",
					"description" => __("Enter the Back Title size in px.", "js_composer")
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Title Color", "js_composer"),
					"param_name" => "back_title_color",
					"group" => "Box Back",
					"description" => __("Enter color in hex format for this element.", "js_composer")
			    ),
			    array(
					"type" => "textarea",
					"heading" => __("Description", "js_composer"),
					"param_name" => "back_desc",
					"group" => "Box Back",
					"description" => __("Enter description for the Back side of the Box.", "js_composer")
				),
				array(
					"type" => "textfield",
					"heading" => __("Description Size", "js_composer"),
					"param_name" => "back_desc_size",
					"group" => "Box Back",
					"description" => __("Enter the Back Description size in px.", "js_composer")
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Description Color", "js_composer"),
					"param_name" => "back_desc_color",
					"group" => "Box Back",
					"description" => __("Enter color in hex format for this element.", "js_composer")
			    ),
			    array(
					"type" => "textfield",
					"heading" => __("Button Text", "js_composer"),
					"param_name" => "button_text",
					"group" => "Box Back",
					"description" => __("Enter the Button Text.", "js_composer")
				),
				array(
					"type" => "textfield",
					"heading" => __("Button URL", "js_composer"),
					"param_name" => "button_url",
					"group" => "Box Back",
					"description" => __("Enter the Button URL.", "js_composer")
				),
				array(
					"type" => 'checkbox',
					"heading" => __("Open link in new tab?", "js_composer"),
					"param_name" => "button_target",
					"group" => "Box Back",
					"value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Button Color", "js_composer"),
		                "param_name" => "button_color",
						"group" => "Box Back",
		                "description" => __("Choose color for this button.", "js_composer"),
		                "value" => array(
		                	__("Standard", "js_composer") => 'default',
		                	__("Turqoise", "js_composer") => 'turqoise',
		                	__("Green Sea", "js_composer") => 'green-sea',
		                	__("Sunflower", "js_composer") => 'sunflower',
		                	__("Orange", "js_composer") => 'orange',
		                	__("Emerald", "js_composer") => 'emerald',
		                	__("Nephritis", "js_composer") => 'nephritis',
		                	__("Carrot", "js_composer") => 'carrot',
		                	__("Pumpkin", "js_composer") => 'pumpkin',
		                	__("Peter River", "js_composer") => 'peter-river',
		                	__("Belize", "js_composer") => 'belize',
		                	__("Alizarin", "js_composer") => 'alizarin',
		                	__("Pomegranate", "js_composer") => 'pomegranate',
		                	__("Amethyst", "js_composer") => 'amethyst',
		                	__("Wisteria", "js_composer") => 'wisteria',
		                	__("Wet Asphalt", "js_composer") => 'wet-asphalt',
		                	__("Midnight Blue", "js_composer") => 'midnight-blue',
		                	__("Concrete", "js_composer") => 'concrete',
		                	__("Asbestos", "js_composer") => 'asbestos',
		                	__("Darkly", "js_composer") => 'darkly',
		                	__("Light", "js_composer") => 'second-light',
		                	__("Light III", "js_composer") => 'hb-third-light',
		                	__("Dark II", "js_composer") => 'second-dark',
		                	__("Dark III", "js_composer") => 'third-dark',
		                	__("Yellow", "js_composer") => 'yellow',
		                ),
		        ),

		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END FLIP BOX------------------------

		// GALLERY CAROUSEL --------------------------------
		vc_map( array(
			"name" => __("Gallery Carousel", "js_composer"),
			"base" => "gallery_carousel",
		  	"icon" => "icon-gallery-carousel",
		  	"wrapper_class" => "hb-wrapper-gallery-carousel",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Carousel with gallery items.', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "dropdown",
		            "heading" => __("Style", "js_composer"),
		            "param_name" => "style",
		            "admin_label" => true,
		            "value" => array(
		               	__("Standard", "js_composer") => 'standard',
						__("Modern", "js_composer") => 'modern',
					),
					"description" => __("Choose how the gallery items are styled.", "js_composer"),
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Visible items", "js_composer"),
					"param_name" => "visible_items",
					"admin_label" => true,
					"value" => array(
		               	__("2", "js_composer") => '2',
						__("3", "js_composer") => '3',
						__("4", "js_composer") => '4',
						__("5", "js_composer") => '5',
						__("6", "js_composer") => '6',
						__("7", "js_composer") => '7',
						__("8", "js_composer") => '8',
					),
					"description" => __("Choose how many posts are visible at a time.", "js_composer"),
		        ),
		    	array(
					"type" => "textfield",
					"heading" => __("Total Items", "js_composer"),
					"param_name" => "total_items",
					"admin_label" => true,
					"value" => "10",
					"description" => __("Choose how many gallery items to include in the carousel. To get all items enter -1.", "js_composer"),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Speed", "js_composer"),
					"param_name" => "carousel_speed",
					"admin_label" => true,
					"value" => "3000",
					"description" => __("Specify the carousel speed in miliseconds, enter just a number. Example: 2000.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Auto Rotate?", "js_composer"),
					"param_name" => "auto_rotate",
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
		        ),
		        array(
					"type" => "textfield",
					"heading" => __("Category", "js_composer"),
					"param_name" => "category",
					"admin_label" => true,
					"value" => "",
					"description" => __("Choose which gallery categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order By", "js_composer"),
					"param_name" => "orderby",
					"value" => array(
		               	__("Date", "js_composer") => 'date',
						__("Title", "js_composer") => 'title',
						__("Random", "js_composer") => 'rand',
						__("Comment Count", "js_composer") => 'comment_count',
						__("Menu Order", "js_composer") => 'menu_order',
					),
					"description" => __("Choose in which order to show gallery items.<br/><small>Select an order from the list of possible orders.</small>.", "js_composer"),

		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Order", "js_composer"),
					"param_name" => "order",
					"value" => array(
		               	__("Descending", "js_composer") => 'DESC',
						__("Ascending", "js_composer") => 'ASC',
					),
					"description" => __("Descending or Ascending order.", "js_composer"),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END GALLERY CAROUSEL ------------------------

		// LAPTOP SLIDER ---------------------------------
		vc_map( array(
			"name" => __("Laptop Slider", "js_composer"),
			"base" => "laptop_slider",
		  	"icon" => "icon-laptop-slider",
		  	"wrapper_class" => "hb-wrapper-laptop-slider",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Slider withing a laptop mockup image.', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "textfield",
		            "heading" => __("Speed", "js_composer"),
		            "param_name" => "speed",
		            "admin_label" => true,
		            "value" => "",
					"description" => __("Speed in miliseconds before slides are changed. Do not enter ms, just a number.", "js_composer"),
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Bullets", "js_composer"),
					"param_name" => "bullets",
					"admin_label" => true,
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
					"description" => __("Choose whether to display the bullet navigation on the slider.", "js_composer"),
		        ),
			    array(
			      "type" => "attach_images",
			      "heading" => __("Slider Images", "js_composer"),
			      "param_name" => "images",
			      "value" => "",
			      "description" => __("Select images from media library.", "js_composer")
			    ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END LAPTOP SLIDER ------------------------

		// SIMPLE SLIDER ---------------------------------
		vc_map( array(
			"name" => __("Simple Slider", "js_composer"),
			"base" => "simple_slider",
		  	"icon" => "icon-simple-slider",
		  	"wrapper_class" => "hb-wrapper-simple-slider",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Simple Flexslider.', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "textfield",
		            "heading" => __("Speed", "js_composer"),
		            "param_name" => "speed",
		            "admin_label" => true,
		            "value" => "",
					"description" => __("Speed in miliseconds before slides are changed. Do not enter ms, just a number.", "js_composer"),
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Pause on Hover", "js_composer"),
					"param_name" => "pause_on_hover",
					"admin_label" => true,
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
					"description" => __("Choose whether to pause the slider when hovering.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Bordered Style", "js_composer"),
					"param_name" => "border",
					"admin_label" => true,
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
					"description" => __("Choose whether to display a white border around the slider.", "js_composer"),
		        ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Bullets", "js_composer"),
					"param_name" => "bullets",
					"admin_label" => true,
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
					"description" => __("Choose whether to display the bullet navigation on the slider.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Arrows", "js_composer"),
					"param_name" => "arrows",
					"admin_label" => true,
					"value" => array(
		               	__("Enable", "js_composer") => 'yes',
						__("Disable", "js_composer") => 'no',
					),
					"description" => __("Choose whether to display the arrow navigation on the slider.", "js_composer"),
		        ),
			    array(
			      "type" => "attach_images",
			      "heading" => __("Slider Images", "js_composer"),
			      "param_name" => "images",
			      "value" => "",
			      "description" => __("Select images from media library.", "js_composer")
			    ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END SIMPLE SLIDER ------------------------

		// SOCIAL ICONS ------------------------------
		vc_map( array(
			"name" => __("Social Icons", "js_composer"),
			"base" => "social_icons",
		  	"icon" => "icon-social-icons",
		  	"wrapper_class" => "hb-wrapper-social-icons",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Generates Social Icons.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Style", "js_composer"),
					"param_name" => "style",
					"admin_label" => true,
					"value" => array(
						__("Dark", "js_composer") => 'dark',
		               	__("Light", "js_composer") => 'light',
					),
					"description" => __("Select a style for these social icons.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Size", "js_composer"),
					"param_name" => "size",
					"admin_label" => true,
					"value" => array(
						__("Small", "js_composer") => 'small',
		               	__("Large", "js_composer") => 'large',
					),
					"description" => __("Select size of these social icons.", "js_composer"),
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Open links in", "js_composer"),
		                "param_name" => "new_tab",
		                "value" => array(
		                	__("Same tab", "js_composer") => 'no',
		                	__("New tab", "js_composer") => 'yes',
		                ),
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Twitter URL", "js_composer"),
		                "param_name" => "twitter",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Facebook URL", "js_composer"),
		                "param_name" => "facebook",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Skype URL", "js_composer"),
		                "param_name" => "skype",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Instagram URL", "js_composer"),
		                "param_name" => "instagram",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Pinterest URL", "js_composer"),
		                "param_name" => "pinterest",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Google Plus URL", "js_composer"),
		                "param_name" => "google_plus",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Dribbble URL", "js_composer"),
		                "param_name" => "dribbble",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("SoundCloud URL", "js_composer"),
		                "param_name" => "soundcloud",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("YouTube URL", "js_composer"),
		                "param_name" => "youtube",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Vimeo URL", "js_composer"),
		                "param_name" => "vimeo",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Flickr URL", "js_composer"),
		                "param_name" => "flickr",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Tumblr URL", "js_composer"),
		                "param_name" => "tumblr",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("VKontakte URL", "js_composer"),
		                "param_name" => "vk",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Yahoo URL", "js_composer"),
		                "param_name" => "yahoo",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Foursquare URL", "js_composer"),
		                "param_name" => "foursquare",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Blogger URL", "js_composer"),
		                "param_name" => "blogger",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("WordPress URL", "js_composer"),
		                "param_name" => "wordpress",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("LastFM URL", "js_composer"),
		                "param_name" => "lastfm",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("GitHub URL", "js_composer"),
		                "param_name" => "github",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("LinkedIn URL", "js_composer"),
		                "param_name" => "linkedin",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Yelp URL", "js_composer"),
		                "param_name" => "yelp",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Forrst URL", "js_composer"),
		                "param_name" => "forrst",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Deviantart URL", "js_composer"),
		                "param_name" => "deviantart",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("StumbleUpon URL", "js_composer"),
		                "param_name" => "stumbleupon",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Delicious URL", "js_composer"),
		                "param_name" => "delicious",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Reddit URL", "js_composer"),
		                "param_name" => "reddit",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Xing URL", "js_composer"),
		                "param_name" => "xing",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Behance URL", "js_composer"),
		                "param_name" => "behance",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Twitch URL", "js_composer"),
		                "param_name" => "twitch",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Weibo URL", "js_composer"),
		                "param_name" => "weibo",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Trip Advisor URL", "js_composer"),
		                "param_name" => "tripadvisor",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("500px URL", "js_composer"),
		                "param_name" => "sn500px",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Email URL", "js_composer"),
		                "param_name" => "envelop",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("RSS URL", "js_composer"),
		                "param_name" => "feed_2",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Custom URL", "js_composer"),
		                "param_name" => "custom_url",
		                "admin_label" => true,
		                "value" => "",
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END SOCIAL ICONS -------------------------

		// CIRCLE CHART ------------------------------
		vc_map( array(
			"name" => __("Circle Chart", "js_composer"),
			"base" => "circle_chart",
		  	"icon" => "icon-circle-chart",
		  	"wrapper_class" => "hb-wrapper-circle-chart",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Highly customisable circle chart.', 'js_composer'),
		    "params"	=> array(
		    	array(
					"type" => "dropdown",
					"heading" => __("Chart Type", "js_composer"),
					"param_name" => "type",
					"admin_label" => true,
					"value" => array(
						__("Chart with Icon", "js_composer") => 'with-icon',
		               	__("Chart with Percent", "js_composer") => 'with-percent',
		               	__("Chart with Text", "js_composer") => 'with-text',
					),
					"description" => __("Select a type for this element.", "js_composer"),
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Chart Percent", "js_composer"),
		                "param_name" => "percent",
		                "admin_label" => true,
		                "value" => "60",
		                "description" => __("Enter a percent number here. Do not enter % character, just number! Example: 60", "js_composer")
		        ),
		        $add_icon,
		        array(
		                "type" => "textfield",
		                "heading" => __("Chart Text", "js_composer"),
		                "param_name" => "text",
		                "admin_label" => true,
		                "value" => "",
		                "description" => __("If you have selected 'Chart with Text' enter your text here. Example: Photoshop", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Chart Caption", "js_composer"),
		                "param_name" => "caption",
		                "admin_label" => true,
		                "value" => "",
		                "description" => __("Optional chart caption. Showed below the chart.", "js_composer")
		        ),
		        array(
			      "type" => "colorpicker",
			      "heading" => __("Line Color", "js_composer"),
			      "param_name" => "color",
			      "description" => __("Enter color in hex format for this element.", "js_composer")
			    ),
			    array(
			      "type" => "colorpicker",
			      "heading" => __("Circle Color", "js_composer"),
			      "param_name" => "track_color",
			      "description" => __("Enter color in hex format for the circle track bar.", "js_composer")
			    ),
			    array(
		                "type" => "textfield",
		                "heading" => __("Chart Size", "js_composer"),
		                "param_name" => "size",
		                "admin_label" => true,
		                "value" => "220",
		                "description" => __("Enter chart size value. Example: 220.", "js_composer")
		        ),
			     array(
		                "type" => "textfield",
		                "heading" => __("Chart Weight", "js_composer"),
		                "param_name" => "weight",
		                "admin_label" => true,
		                "value" => "3",
		                "description" => __("Enter chart weight value. Example: 4.", "js_composer")
		        ),

			     array(
		                "type" => "textfield",
		                "heading" => __("Animation Speed", "js_composer"),
		                "param_name" => "animation_speed",
		                "value" => "1000",
		                "description" => __("Enter chart animation speed. Useful for creating timed animations. No need to enter ms. Eg: 1000 (1000 stands for 1 second)", "js_composer")
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END CIRCLE CHART -------------------------

		// GOOGLE MAP ----------------------------------
		vc_map( array(
			"name" => __("Map", "js_composer"),
			"base" => "map_embed",
		  	"icon" => "icon-map-embed",
		  	"wrapper_class" => "hb-wrapper-map-embed",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Embed a Google Map.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Latitude", "js_composer"),
		                "param_name" => "latitude",
		                "value" => "48.856614",
		                "description" => __("Enter latitude coordinate where the map will be centered. You can use <a href=\"http://latlong.net\" target=\"_blank\">LatLong</a> to find out coordinates.", "js_composer"),
		                "admin_label" => true
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Longitude", "js_composer"),
		                "param_name" => "longitude",
		                "value" => "2.352222",
		                "description" => __("Enter longitude coordinate where the map will be centered. You can use <a href=\"http://latlong.net\" target=\"_blank\">LatLong</a> to find out coordinates.", "js_composer"),
		                "admin_label" => true
		        ),
		    	array(
		                "type" => "textfield",
		                "heading" => __("Zoom", "js_composer"),
		                "param_name" => "zoom",
		                "value" => "16",
		                "description" => __("Enter zoom level for the map. A numeric value from 1 to 18, where 1 is whole earth and 18 is street level zoom.", "js_composer"),
		                "admin_label" => true
		        ),
			    array(
			      		"type" => "attach_image",
					    "heading" => __("Custom Pin Image", "js_composer"),
					    "param_name" => "custom_pin",
					    "value" => "",
					    "description" => __("Select image from media library.", "js_composer")
			    ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Map Height", "js_composer"),
		                "param_name" => "height",
		                "description" => __("Enter map height in pixels for the map. You can use px, em, %, etc. or enter just number and it will use pixels.", "js_composer"),
		                "admin_label" => true
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Border Around Map", "js_composer"),
		                "param_name" => "border",
		                "value" => array(
		                	__("Show", "js_composer") => 'yes',
		                	__("Hide", "js_composer") => 'no',
		                ),
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Styled Map", "js_composer"),
		                "param_name" => "styled",
		                "value" => array(
		                	__("Yes", "js_composer") => 'yes',
		                	__("No", "js_composer") => 'no',
		                ),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END GOOGLE MAP ------------------------------


		// GOOGLE MAP ----------------------------------
		vc_map( array(
			"name" => __("Fullwidth Map", "js_composer"),
			"base" => "fw_map_embed",
		  	"icon" => "icon-map-embed",
		  	"wrapper_class" => "hb-wrapper-map-embed",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Embed a Fullwidth Google Map.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "dropdown",
		                "heading" => __("Use values from Highend Options > Map Settings", "js_composer"),
		                "param_name" => "from_to",
		                "value" => array(
		                	__("No", "js_composer") => 'no',
		                	__("Yes", "js_composer") => 'yes',
		                ),
		        ),
		    	array(
		                "type" => "textfield",
		                "heading" => __("Latitude", "js_composer"),
		                "param_name" => "latitude",
		                "value" => "48.856614",
		                "description" => __("Enter latitude coordinate where the map will be centered. You can use <a href=\"http://latlong.net\" target=\"_blank\">LatLong</a> to find out coordinates.", "js_composer"),
		                "admin_label" => true
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Longitude", "js_composer"),
		                "param_name" => "longitude",
		                "value" => "2.352222",
		                "description" => __("Enter longitude coordinate where the map will be centered. You can use <a href=\"http://latlong.net\" target=\"_blank\">LatLong</a> to find out coordinates.", "js_composer"),
		                "admin_label" => true
		        ),
		    	array(
		                "type" => "textfield",
		                "heading" => __("Zoom", "js_composer"),
		                "param_name" => "zoom",
		                "value" => "16",
		                "description" => __("Enter zoom level for the map. A numeric value from 1 to 18, where 1 is whole earth and 18 is street level zoom.", "js_composer"),
		                "admin_label" => true
		        ),
			    array(
			      		"type" => "attach_image",
					    "heading" => __("Custom Pin Image", "js_composer"),
					    "param_name" => "custom_pin",
					    "value" => "",
					    "description" => __("Select image from media library.", "js_composer")
			    ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Map Height", "js_composer"),
		                "param_name" => "height",
		                "description" => __("Enter map height in pixels for the map.", "js_composer"),
		                "admin_label" => true
		        ),
		        $add_margin_top,
		       	$add_margin_bottom,
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END GOOGLE MAP ------------------------------

		// BUTTON ----------------------------------
		vc_map( array(
			"name" => __("Button", "js_composer"),
			"base" => "button",
		  	"icon" => "icon-button",
		  	"wrapper_class" => "hb-wrapper-button",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Generates a button.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Button Title", "js_composer"),
		                "param_name" => "title",
		                "description" => __("Enter the title/caption for this button. Example: Click Me", "js_composer"),
		                "admin_label" => true
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Button Link", "js_composer"),
		                "param_name" => "link",
		                "description" => __("Choose URL of the link for the button. <br/>Enter with http:// prefix. You can also enter section id with # prefix to scroll to the section within your page. Example #home", "js_composer"),
		                "admin_label" => true
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Open link in", "js_composer"),
		                "param_name" => "new_tab",
		                "value" => array(
		                	__("Same tab", "js_composer") => 'no',
		                	__("New tab", "js_composer") => 'yes',
		                ),
		        ),
		        $add_icon,
		        array(
		                "type" => "dropdown",
		                "heading" => __("Icon Position", "js_composer"),
		                "param_name" => "icon_position",
		                "value" => array(
		                	__("Left", "js_composer") => 'left',
		                	__("Push down", "js_composer") => 'push_down',
		                ),
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Special Styling", "js_composer"),
		                "param_name" => "special_style",
		                "value" => array(
		                	__("Standard", "js_composer") => 'no',
		                	__("Special", "js_composer") => 'yes',
		                ),
		        ),
		        array(
						"type" => "number",
						"class" => "",
						"heading" => __("Border Radius", "hbthemes"),
						"param_name" => "border_radius",
						"value" => 2,
						"min" => 0,
						"max" => 100,
						"suffix" => "px",
						"description" => __("Enter a border radius value. Value needs to be in range: 0-100.", "hbthemes"),
					),
		        array(
		                "type" => "dropdown",
		                "heading" => __("3D Effect", "js_composer"),
		                "param_name" => "three_d",
		                "value" => array(
		                	__("No", "js_composer") => 'no',
		                	__("Yes", "js_composer") => 'yes',
		                ),
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Size", "js_composer"),
		                "param_name" => "size",
		                "description" => __("Choose size for this button.", "js_composer"),
		                "value" => array(
		                	__("Standard", "js_composer") => 'default',
		                	__("Small", "js_composer") => 'small',
		                	__("Large", "js_composer") => 'large',
		                ),
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Color", "js_composer"),
		                "param_name" => "color",
		                "description" => __("Choose color for this button.", "js_composer"),
		                "value" => array(
		                	__("Standard", "js_composer") => 'default',
		                	__("Turqoise", "js_composer") => 'turqoise',
		                	__("Green Sea", "js_composer") => 'green-sea',
		                	__("Sunflower", "js_composer") => 'sunflower',
		                	__("Orange", "js_composer") => 'orange',
		                	__("Emerald", "js_composer") => 'emerald',
		                	__("Nephritis", "js_composer") => 'nephritis',
		                	__("Carrot", "js_composer") => 'carrot',
		                	__("Pumpkin", "js_composer") => 'pumpkin',
		                	__("Peter River", "js_composer") => 'peter-river',
		                	__("Belize", "js_composer") => 'belize',
		                	__("Alizarin", "js_composer") => 'alizarin',
		                	__("Pomegranate", "js_composer") => 'pomegranate',
		                	__("Amethyst", "js_composer") => 'amethyst',
		                	__("Wisteria", "js_composer") => 'wisteria',
		                	__("Wet Asphalt", "js_composer") => 'wet-asphalt',
		                	__("Midnight Blue", "js_composer") => 'midnight-blue',
		                	__("Concrete", "js_composer") => 'concrete',
		                	__("Asbestos", "js_composer") => 'asbestos',
		                	__("Darkly", "js_composer") => 'darkly',
		                	__("Light", "js_composer") => 'second-light',
		                	__("Light III", "js_composer") => 'hb-third-light',
		                	__("Dark II", "js_composer") => 'second-dark',
		                	__("Dark III", "js_composer") => 'third-dark',
		                	__("Yellow", "js_composer") => 'yellow',
		                ),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END BUTTON ------------------------------

		// MODAL ----------------------------------
		vc_map( array(
			"name" => __("Modal Window", "js_composer"),
			"base" => "modal_window",
		  	"icon" => "icon-modal",
		  	"wrapper_class" => "hb-wrapper-modal",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Show content in modal window.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Window Title", "js_composer"),
		                "param_name" => "title",
		                "description" => __("Enter a title for this modal window. Example: My Window Title", "js_composer"),
		                "admin_label" => true
		        ),
		    	array(
				      "type" => "textarea_html",
				      "class" => "callout-box-holder",
				      "heading" => __("Callout Content", "js_composer"),
				      "param_name" => "content",
				      "value" => __("<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>", "js_composer")
			    ),
			    array(
		                "type" => "textfield",
		                "heading" => __("Invoke Button Title", "js_composer"),
		                "param_name" => "invoke_title",
		                "description" => __("Enter a title for the invoke button for this modal window. Example: Show Modal", "js_composer"),
		                "admin_label" => true
		        ),
			    array(
		                "type" => "dropdown",
		                "heading" => __("Show On Load", "js_composer"),
		                "param_name" => "show_on_load",
		                "value" => array(
		                	__("Yes", "js_composer") => 'yes',
		                	__("No", "js_composer") => 'no',
		                ),
		                "description" => __("Choose if you want to show this modal window automatically when the page loads.", "js_composer"),
		                "admin_label" => true
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Optional Unique ID", "js_composer"),
		                "param_name" => "id",
		                "description" => __("Enter a UNIQUE id word, without spaces, that will be assigned to this modal window. You can use this id to invoke the window with javascript if you don't want to show the invoke button.", "js_composer"),
		                "admin_label" => true
		        ),
		    ),
		));
		// END MODAL ------------------------------

		// BOX CONTENT ----------------------------
		vc_map( array(
			"name" => __("Content Box", "js_composer"),
			"base" => "content_box",
		  	"icon" => "icon-content-box",
		  	"wrapper_class" => "hb-wrapper-content-box",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Shows any content in styled box.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "dropdown",
		                "heading" => __("Box Style", "js_composer"),
		                "param_name" => "type",
		                "value" => array(
		                	__("With Header", "js_composer") => 'with-header',
		                	__("Without Header", "js_composer") => 'without-header',
		                ),
		                "description" => __("Choose your box style.", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Box Title", "js_composer"),
		                "param_name" => "title",
		                "holder" => "div",
		                "class" => "box-title-holder",
		                "description" => __("Enter box title here if you have selected 'With Header' box style.<br/>Example: My box title.", "js_composer")
		        ),
		        array(
				      "type" => "textarea_html",
				      "holder" => "div",
				      "class" => "box-content-holder",
				      "heading" => __("Text", "js_composer"),
				      "param_name" => "content",
				      "value" => __("<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>", "js_composer")
			    ),
			    $add_icon,
			    array(
			    	"type" => "colorpicker",
			    	"heading" => __("Background Color", "js_composer"),
			    	"param_name" => "color",
			    	"admin_label" => true,
			    	"description" => __("Choose background color for this box.", "js_composer")
			    ),
			    array(
					"type" => "dropdown",
					"heading" => __("Content Color", "js_composer"),
					"param_name" => "text_color",
					"admin_label" => true,
					"value" => array(
						__("Dark", "js_composer") => 'dark',
						__("Light", "js_composer") => 'light',
						),
					"description" => __("Choose your text color style.", "js_composer")
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END BOX CONTENT ------------------------

		// CALLOUT --------------------------------
		vc_map( array(
			"name" => __("Callout Box", "js_composer"),
			"base" => "callout",
		  	"icon" => "icon-callout-box",
		  	"wrapper_class" => "hb-wrapper-callout-box",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Shows a styled callout box with optional button.', 'js_composer'),
		    "params"	=> array(
		    	array(
				      "type" => "textarea_html",
				      "holder" => "div",
				      "class" => "callout-box-holder",
				      "heading" => __("Callout Content", "js_composer"),
				      "param_name" => "content",
				      "value" => __("<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>", "js_composer")
			    ),
		    	array(
		                "type" => "textfield",
		                "heading" => __("Button Title", "js_composer"),
		                "param_name" => "title",
		                "holder" => "button",
		                "class" => "callout-button-holder",
		                "description" => __("Enter the title/caption for this button. Leave empty if you don't need a button.", "js_composer")
		        ),
		        $add_icon,
		        array(
		                "type" => "textfield",
		                "heading" => __("Button Link URL", "js_composer"),
		                "param_name" => "link",
		                "class" => "callout-button-holder",
		                "description" => __("Choose URL of the link for the button. Enter with http:// prefix.<br/>You can also enter section id with # prefix to scroll to the section within your page. Example #home", "js_composer")
		        ),
		        array(
		                "type" => "dropdown",
		                "heading" => __("Open link in", "js_composer"),
		                "param_name" => "new_tab",
		                "value" => array(
		                	__("Same tab", "js_composer") => 'no',
		                	__("New tab", "js_composer") => 'yes',
		                ),
		        ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END CALLOUT ----------------------------

		// SITEMAP --------------------------------
		vc_map( array(
			"name" => __("Sitemap", "js_composer"),
			"base" => "sitemap",
		  	"icon" => "icon-sitemap-image",
		  	"wrapper_class" => "hb-wrapper-sitemap",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Shows sitemap of your website.', 'js_composer'),
		  	"show_settings_on_create" => false,
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Sitemap Depth", "js_composer"),
		                "param_name" => "depth",
		                "value" => "2",
		                "admin_label" => true,
		                "description" => __("Specify how many child levels to show. Leave empty for default value. Default: 2.", "js_composer")
		        ),
		        $add_class,
		    ),
		));
		// END SITEMAP ---------------------------

		// SPACER --------------------------------
		vc_map( array(
			"name" => __("Spacer", "js_composer"),
			"base" => "spacer",
		  	"icon" => "icon-spacer",
		  	"wrapper_class" => "hb-wrapper-spacer",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('A blank spacer with specified height.', 'js_composer'),
		  	"show_settings_on_create" => false,
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Spacer Height", "js_composer"),
		                "param_name" => "height",
		                "value" => "",
		                "description" => __("Enter the height of this spacer. You can use px, em, %, etc. or enter just number and it will use pixels. Example: 40", "js_composer"),
		        ),
		        $add_class,
		    ),
		));
		// END SPACER --------------------------------

		// SKILL BAR ---------------------------------
		vc_map( array(
			"name" => __("Skill Bar", "js_composer"),
			"base" => "skill",
		  	"icon" => "icon-skill-bar",
		  	"wrapper_class" => "hb-wrapper-skill-bar",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('A single skill bar.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Skill Value", "js_composer"),
		                "param_name" => "number",
		                "value" => "75",
		                "description" => __("Enter the number this skill is filled. Maximum 100. Do not write % or any other character. Example: 80", "js_composer"),
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Character", "js_composer"),
		                "param_name" => "char",
		                "value" => "%",
		                "description" => __("Enter a character which stands next to the number. Example: %", "js_composer"),
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Skill Caption", "js_composer"),
		                "param_name" => "caption",
		                "value" => "Enter Caption",
		                "description" => __("A word, or short text to display above the skill meter. Example: Photoshop", "js_composer"),
		        ),
		        array(
			      "type" => "colorpicker",
			      "heading" => __("Color", "js_composer"),
			      "param_name" => "color",
			      "description" => __("Choose a focus color in hex format for this skill bar. Leave empty for default value.", "js_composer")
			    ),
		        $add_class,
		    ),
		));
		// END SKILL BAR -----------------------------


		// SEPARATOR ---------------------------------
		vc_map( array(
			"name" => __("Separator", "js_composer"),
			"base" => "separator",
		  	"icon" => "icon-separator",
		  	"wrapper_class" => "hb-wrapper-separator",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Line separator with extra options.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "dropdown",
		                "heading" => __("Separator Type", "js_composer"),
		                "param_name" => "type",
		                "value" => array(
		                	__("Default", "js_composer") => 'default',
		                	__("Small Break", "js_composer") => 'small-break',
		                	__("Default Double", "js_composer") => 'default-double',
		                	__("Dashed", "js_composer") => 'dashed',
		                	__("Dashed Double", "js_composer") => 'dashed-double',
		                	__("Small", "js_composer") => 'small',
		                	__("* Fullwidth", "js_composer") => 'hb-fw-separator',
		                	__("* Fullwidth Dashed", "js_composer") => 'hb-fw-dashed',
		                ),
		                "admin_label" => true,
		                "description" => __("Choose your separator style. * Fullwidth Separator does not support all of the options and it has to be used in fullwidth layout.", "js_composer")
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Optional scissors icon?", "js_composer"),
			      "param_name" => "scissors_icon",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Go to top button?", "js_composer"),
			      "param_name" => "go_to_top",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			   	$add_margin_top,
		       	$add_margin_bottom,
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END SEPARATOR ------------------------


		// CLEAR --------------------------------
		vc_map( array(
			"name" => __("Clear", "js_composer"),
			"base" => "clear",
		  	"icon" => "icon-clear",
		  	"controls" => "popup_delete",
		  	"wrapper_class" => "hb-wrapper-clear",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Clear floated elements.', 'js_composer'),
		  	"show_settings_on_create" => false,
		));
		// END CLEAR --------------------------------


		// INFO MESSAGE -----------------------------
		vc_map( array(
			"name" => __("Info Message", "js_composer"),
			"base" => "info_message",
		  	"icon" => "icon-message",
			"wrapper_class" => "hb-notification-box",
			"category" => __('Highend Shortcodes', 'js_composer'),
			"description" => __('Notification box', 'js_composer'),
			"params" => array(
				array(
			      "type" => "dropdown",
			      "heading" => __("Message style", "js_composer"),
			      "param_name" => "style",
			      "value" => array(__('Info', "js_composer") => "info", __('Warning', "js_composer") => "warning", __('Success', "js_composer") => "success", __('Error', "js_composer") => "error"),
			      "description" => __("Choose a style for this message.", "js_composer")
			    ),
			    array(
			      "type" => "textarea_html",
			      "holder" => "div",
			      "class" => "messagebox_text",
			      "heading" => __("Message text", "js_composer"),
			      "param_name" => "content",
			      "value" => __("<p>I am message box. Click edit button to change this text.</p>", "js_composer")
			    ),
			    $add_css_animation,
			    $add_animation_delay,
			    $add_class,
			)
		));
		// END INFO MESSAGE -------------------------

		
		// COUNTDOWN --------------------------------
		vc_map( array(
			"name" => __("Countdown", "js_composer"),
			"base" => "countdown",
		  	"icon" => "icon-countdown",
		  	"wrapper_class" => "hb-wrapper-countdown",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Countdown timer for specified date and time in future. Time is in 24h format.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Date & Time", "js_composer"),
		                "param_name" => "date",
		                "value" => "24 april 2016 16:00:00",
		                "admin_label" => true,
		                "description" => __("Enter date and time for which countdown will count.<br/>Must enter in this format: <strong>27 february 2014 12:00:00</strong>", "js_composer")
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Align center?", "js_composer"),
			      "param_name" => "aligncenter",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes', __("No", "js_composer") => 'no')
			    ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));

		// VIDEO EMBED ---------------------------
		vc_map( array(
			"name" => __("Video Embed", "js_composer"),
			"base" => "video_embed",
		  	"icon" => "icon-video-embed",
		  	"wrapper_class" => "hb-wrapper-video-embed",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Embed video from Youtube/Vimeo or similar.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "dropdown",
		                "heading" => __("Embed Style", "js_composer"),
		                "param_name" => "embed_style",
		                "value" => array(
		                	__("Default", "js_composer") => 'default',
		                	__("In Lightbox", "js_composer") => 'in_lightbox',
		                ),
		                "admin_label" => true,
		                "description" => __("Choose between standard embed and embed in lightbox. Lightbox embed will generate a button invoker.", "js_composer")
		        ),
		    	array(
		                "type" => "textfield",
		                "heading" => __("Video URL", "js_composer"),
		                "param_name" => "url",
		                "admin_label" => true,
		                "value" => "",
		                "description" => __("URL to the video which needs to be embedded.<br/>Youtube example: <strong>http://www.youtube.com/watch?v=NxfK4LANqww</strong>", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Video Width", "js_composer"),
		                "param_name" => "width",
		                "admin_label" => true,
		                "value" => "",
		                "description" => __("Width of the Video in pixels. Height will be calculated automatically. You can use px, em, %, etc. or enter just number and it will use pixels. Leave empty for fullwidth.<br/>Example: <strong>550</strong>", "js_composer")
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Border around video?", "js_composer"),
			      "param_name" => "border",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
		        $add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END VIDEO EMBED --------------------------------

		// FW SECTION -------------------------------------
		vc_map( array(
			"name" => __("Fullwidth Section", "js_composer"),
			"base" => "fullwidth_section",
		  	"icon" => "icon-fw-section",
		  	"wrapper_class" => "hb-wrapper-fw-section",
		  	"deprecated" => true,
		  	"description" => __('This shortcode is deprecated. Please use ROW instead this shortcode.', 'js_composer'),
		    "params"	=> array(
		    	array(
				      "type" => "textarea_html",
				      "class" => "callout-box-holder",
				      "heading" => __("Content", "js_composer"),
				      "param_name" => "content",
				      "value" => __("<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>", "js_composer")
			    ),
		    	array(
		                "type" => "dropdown",
		                "heading" => __("Background Type", "js_composer"),
		                "param_name" => "background_type",
		                "value" => array(
		                	__("Background Color", "js_composer") => 'color',
		                	__("Background Image", "js_composer") => 'image',
		                	__("Background Texture", "js_composer") => 'texture',
		                	__("Background Video", "js_composer") => 'video',
		                ),
		                "admin_label" => true,
		                "description" => __("Select a background type for this element.", "js_composer")
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Enable Border?", "js_composer"),
			      "param_name" => "border",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
		                "type" => "dropdown",
		                "heading" => __("Text Color", "js_composer"),
		                "param_name" => "text_color",
		                "value" => array(
		                	__("Dark", "js_composer") => 'dark',
		                	__("Light", "js_composer") => 'light',
		                ),
		                "description" => __("Select a text color style for this element. Use light when your background is dark and opposite.", "js_composer")
		        ),
		        array(
			      "type" => "colorpicker",
			      "heading" => __("Background Color", "js_composer"),
			      "param_name" => "bg_color",
			      "description" => __("Choose background color in hex format.", "js_composer")
			    ),
			    array(
			      		"type" => "attach_image",
					    "heading" => __("Background Image or Texture", "js_composer"),
					    "param_name" => "image",
					    "value" => "",
					    "admin_label" => true,
					    "description" => __("Select an image from media library that will be used as your background image or texture depending on value in Background Type.", "js_composer")
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Enable Parallax effect for image?", "js_composer"),
			      "param_name" => "parallax",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Enable waved top border for this fullwidth section?", "js_composer"),
			      "param_name" => "waved_border_top",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Enable waved bottom border for this fullwidth section?", "js_composer"),
			      "param_name" => "waved_border_bottom",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Optional Scissors Icon?", "js_composer"),
			      "param_name" => "scissors_icon",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
			      		"type" => "textfield",
					    "heading" => __("Background Video MP4", "js_composer"),
					    "param_name" => "bg_video_mp4",
					    "admin_label" => true,
					    "value" => "",
					    "description" => __("If you have chosen \"Background Video\" for the Background Type, then upload your video in MP4 format here. ", "js_composer")
			    ),
			    array(
			      		"type" => "textfield",
					    "heading" => __("Background Video OGV", "js_composer"),
					    "param_name" => "bg_video_ogv",
					    "admin_label" => true,
					    "value" => "",
					    "description" => __("If you have chosen \"Background Video\" for the Background Type, then upload your video in OGG format here. ", "js_composer")
			    ),
			    array(
			      		"type" => "attach_image",
					    "heading" => __("Background Video Poster", "js_composer"),
					    "param_name" => "bg_video_poster",
					    "value" => "",
					    "description" => __("Select an image that will be used as a placeholder until video is loaded (or cannot be loaded). ", "js_composer")
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Video Texture Overlay?", "js_composer"),
			      "param_name" => "overlay",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			   	$add_margin_top,
		       	$add_margin_bottom,
		        array(
		                "type" => "textfield",
		                "heading" => __("Inside Padding Top", "js_composer"),
		                "param_name" => "padding_top",
		                "value" => "30",
		                "group" => "Position",
		                "description" => __("Enter top padding for this section in pixels. Leave empty for default value. No need to write px. Eg: 50", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Inside Padding Bottom", "js_composer"),
		                "param_name" => "padding_bottom",
		                "group" => "Position",
		                "value" => "30",
		                "description" => __("Enter bottom padding for this section in pixels. Leave empty for default value. No need to write px. Eg: 50", "js_composer")
		        ),
		        $add_class,
		        array(
		                "type" => "textfield",
		                "heading" => __("Unique Section ID", "js_composer"),
		                "param_name" => "id",
		                "value" => "",
		                "admin_label" => true,
		                "description" => __("If needed, enter a UNIQUE section id, without whitespaces. You can use that id to make links to this section. Example: about-us", "js_composer")
		        ),
		    ),
		));
		// END FW SECTION --------------------------------

		// FW SECTION -------------------------------------
		vc_map( array(
			"name" => __("One Page Section", "js_composer"),
			"base" => "onepage_section",
		  	"icon" => "icon-onepage-section",
		  	"wrapper_class" => "hb-wrapper-onepage-section",
		  	"deprecated" => true,
		  	"description" => __('This shortcode is deprecated. Please use ROW instead this shortcode.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Unique Section ID", "js_composer"),
		                "param_name" => "id",
		                "value" => "",
		                "admin_label" => true,
		                "description" => __("Enter a UNIQUE section id, without whitespaces. This is very important for One Page websites, as this will be used for a navigation. For example, if you have entered <strong>first-page</strong> in this field, in your menu, you would enter <strong>#first-page</strong> to link to this page.", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Section Title", "js_composer"),
		                "param_name" => "name",
		                "value" => "My Page",
		                "admin_label" => true,
		                "description" => __("Enter title for this section. It will be used in left circle navigation on one page websites.", "js_composer")
		        ),
		    	array(
				      "type" => "textarea_html",
				      "class" => "callout-box-holder",
				      "heading" => __("Content", "js_composer"),
				      "param_name" => "content",
				      "value" => __("<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>", "js_composer")
			    ),
		    	array(
		                "type" => "dropdown",
		                "heading" => __("Background Type", "js_composer"),
		                "param_name" => "background_type",
		                "value" => array(
		                	__("Background Color", "js_composer") => 'color',
		                	__("Background Image", "js_composer") => 'image',
		                	__("Background Texture", "js_composer") => 'texture',
		                	__("Background Video", "js_composer") => 'video',
		                ),
		                "admin_label" => true,
		                "description" => __("Select a background type for this element.", "js_composer")
		        ),
		        array(
			      "type" => 'checkbox',
			      "heading" => __("Enable Border?", "js_composer"),
			      "param_name" => "border",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
		                "type" => "dropdown",
		                "heading" => __("Text Color", "js_composer"),
		                "param_name" => "text_color",
		                "value" => array(
		                	__("Dark", "js_composer") => 'dark',
		                	__("Light", "js_composer") => 'light',
		                ),
		                "description" => __("Select a text color style for this element. Use light when your background is dark and opposite.", "js_composer")
		        ),
		        array(
			      "type" => "colorpicker",
			      "heading" => __("Background Color", "js_composer"),
			      "param_name" => "bg_color",
			      "description" => __("Choose background color in hex format.", "js_composer")
			    ),
			    array(
			      		"type" => "attach_image",
					    "heading" => __("Background Image or Texture", "js_composer"),
					    "param_name" => "image",
					    "value" => "",
					    "admin_label" => true,
					    "description" => __("Select an image from media library that will be used as your background image or texture depending on value in Background Type.", "js_composer")
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Enable Parallax effect for image?", "js_composer"),
			      "param_name" => "parallax",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Optional Scissors Icon?", "js_composer"),
			      "param_name" => "scissors_icon",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    array(
			      		"type" => "textfield",
					    "heading" => __("Background Video MP4", "js_composer"),
					    "param_name" => "bg_video_mp4",
					    "value" => "",
					    "description" => __("If you have chosen \"Background Video\" for the Background Type, then upload your video in MP4 format here. ", "js_composer")
			    ),
			    array(
			      		"type" => "textfield",
					    "heading" => __("Background Video OGV", "js_composer"),
					    "param_name" => "bg_video_ogv",
					    "value" => "",
					    "description" => __("If you have chosen \"Background Video\" for the Background Type, then upload your video in OGG format here. ", "js_composer")
			    ),
			    array(
			      		"type" => "attach_image",
					    "heading" => __("Background Video Poster", "js_composer"),
					    "param_name" => "poster",
					    "value" => "",
					    "description" => __("Select an image that will be used as a placeholder until video is loaded (or cannot be loaded). ", "js_composer")
			    ),
			    array(
			      "type" => 'checkbox',
			      "heading" => __("Video Texture Overlay?", "js_composer"),
			      "param_name" => "overlay",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
			    $add_margin_top,
		       	$add_margin_bottom,
		        array(
		                "type" => "textfield",
		                "heading" => __("Inside Padding Top", "js_composer"),
		                "param_name" => "padding_top",
		                "group" => "Position",
		                "value" => "30",
		                "description" => __("Enter top padding for this section in pixels. Leave empty for default value. No need to write px. Eg: 50", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Inside Padding Bottom", "js_composer"),
		                "param_name" => "padding_bottom",
		                "group" => "Position",
		                "value" => "30",
		                "description" => __("Enter bottom padding for this section in pixels. Leave empty for default value. No need to write px. Eg: 50", "js_composer")
		        ),
		        $add_class,
		    ),
		));
		// END FW SECTION --------------------------------

		// MILESTONE COUNTER -----------------------------
		vc_map( array(
			"name" => __("Milestone Counter", "js_composer"),
			"base" => "counter",
		  	"icon" => "icon-counter",
		  	"wrapper_class" => "hb-wrapper-counter",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Place lovely customisable number counter.', 'js_composer'),
		    "params"	=> array(
		    	array(
		                "type" => "textfield",
		                "heading" => __("Start Number", "js_composer"),
		                "param_name" => "from",
		                "admin_label" => true,
		                "value" => "0",
		                "description" => __("Enter a start number for the counter. Counting will begin from this number. This value has to be numerical. Example: 0", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("End Number", "js_composer"),
		                "param_name" => "to",
		                "admin_label" => true,
		                "value" => "1250",
		                "description" => __("Enter an end number for the counter. Counting will end on this number. This value has to be a numerical. Example: 1250", "js_composer")
		        ),
		        $add_icon,
		        array(
		                "type" => "textfield",
		                "heading" => __("Subtitle", "js_composer"),
		                "param_name" => "subtitle",
		                "admin_label" => true,
		                "value" => "My Subtitle",
		                "description" => __("A word, or short text to display below the counter. Example: Apples Eaten", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Counter Speed", "js_composer"),
		                "param_name" => "speed",
		                "value" => "700",
		                "description" => __("Enter counter speed value in miliseconds. Example: 700", "js_composer")
		        ),
		        array(
			      "type" => "colorpicker",
			      "heading" => __("Focus Color", "js_composer"),
			      "param_name" => "color",
			      "description" => __("Choose a color in hex format for this element.", "js_composer")
			    ),
				$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END MILESTONE COUNTER -------------------------
		
		// IMAGE FRAME -----------------------------------
		vc_map( array(
			"name" => __("Image Frame", "js_composer"),
			"base" => "image_frame",
		  	"icon" => "icon-image-frame",
		  	"wrapper_class" => "hb-wrapper-image-frame",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Insert a regular image with additional options.', 'js_composer'),
		    "params"	=> array(
		    	array(
			      		"type" => "attach_image",
					    "heading" => __("Image", "js_composer"),
					    "param_name" => "url",
					    "value" => "",
					    "description" => __("Upload your image here. Make sure to choose size also... ", "js_composer")
			    ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Image Border Style", "js_composer"),
					"param_name" => "border_style",
					"admin_label" => true,
					"value" => array(
		               	__("Without frame", "js_composer") => 'no-frame',
		               	__("Circle frame", "js_composer") => 'circle-frame',
						__("Boxed frame", "js_composer") => 'boxed-frame',
						__("Boxed frame with hover", "js_composer") => 'boxed-frame-hover',
					),
					"description" => __("Choose an image border style.", "js_composer"),
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("On Click Action", "js_composer"),
					"param_name" => "action",
					"admin_label" => true,
					"value" => array(
		               	__("Do nothing", "js_composer") => 'none',
						__("Open lightbox", "js_composer") => 'open-lightbox',
						__("Open url in same tab", "js_composer") => 'open-url',
					),
					"description" => __("Choose what to do when clicked on image.", "js_composer"),
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("URL Link", "js_composer"),
		                "param_name" => "link",
		                "value" => "",
		                "description" => __("Enter URL where it will lead when clicked on the image. On Click Action has to be \"Open URL\". You need to enter full website address with http:// prefix.", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Image Caption", "js_composer"),
		                "param_name" => "image_caption",
		                "value" => "",
		                "description" => __("Add optional image caption here.", "js_composer")
		        ),
		        array(
		                "type" => "textfield",
		                "heading" => __("Gallery REL attribute", "js_composer"),
		                "param_name" => "rel",
		                "value" => "",
		                "description" => __("If you want to open this image in lightbox gallery along with other images, then enter the same Gallery REL for all those images. Example: my-gal", "js_composer")
		        ),
				$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END IMAGE FRAME --------------------------------

		// IMAGE BANNER -----------------------------------
		vc_map( array(
			"name" => __("Image Banner", "js_composer"),
			"base" => "image_banner",
		  	"icon" => "icon-image-frame",
		  	"wrapper_class" => "hb-wrapper-image-frame",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Insert an image banner.', 'js_composer'),
		    "params"	=> array(
		    	array(
			      		"type" => "attach_image",
					    "heading" => __("Image", "js_composer"),
					    "param_name" => "url",
					    "value" => "",
					    "description" => __("Upload your image here. Make sure to choose size also... The dimensions of a banner depends on your image size.", "js_composer")
			    ),
			    array(
				      "type" => "textarea_html",
				      "heading" => __("Banner Content", "js_composer"),
				      "param_name" => "content",
				      "value" => __("<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>", "js_composer")
			    ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Text Color", "js_composer"),
					"param_name" => "text_color",
					"admin_label" => true,
					"value" => array(
						__("Dark", "js_composer") => 'dark',
						__("Light", "js_composer") => 'light',
						),
					"description" => __("Choose your text color style.", "js_composer")
		        ),
				$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END IMAGE BANNER ------------------------

		// TITLE -----------------------------------
		vc_map( array(
			"name" => __("Title", "js_composer"),
			"base" => "title",
		  	"icon" => "icon-title",
		  	"wrapper_class" => "hb-wrapper-title",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Place a title.', 'js_composer'),
		    "params"	=> array(
			    array(
					"type" => "textarea",
				      "heading" => __("Title Content", "js_composer"),
				      "param_name" => "content",
				      "admin_label" => true,
				      "value" => __("Enter your title here", "js_composer")
			    ),
			    array(
			      	"type" => "colorpicker",
			      	"heading" => __("Text Color", "js_composer"),
			      	"param_name" => "color",
			      	"admin_label" => true,
			      	"description" => __("Choose a color in hex format for this element.", "js_composer")
			    ),
		    	array(
					"type" => "dropdown",
					"heading" => __("Title Type", "js_composer"),
					"param_name" => "type",
					"admin_label" => true,
					"value" => array(
						__("Extra Large", "js_composer") => 'extra-large',
						__("H1", "js_composer") => 'h1',
						__("H2", "js_composer") => 'h2',
						__("H3", "js_composer") => 'h3',
						__("H4", "js_composer") => 'h4',
						__("H5", "js_composer") => 'h5',
						__("H6", "js_composer") => 'h6',
						__("Modern H1", "js_composer") => 'modern-h1',
						__("Modern H2", "js_composer") => 'modern-h2',
						__("Modern H3", "js_composer") => 'modern-h3',
						__("Modern H4", "js_composer") => 'modern-h4',
						__("Modern H5", "js_composer") => 'modern-h5',
						__("Modern H6", "js_composer") => 'modern-h6',
						__("Modern H2 Large", "js_composer") => 'modern-h2-large',
						__("Special H3", "js_composer") => 'special-h3',
						__("Special H3 Left", "js_composer") => 'special-h3-left',
						__("Special H3 Right", "js_composer") => 'special-h3-right',
						__("Special H4", "js_composer") => 'special-h4',
						__("Special H4 Left", "js_composer") => 'special-h4-left',
						__("Special H4 Right", "js_composer") => 'special-h4-right',
						__("Fancy H1", "js_composer") => 'fancy-h1',
						__("Fancy H2", "js_composer") => 'fancy-h2',
						__("Fancy H3", "js_composer") => 'fancy-h3',
						__("Fancy H4", "js_composer") => 'fancy-h4',
						__("Fancy H5", "js_composer") => 'fancy-h5',
						__("Fancy H6", "js_composer") => 'fancy-h6',
						__("Subtitle H3", "js_composer") => 'subtitle-h3',
						__("Subtitle H6", "js_composer") => 'subtitle-h6',
						__("Special H6", "js_composer") => 'special-h6',
						),
					"description" => __("Choose your title heading style.", "js_composer")
		        ),
				array(
					"type" => "dropdown",
					"heading" => __("Alignment", "js_composer"),
					"param_name" => "align",
					"admin_label" => true,
					"value" => array(
						__("Left", "js_composer") => 'left',
						__("Center", "js_composer") => 'center',
						__("Right", "js_composer") => 'right',
					),
		        ),
				$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END TITLE -------------------------------------


		// TEASER ----------------------------------------
		vc_map( array(
			"name" => __("Teaser Box", "js_composer"),
			"base" => "teaser",
		  	"icon" => "icon-teaser",
		  	"wrapper_class" => "hb-wrapper-teaser",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Insert a teaser box.', 'js_composer'),
		    "params"	=> array(
		    	array(
			      	"type" => "attach_image",
					"heading" => __("Teaser Image", "js_composer"),
					"param_name" => "image",
					"value" => "",
					"description" => __("Upload a teaser image. Leave empty to hide the image section of the teaser.", "js_composer")
			    ),
			    array(
					"type" => "dropdown",
					"heading" => __("Content Alignment", "js_composer"),
					"param_name" => "align",
					"value" => array(
						__("Left", "js_composer") => 'alignleft',
						__("Center", "js_composer") => 'aligncenter',
						__("Right", "js_composer") => 'alignright'
						),
					"description" => __("Choose teaser content alignment.", "js_composer")
		        ),
		        array(
					"type" => "dropdown",
					"heading" => __("Teaser Box Style", "js_composer"),
					"param_name" => "style",
					"admin_label" => true,
					"value" => array(
						__("Boxed", "js_composer") => 'boxed',
						__("Unboxed", "js_composer") => 'alternative',
						),
					"description" => __("Choose teaser style. Choose between a boxed or unboxed alternative.", "js_composer")
		        ),
			    array(
				      "type" => "textfield",
				      "heading" => __("Teaser Title", "js_composer"),
				      "param_name" => "title",
				      "admin_label" => true,
				      "value" => 'My teaser box',
				      "value" => __("Enter your title here", "js_composer")
			    ),
			    array(
				      "type" => "textarea_html",
				      "heading" => __("Teaser Content", "js_composer"),
				      "param_name" => "content",
				      "value" => __("<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>", "js_composer")
			    ),
			    array(
				      "type" => "textfield",
				      "heading" => __("Button Title", "js_composer"),
				      "param_name" => "button_title",
				      "value" => "",
				      "description" => __("Enter the title/caption for the title button. Leave empty if you don't need one", "js_composer")
			    ),
			    array(
				      "type" => "textfield",
				      "heading" => __("Button Link", "js_composer"),
				      "param_name" => "button_link",
				      "value" => "",
				      "description" => __("Choose URL of the link for the button. Enter with http:// prefix. You can also enter section id with # prefix to scroll to the section within your page. Example #home", "js_composer")
			    ),
			     array(
			      "type" => 'checkbox',
			      "heading" => __("Open link in new tab?", "js_composer"),
			      "param_name" => "new_tab",
			      "value" => Array(__("Yes, please", "js_composer") => 'yes')
			    ),
				$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END TEASER ------------------------------------

		// MENU PRICING ITEM ----------------------------------------
		vc_map( array(
			"name" => __("Menu Pricing Item", "js_composer"),
			"base" => "menu_pricing_item",
		  	"icon" => "icon-menu-pricing-item",
		  	"wrapper_class" => "hb-wrapper-menu-pricing-item",
		  	"category" => __('Highend Shortcodes', 'js_composer'),
		  	"description" => __('Insert a menu pricing item.', 'js_composer'),
		    "params"	=> array(
			    array(
				      "type" => "textfield",
				      "heading" => __("Menu Pricing Item Title", "js_composer"),
				      "param_name" => "title",
				      "admin_label" => true,
				      "value" => 'My Menu item',
				      "value" => __("Enter your title here", "js_composer")
			    ),
			    array(
				      "type" => "textfield",
				      "heading" => __("Menu Pricing Item Content", "js_composer"),
				      "param_name" => "text",
				      "value" => "",
			    ),
			    array(
				      "type" => "textfield",
				      "heading" => __("Menu Pricing Item Price", "js_composer"),
				      "param_name" => "price",
				      "value" => "$2.99",
				      "description" => __("Enter the menu item price. Use your desided currency.", "js_composer")
			    ),
				$add_css_animation,
			    $add_animation_delay,
			    $add_class,
		    ),
		));
		// END MENU PRICING ITEM ------------------------------------

	}



	// VC MAP UPDATE
	if ( function_exists('vc_map_update')  ) {
		$setting = array ('category' => 'VC Shortcodes');
		
		vc_map_update('vc_facebook', $setting);
		vc_map_update('vc_tweetmeme', $setting);
		vc_map_update('vc_pinterest', $setting);
		vc_map_update('vc_googleplus', $setting);
		vc_map_update('vc_widget_sidebar', $setting);
		vc_map_update('vc_raw_html', $setting);
		vc_map_update('vc_raw_js', $setting);
		vc_map_update('vc_row', $setting);
		vc_map_update('vc_column_text', $setting);
		vc_map_update('vc_separator', $setting);
		vc_map_update('vc_text_separator', $setting);
		vc_map_update('vc_message', $setting);
		vc_map_update('vc_single_image', $setting);
		vc_map_update('vc_toggle', $setting);
		vc_map_update('vc_gallery', $setting);
		vc_map_update('vc_images_carousel', $setting);
		vc_map_update('vc_tabs', $setting);
		vc_map_update('vc_accordion', $setting);
		vc_map_update('vc_carousel', $setting);
		vc_map_update('vc_button2', $setting);
		vc_map_update('vc_button', $setting);
		vc_map_update('vc_cta_button', $setting);
		vc_map_update('vc_video', $setting);
		vc_map_update('vc_flickr', $setting);
		vc_map_update('vc_empty_space', $setting);
		vc_map_update('vc_custom_heading', $setting);
	}

	// VC ADD PARAM
    if ( function_exists('vc_add_param') ) {

    	 vc_add_param ( 'vc_custom_heading' ,
                array(
					"type" => 'checkbox',
					"heading" => __("Enable typewriter effect?", "js_composer"),
					"param_name" => "tw_enable",
					"group" => "Typewriter",
                    "description" => __("Enable a Typewriter animation for your Heading.<br/>To get different strings to animate break the <strong>Heading text</strong> into <strong>new lines</strong>.<br/>Preferably, leave the <strong>Element tag</strong> set to <strong>div</strong>.", "js_composer"),
					"value" => Array(__("Yes, please", "js_composer") => 'yes'),
				)
            );

    	vc_add_param ( 'vc_custom_heading' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Prefix", "js_composer"),
                    "param_name" => "tw_prefix",
                    "admin_label" => true,
                    "group" => "Typewriter",
                    "description" => __("Add a static prefix to your Typewriter.", "js_composer"),
                    "dependency" => Array("element" => "tw_enable", "value" => array("yes")),
                )
            );

    	vc_add_param ( 'vc_custom_heading' ,
                array(
					"type" => 'checkbox',
					"heading" => __("Highlight Text?", "js_composer"),
					"param_name" => "tw_highlight",
					"group" => "Typewriter",
					"value" => Array(__("Yes, please", "js_composer") => 'yes'),
                    "dependency" => Array("element" => "tw_enable", "value" => array("yes")),
				)
            );

    	vc_add_param ( 'vc_custom_heading' ,
			array(
	            "type" => "colorpicker",
	            "heading" => __("Highlight Color", "js_composer"),
	            "param_name" => "tw_highlight_color",
			    "param_holder_class" => 'vc_col-sm-4',
	            "std" => "#888",
	            "description" => __("Highlight color that will appear behind the text.", "js_composer"),
	            "dependency" => Array("element" => "tw_highlight", "value" => array("yes")),
				"group" => "Typewriter",
	        )
		);

		vc_add_param ( 'vc_custom_heading' ,
			array(
	            "type" => "colorpicker",
	            "heading" => __("Highlight Text Color", "js_composer"),
	            "param_name" => "tw_highlight_text_color",
	            "std" => "#FFF",
			    "param_holder_class" => 'vc_col-sm-4',
	            "description" => __("Highlight color of the text.", "js_composer"),
	            "dependency" => Array("element" => "tw_highlight", "value" => array("yes")),
				"group" => "Typewriter",
	        )
		);

		vc_add_param ( 'vc_custom_heading' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Speed", "js_composer"),
                    "param_name" => "tw_speed",
                    "value" => "100",
                    "admin_label" => true,
                    "group" => "Typewriter",
                    "description" => __("Set the typewriter speed in ms. (Number only).", "js_composer"),
                    "dependency" => Array("element" => "tw_enable", "value" => array("yes")),
                )
            );

		vc_add_param ( 'vc_custom_heading' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Back Delay", "js_composer"),
                    "param_name" => "tw_backdelay",
                    "value" => "900",
                    "admin_label" => true,
                    "group" => "Typewriter",
                    "description" => __("Set the time in ms the typewriter pauses before typing new word. (Number only).", "js_composer"),
                    "dependency" => Array("element" => "tw_enable", "value" => array("yes")),
                )
            );

		vc_add_param ( 'vc_custom_heading' ,
                array(
					"type" => 'checkbox',
					"heading" => __("Loop Typewriter?", "js_composer"),
					"param_name" => "tw_loop",
					"group" => "Typewriter",
					"std" => "yes",
					"value" => Array(__("Yes, please", "js_composer") => 'yes'),
                    "description" => __("Loop the typewritter process.", "js_composer"),
                    "dependency" => Array("element" => "tw_enable", "value" => array("yes")),
				)
            );

		vc_add_param ( 'vc_custom_heading' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Loop Count", "js_composer"),
                    "param_name" => "tw_loop_count",
                    "value" => "",
                    "admin_label" => true,
                    "group" => "Typewriter",
                    "description" => __("Choose how many times to repeat the loop.", "js_composer"),
                    "dependency" => Array("element" => "tw_loop", "value" => array("yes")),
                )
            );

        vc_add_param ( 'vc_accordion_tab' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Icon", "js_composer"),
                    "param_name" => "icon",
                    "admin_label" => true,
                    "value" => "hb-moon-plus-circle",
                    "description" => __("Enter a name of the icon you would like to use. Leave empty if you don't want an icon. You can find list of icons here: <a href='http://documentation.hb-themes.com/icons/' target='_blank'>Icon List</a>.
        Example: hb-moon-apple-fruit", "js_composer")
                )
            );

        vc_add_param ( 'vc_tab' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Icon", "js_composer"),
                    "param_name" => "icon",
                    "value" => "hb-moon-plus-circle",
                    "admin_label" => true,
                    "description" => __("Enter a name of the icon you would like to use. Leave empty if you don't want an icon. You can find list of icons here: <a href='http://documentation.hb-themes.com/icons/' target='_blank'>Icon List</a>.
        Example: hb-moon-apple-fruit", "js_composer")
                )
            );

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => "dropdown",
			  	"heading" => __("Entrance Animation", "js_composer"),
			  	"param_name" => "animation",
			  	"admin_label" => true,
			  	"value" => array(__("None", "js_composer") => '', __("Fade In", "js_composer") => "fade-in", __("Scale Up", "js_composer") => "scale-up", __("Scale Down", "js_composer") => "scale-down", __("Right to Left", "js_composer") => "right-to-left", __("Left to Right", "js_composer") => "left-to-right", __("Top to Bottom", "js_composer") => "top-to-bottom", __("Bottom to Top", "js_composer") => "bottom-to-top", __("Helix", "js_composer") => "helix", __("Flip-X", "js_composer") => "flip-x",  __("Flip-Y", "js_composer") => "flip-y",  __("Spin", "js_composer") => "spin"),
			  	"description" => __("Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "js_composer")
			)
        );
        vc_add_param ( 'vc_row' , 
        	array(
				"type" => "textfield",
				"heading" => __("Entrance Delay", "js_composer"),
				"param_name" => "animation_delay",
				"value" => "",
				"description" => __("Enter delay in miliseconds before the animation starts. Useful for creating timed animations. No need to enter ms. Eg: 300 (300 stands for 0.3 seconds)", "js_composer")
			)
        );

        vc_add_param ( 'vc_row' ,
                array(
                    "type" => "dropdown",
                    "heading" => __("Fullwidth Section", "js_composer"),
                    "param_name" => "fullwidth",
                    "value" => array(
						__("Disable", "js_composer") => 'no',
						__("Enable", "js_composer") => 'yes',
						),
                    "group" => "Fullwidth Section",
					"description" => __("Make this row fullwidth?", "js_composer")
                )
            );

        vc_add_param ( 'vc_row' ,
                array(
                    "type" => "dropdown",
                    "heading" => __("Background type", "js_composer"),
                    "param_name" => "bg_type",
                    "value" => array(
						__("Solid Color", "js_composer") => 'solid_color',
						__("Gradient", "js_composer") => 'gradient',
						__("Image/Parallax", "js_composer") => 'bg_image',
						__("Hosted Video", "js_composer") => 'bg_video',
						),
                    "group" => "Fullwidth Section",
                    "dependency" => Array("element" => "fullwidth", "value" => array("yes")),
                    "description" => __("Select the kind of background would you like to set for this row.", "js_composer"),
                )
            );

        vc_add_param ( 'vc_row' ,
			array(
	            "type" => "dropdown",
	            "heading" => __("Gradient Overlay Orientation", "js_composer"),
	            "param_name" => "gr_orientation",
	            "value" => array(
	                __('Vertical ↓', "js_composer") => "vertical",
	                __('Horizontal →', "js_composer") => "horizontal",
	                __('Diagonal ↘', "js_composer") => "left_top",
	                __('Diagonal ↗', "js_composer") => "left_bottom",
	                __('Radial ○', "js_composer") => "radial",
	            ),
	            "std" => "vertical",
	            "description" => __("Choose the orientation of gradient overlay", "js_composer"),
	            "dependency" => Array("element" => "bg_type", "value" => array("gradient")),
	            "group" => "Fullwidth Section",
        	)
		);

        vc_add_param ( 'vc_row' ,
			array(
	            "type" => "colorpicker",
	            "heading" => __("Gradient Color #1", "js_composer"),
	            "param_name" => "first_gr_color",
	            "std" => "#c9de96",
	            "description" => __("The starting color of gradient fill.", "js_composer"),
	            "dependency" => Array("element" => "bg_type", "value" => array("gradient")),
	            "group" => "Fullwidth Section",
	        )
		);

		vc_add_param ( 'vc_row' ,
	        array(
	            "type" => "colorpicker",
	            "heading" => __("Gradient Color #2", "js_composer"),
	            "param_name" => "second_gr_color",
	            "std" => "#327731",
	            "description" => __("The ending color of gradient fill.", "js_composer"),
	            "dependency" => Array("element" => "bg_type", "value" => array("gradient")),
	            "group" => "Fullwidth Section",
	        )
		);

		vc_add_param ( 'vc_row' ,
			array(
				"type" => "colorpicker",
			    "heading" => __("Section Background", "js_composer"),
			    "param_name" => "background_color",
			    "group" => "Fullwidth Section",
			    "description" => __("Choose a background color for this section. Leave empty for default color.", "js_composer"),
			    "dependency" => Array("element" => "fullwidth", "value" => array("yes")),
			)
		);

        vc_add_param ( 'vc_row' ,
                array(
                    "type" => "attach_image",
                    "heading" => __("Upload background image", "js_composer"),
                    "param_name" => "image_url",
                    "group" => "Fullwidth Section",
                    "dependency" => Array("element" => "bg_type", "value" => array("bg_image")),
                    "description" => __("Upload background image for this row.", "js_composer"),
                )
            );

        vc_add_param ( 'vc_row' ,
                array(
                    "type" => "dropdown",
                    "heading" => __("Background repeat", "js_composer"),
                    "param_name" => "bg_repeat",
                    "value" => array(
						__("No repeat", "js_composer") => 'no-repeat',
						__("Repeat", "js_composer") => 'repeat',
						),
                    "group" => "Fullwidth Section",
                    "dependency" => Array("element" => "bg_type", "value" => array("bg_image")),
                    "description" => __("Use repeat for textures and no repeat for images.", "js_composer"),
                )
            );

        vc_add_param ( 'vc_row' ,
                array(
					"type" => 'checkbox',
					"heading" => __("Enable parallax effect?", "js_composer"),
					"param_name" => "parallax",
					"group" => "Fullwidth Section",
					"value" => Array(__("Yes, please", "js_composer") => 'yes'),
					"dependency" => Array("element" => "bg_type", "value" => array("bg_image")),
				)
            );

        vc_add_param ( 'vc_row' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Link to the video in MP4 format", "js_composer"),
                    "param_name" => "video_mp4",
                    "group" => "Fullwidth Section",
                    "dependency" => Array("element" => "bg_type", "value" => array("bg_video")),
                    "description" => __("Enter the URL of your video in MP4 format. You can upload a video through <a href='".home_url()."/wp-admin/media-new.php' target='_blank'>WordPress Media Library</a> if you haven't done that already.", "js_composer"),
                )
            );

        vc_add_param ( 'vc_row' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Link to the video in WEBM/OGG format", "js_composer"),
                    "param_name" => "video_webm",
                    "group" => "Fullwidth Section",
                    "dependency" => Array("element" => "bg_type", "value" => array("bg_video")),
                    "description" => __("IE, Chrome & Safari <a href='http://www.w3schools.com/html/html5_video.asp' target='_blank'>support</a> MP4 format, while Firefox & Opera prefer WebM / Ogg formats. You can upload the video through <a href='".home_url()."/wp-admin/media-new.php' target='_blank'>WordPress Media Library</a>.", "js_composer"),
                )
            );

        vc_add_param ( 'vc_row' ,
                array(
                    "type" => "attach_image",
                    "heading" => __("Upload placeholder image", "js_composer"),
                    "param_name" => "video_poster",
                    "group" => "Fullwidth Section",
                    "dependency" => Array("element" => "bg_type", "value" => array("bg_video")),
                    "description" => __("Upload placeholder image for your video. This image will be used when the video is loading or when it is not possible to display the video.", "js_composer"),
                )
            );

        vc_add_param ( 'vc_row' ,
        	array(
				"type" => 'checkbox',
				"heading" => __("Enable video texture overlay?", "js_composer"),
				"param_name" => "video_texture",
				"group" => "Fullwidth Section",
				"value" => Array(__("Yes, please", "js_composer") => 'yes'),
				"dependency" => Array("element" => "bg_type", "value" => array("bg_video")),
			)
		);

		vc_add_param ( 'vc_row' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Section ID", "js_composer"),
                    "param_name" => "section_id",
                    "group" => "One Page Section",
                    "description" => __("Enter a UNIQUE section id if needed. This is required if you are creating One Page websites module, as this will be used for a navigation. For example, if you have entered first-page in this field, in your menu, you would enter #first-page to link to this row.", "js_composer"),
                )
            );

		vc_add_param ( 'vc_row' ,
                array(
                    "type" => "textfield",
                    "heading" => __("Section Title", "js_composer"),
                    "param_name" => "section_title",
                    "group" => "One Page Section",
                    "description" => __("Enter title for this section. It will be used in left circle navigation on one page websites.", "js_composer"),
                )
            );

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => 'checkbox',
				"heading" => __("Enable fullwidth columns inside this row?", "js_composer"),
				"param_name" => "fw_columns",
				"group" => "Fullwidth Section",
				"value" => Array(__("Yes, please", "js_composer") => 'yes'),
				"dependency" => Array("element" => "fullwidth", "value" => array("yes")),
			)
		);

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => 'checkbox',
				"heading" => __("Make fullwidth columns same height?", "js_composer"),
				"param_name" => "fw_same_height",
				"group" => "Fullwidth Section",
				"value" => Array(__("Yes, please", "js_composer") => 'yes'),
				"dependency" => Array("element" => "fw_columns", "value" => array("yes")),
			)
		);

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => 'checkbox',
				"heading" => __("Enable line border?", "js_composer"),
				"param_name" => "border",
				"group" => "Fullwidth Section",
				"value" => Array(__("Yes, please", "js_composer") => 'yes'),
				"dependency" => Array("element" => "fullwidth", "value" => array("yes")),
			)
		);

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => 'checkbox',
				"heading" => __("Waved top border?", "js_composer"),
				"param_name" => "waved_border_top",
				"group" => "Fullwidth Section",
				"value" => Array(__("Yes, please", "js_composer") => 'yes'),
				"dependency" => Array("element" => "fullwidth", "value" => array("yes")),
			)
		);

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => 'checkbox',
				"heading" => __("Waved bottom border?", "js_composer"),
				"param_name" => "waved_border_bottom",
				"group" => "Fullwidth Section",
				"value" => Array(__("Yes, please", "js_composer") => 'yes'),
				"dependency" => Array("element" => "fullwidth", "value" => array("yes")),
			)
		);

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => "number",
				"class" => "",
				"heading" => __("Top", "hbthemes"),
				"param_name" => "top_margin",
				"value" => 0,
				"min" => -1000,
				"max" => 1000,
				"suffix" => "px",
				"group" => "Positioning",
				"description" => __("Enter the top position offset. You can use negative values also. Example: -60", "hbthemes"),
			)
		);

		vc_add_param ( 'vc_row' ,
        	array(
				"type" => "number",
				"class" => "",
				"heading" => __("Bottom", "hbthemes"),
				"param_name" => "bottom_margin",
				"value" => 0,
				"min" => -1000,
				"max" => 1000,
				"suffix" => "px",
				"group" => "Positioning",
				"description" => __("Enter the bottom position offset. You can use negative values also. Example: -70", "hbthemes"),
			)
		);        

       vc_add_param ( 'vc_toggle' ,
                array(
                    "type" => "textfield",
                    "value" => "hb-moon-plus-circle",
                    "heading" => __("Icon", "js_composer"),
                    "param_name" => "icon",
                    "admin_label" => true,
                    "description" => __("Enter a name of the icon you would like to use. Leave empty if you don't want an icon. You can find list of icons here: <a href='http://documentation.hb-themes.com/icons/' target='_blank'>Icon List</a>.
        Example: hb-moon-apple-fruit", "js_composer")
                )
            );

        vc_add_param ( 'vc_column' , $add_css_animation);
        vc_add_param ( 'vc_column' , $add_animation_delay);

        // Function generate param type "number"
		function number_settings_field($settings, $value){
			//$dependency = vc_generate_dependencies_attributes($settings);
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type = isset($settings['type']) ? $settings['type'] : '';
			$min = isset($settings['min']) ? $settings['min'] : '';
			$max = isset($settings['max']) ? $settings['max'] : '';
			$step = isset($settings['step']) ? $settings['step'] : '';
			$suffix = isset($settings['suffix']) ? $settings['suffix'] : '';
			$class = isset($settings['class']) ? $settings['class'] : '';
			$output = '<input type="number" min="'.$min.'" max="'.$max.'" step="'.$step.'" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.$value.'" style="max-width:100px; margin-right: 10px;" />'.$suffix;
			return $output;
		}

		// Generate param type "number"
		if ( function_exists('vc_add_shortcode_param')){
			vc_add_shortcode_param('number' , 'number_settings_field' );
		}
    }
?>