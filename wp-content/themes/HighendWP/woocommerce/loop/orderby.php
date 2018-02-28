<?php
/**
 * Show options for ordering
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query;

	parse_str($_SERVER['QUERY_STRING'], $params);
	$query_string = '?'.$_SERVER['QUERY_STRING'];

	// replace it with theme option
	if( hb_options('hb_woo_count') ) {
		$per_page = hb_options('hb_woo_count');
	} else {
		$per_page = 12;
	}

if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
	return;
?>
<form class="woocommerce-ordering" method="get">
	<select name="orderby" class="orderby">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
	</select>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' === $key || 'submit' === $key ) {
				continue;
			}
			if ( is_array( $val ) ) {
				foreach( $val as $innerVal ) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
	?>
</form>
<?php
	$pc='';
	$current_count = $per_page;
	if ( isset($_GET['product_count']) ) {
		if ( $_GET['product_count'] ){
			$current_count = $_GET['product_count'];
		}
	}

	$html = '';
	$html .= '<ul class="sort-count order-dropdown">';
	$html .= '<li>';
	$html .= '<span class="current-li"><a>'.__('Show', 'woocommerce').' '.$current_count.' '.__('Products', 'woocommerce').'</a></span>';
	$html .= '<ul>';
	$html .= '<li class="'.(($pc == $per_page) ? 'current': '').'"><a href="'.hb_addURLParameter($query_string, 'product_count', $per_page).'">'.__('Show', 'woocommerce').' <strong>'.$per_page.' '.__('Products', 'woocommerce').'</strong></a></li>';
	$html .= '<li class="'.(($pc == $per_page*2) ? 'current': '').'"><a href="'.hb_addURLParameter($query_string, 'product_count', $per_page*2).'">'.__('Show', 'woocommerce').' <strong>'.($per_page*2).' '.__('Products', 'woocommerce').'</strong></a></li>';
	$html .= '<li class="'.(($pc == $per_page*3) ? 'current': '').'"><a href="'.hb_addURLParameter($query_string, 'product_count', $per_page*3).'">'.__('Show', 'woocommerce').' <strong>'.($per_page*3).' '.__('Products', 'woocommerce').'</strong></a></li>';
	$html .= '<li class="'.(($pc == $per_page*4) ? 'current': '').'"><a href="'.hb_addURLParameter($query_string, 'product_count', $per_page*4).'">'.__('Show', 'woocommerce').' <strong>'.($per_page*4).' '.__('Products', 'woocommerce').'</strong></a></li>';
	$html .= '</ul>';
	$html .= '</li>';
	$html .= '</ul>';

	echo $html;
?>
<div class="clear"></div>
<div class="hb-separator-extra shop-separator"></div>