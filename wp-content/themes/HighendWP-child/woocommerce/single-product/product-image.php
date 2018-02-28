<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $woocommerce, $product;

?>
<div class="images hb-woo-single-images">

	<?php
		$postdate = get_the_time( 'Y-m-d' );
		$postdatestamp 	= strtotime( $postdate );
		$newness 		= 3;
				
		if ( $product->sale_price ){
			echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale', 'woocommerce' ) . '</span>', $post, $product );
		}
		else if ( hb_is_out_of_stock() ) {	
			echo '<span class="out-of-stock-badge">' . __( 'Sold out', 'hbthemes' ) . '</span>';
		} else if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) {
			echo '<span class="wc-new-badge">' . __( 'New', 'woocommerce' ) . '</span>';
		}
	?>

	<?php
		if ( has_post_thumbnail() ) {
			$image_title 		= get_the_title();
			$image_link  		= wp_get_attachment_image_src ( get_post_thumbnail_id() , 'full' );
			$image_object		= get_post_thumbnail_id();
			$attachment_count   = count( $product->get_gallery_attachment_ids() );
			$shop_single_size	= wc_get_image_size( 'shop_single' );

			$image_object		= get_post_thumbnail_id();
			$image_cropped = hb_resize ( $image_object, '', $shop_single_size['width'], $shop_single_size['height'], true);

			$image = $image_link;
			$image_print = '<img class="hb-woo-featured-image" src="' . $image_cropped['url'] . '" width="'.$image_cropped['width'].'" height="'.$image_cropped['height'].'">';

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" data-title="%s" rel="prettyPhoto' . $gallery . '">%s</a>', $image_link[0], $image_title, $image_print ), $post->ID );

		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $post->ID );

		}
		do_action( 'woocommerce_product_thumbnails' );
	?>
</div>
