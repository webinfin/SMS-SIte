<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

 /* Function to get shortcode design
 * 
 * @package WP Testimonials with rotator widget
 * @since 2.2.5
 */
function wptww_designs() {
	$design_arr = array(
		'design-1'	=> __('Design 1', 'wp-testimonial-with-widget'),
		'design-2'	=> __('Design 2', 'wp-testimonial-with-widget'),
		'design-3'	=> __('Design 3', 'wp-testimonial-with-widget'),
		'design-4'	=> __('Design 4', 'wp-testimonial-with-widget'),	
		);	
	return apply_filters('wptww_designs', $design_arr );
}
 /* Function to register col
 * 
 * @package WP Testimonials with rotator widget
 * @since 2.2.5
 */
add_filter( 'manage_edit-testimonial_columns',  'register_custom_column_headings' );
add_action( 'manage_posts_custom_column',  'register_custom_columns' );
function register_custom_columns ( $column_name ) {
		global $wpdb, $post;		
		switch ( $column_name ) {
			case 'image':
				$value = '';
				$value = get_image( get_the_ID(), 40 ,'square');
				echo $value;
			break;
			default:
			break;
		}
	}
	function register_custom_column_headings ( $defaults ) {
		$new_columns = array( 'image' => __( 'Image', 'wp-testimonial-with-widget' ) );
		$last_item = '';
		if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }
		if ( count( $defaults ) > 2 ) {
			$last_item = array_slice( $defaults, -1 );
			array_pop( $defaults );
		}
		$defaults = array_merge( $defaults, $new_columns );
		if ( $last_item != '' ) {
			foreach ( $last_item as $k => $v ) {
				$defaults[$k] = $v;
				break;
			}
		}
		return $defaults;
	}
	function get_image ( $id, $size, $style = "" ) {
		if($style == "circle")
			{
				$image_class= "wptww-circle";
			} else {
				$image_class= "wptww-square";
			}
		$response = '';
		if ( has_post_thumbnail( $id ) ) {			
			if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
				$size = array( intval( $size ), intval( $size ) );
			} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
				$size = array( 100, 100 );
			}
			$response = get_the_post_thumbnail( intval( $id ), $size, array( 'class' => $image_class ) );
		} else {
			$testimonial_email = get_post_meta( $id, '_testimonial_email', true );
			if ( '' != $testimonial_email && is_email( $testimonial_email ) ) {
				$response = get_avatar( $testimonial_email, $size );
			}
		}
		return $response;
	}
// Manage Category Shortcode Columns
add_filter("manage_testimonial-category_custom_column", 'testimonial_category_columns', 10, 3);
add_filter("manage_edit-testimonial-category_columns", 'testimonial_category_manage_columns'); 
function testimonial_category_manage_columns($theme_columns) {
    $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'testimonial_shortcode' => __( 'Testimonial Category Shortcode', 'wp-testimonial-with-widget' ),
            'slug' => __('Slug'),
            'posts' => __('Posts')
			);
    return $new_columns;
}

