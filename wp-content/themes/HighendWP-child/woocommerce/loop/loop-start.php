<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
global $woocommerce_loop;
// Store column count for displaying the grid
if(empty($woocommerce_loop['columns'])) {
	$woocommerce_loop['columns'] = apply_filters('loop_shop_columns', 1);
}

if(is_shop() || is_product_category() || is_product_tag()) {
	$woocommerce_loop['columns'] = 4;
}
if(isset($_GET['view']) && ($_GET['view']=='grid'))
	{
		
		$classb="gridproduct";
	}
	else
	{
		$classb="listproduct";
	}
	
?>
<div class="row products <?php echo $classb; ?> clearfix products-<?php echo $woocommerce_loop['columns']; ?>">