<?php 
	if ( have_posts() ) : while ( have_posts() ) : the_post();
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
					
				echo '<article class="search-entry clearfix">';

				if ( $thumb ) {
					$image = hb_resize( $thumb, '', 80, 80, true );
					echo '<a href="'.get_permalink().'" title="'.get_the_title().'" class="search-thumb"><img src="'.$image['url'].'" alt="'. get_the_title() .'" /></a>';
				} else {
					echo '<a href="'.get_permalink().'" title="'.get_the_title().'" class="search-thumb"><i class="'. $icon_to_use .'"></i></a>';
				}
						
				$echo_title = get_the_title();
				if ( $echo_title == "" ) $echo_title = __('No Title' , 'hbthemes' );
				echo '<h4 class="semi-bold"><a href="'.get_permalink().'" title="'.$echo_title.'">'.$echo_title.'</a></h4>';
				echo '<div class="minor-meta">'. get_the_time('M j, Y') .'</div>';
					
				echo '<div class="excerpt-wrap">';
					the_excerpt();
				echo '</div>';
						
				echo '</article>';
						
	endwhile; endif;
?>