function testimonial_category_columns($out, $column_name, $theme_id) {
    $theme = get_term($theme_id, 'testimonial-category');
    switch ($column_name) {      

        case 'title':
            echo get_the_title();
        break;
        case 'testimonial_shortcode':
		echo '[sp_testimonials category="' . $theme_id. '"]<br />';
		echo '[sp_testimonials_slider category="' . $theme_id. '"]';
        break;

        default:
            break;
    }
    return $out;   

}	
add_action( 'admin_menu', 'meta_box_setup');
add_action( 'save_post','meta_box_save');
	function meta_box_setup () {
		add_meta_box( 'testimonial-details', __( 'Testimonial Details', 'wp-testimonial-with-widget' ), 'meta_box_content' , 'testimonial', 'normal', 'high' );
	}
	function meta_box_content () {

		global $post_id;
		$fields = get_post_custom( $post_id );
		$field_data = get_custom_fields_settings();

		$html = '';
		$html .= wp_nonce_field( 'meta_box_save', 'sp_testimonial_noonce' );
		if ( 0 < count( $field_data ) ) {
			$html .= '<table class="form-table">' . "\n";
			$html .= '<tbody>' . "\n";

			foreach ( $field_data as $k => $v ) {
				$data = $v['default'];
				if ( isset( $fields['_' . $k] ) && isset( $fields['_' . $k][0] ) ) {
					$data = $fields['_' . $k][0];

				}

				$html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" class="regular-text" value="' . esc_attr( $data ) . '" />' . "\n";
				$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
				$html .= '</td><tr/>' . "\n";
			}

			$html .= '</tbody>' . "\n";
			$html .= '</table>' . "\n";
		}

		echo $html;
	}
	function meta_box_save ( $post_id ) {

		global $post, $messages;
		// Verify
		if ( ( get_post_type( $post_id) != 'testimonial' ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['sp_testimonial_noonce'] ) ) {
		return $post_id;
	}
		if ( ! wp_verify_nonce( $_POST['sp_testimonial_noonce'], 'meta_box_save' ) ) {
			return $post_id;
		  }
			if ( 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}

		$field_data = get_custom_fields_settings();
		$fields = array_keys( $field_data );

		foreach ( $fields as $f ) {

			${$f} = strip_tags(trim($_POST[$f]));
			//echo '<pre>';print_r(${$f});die;
			// Escape the URLs.
			if ( 'url' == $field_data[$f]['type'] ) {

				${$f} = esc_url( ${$f} );
			}

			if ( get_post_meta( $post_id, '_' . $f ) == '' ) {
				

				add_post_meta( $post_id, '_' . $f, ${$f}, true );
			} elseif( ${$f} != get_post_meta( $post_id, '_' . $f, true ) ) {
				update_post_meta( $post_id, '_' . $f, ${$f} );
			} elseif ( ${$f} == '' ) {
				delete_post_meta( $post_id, '_' . $f, get_post_meta( $post_id, '_' . $f, true ) );
			}
		}
	}
	function get_custom_fields_settings () {
		$fields = array();

		$fields['testimonial_client'] = array(
		    'name' => __( 'Client Name', 'wp-testimonial-with-widget' ),
		    'description' => __( '' ),
		    'type' => 'text',
		    'default' => '',
		    'section' => 'info'
		);
		
		$fields['testimonial_job'] = array(
		    'name' => __( 'Job Title', 'wp-testimonial-with-widget' ),
		    'description' => __( '' ),
		    'type' => 'text',
		    'default' => '',
		    'section' => 'info'
		);

		$fields['testimonial_company'] = array(
		    'name' => __( 'Company', 'wp-testimonial-with-widget' ),
		    'description' => __( '' ),
		    'type' => 'text',
		    'default' => '',
		    'section' => 'info'
		);

		$fields['testimonial_url'] = array(
		    'name' => __( 'URL', 'wp-testimonial-with-widget' ),
		    'description' => __( '' ),
		    'type' => 'text',
		    'default' => '',
		    'section' => 'info'
		);

		return $fields;
	}
	function get_testimonials ( $args = '' ) {
		$defaults = array(
			'limit' => -1,
			'orderby' => 'menu_order',
			'order' => 'DESC',
			'id' => 0,
			'category' => 0,
		);

		$args = wp_parse_args( $args, $defaults );
		$query_args = array();
		$query_args['post_type'] = 'testimonial';
		$query_args['numberposts'] = $args['limit'];
		$query_args['orderby'] = $args['orderby'];
		$query_args['order'] = $args['order'];
		$query_args['suppress_filters'] = false;

		$ids = explode( ',', $args['id'] );

		if ( 0 < intval( $args['id'] ) && 0 < count( $ids ) ) {
			$ids = array_map( 'intval', $ids );
			if ( 1 == count( $ids ) && is_numeric( $ids[0] ) && ( 0 < intval( $ids[0] ) ) ) {
				$query_args['p'] = intval( $args['id'] );
			} else {
				$query_args['ignore_sticky_posts'] = 1;
				$query_args['post__in'] = $ids;
			}
		}

		// Whitelist checks.
		if ( ! in_array( $query_args['orderby'], array( 'none', 'ID', 'author', 'title', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'meta_value', 'meta_value_num' ) ) ) {
			$query_args['orderby'] = 'date';
		}

		if ( ! in_array( $query_args['order'], array( 'ASC', 'DESC' ) ) ) {
			$query_args['order'] = 'DESC';
		}

		if ( ! in_array( $query_args['post_type'], get_post_types() ) ) {
			$query_args['post_type'] = 'testimonial';
		}

		$tax_field_type = '';

		// If the category ID is specified.
		if ( is_numeric( $args['category'] ) && 0 < intval( $args['category'] ) ) {
			$tax_field_type = 'id';
		}

		// If the category slug is specified.
		if ( ! is_numeric( $args['category'] ) && is_string( $args['category'] ) ) {
			$tax_field_type = 'slug';
		}

		// Setup the taxonomy query.
		if ( '' != $tax_field_type ) {
			$term = $args['category'];
			if ( is_string( $term ) ) { $term = esc_html( $term ); } else { $term = intval( $term ); }
			$query_args['tax_query'] = array( array( 'taxonomy' => 'testimonial-category', 'field' => $tax_field_type, 'terms' => array( $term ) ) );
		}

		// The Query.
		$query = get_posts( $query_args );

		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			foreach ( $query as $k => $v ) {
				$meta = get_post_custom( $v->ID );
				// Get the image.
				$query[$k]->image = get_image( $v->ID, $args['size'],$args['image_style']);
				foreach ( (array)get_custom_fields_settings() as $i => $j ) {
					if ( isset( $meta['_' . $i] ) && ( '' != $meta['_' . $i][0] ) ) {
						$query[$k]->$i = $meta['_' . $i][0];
					} else {
						$query[$k]->$i = $j['default'];
					}
				}
			}
		} else {
			$query = false;
		}
		return $query;
	}