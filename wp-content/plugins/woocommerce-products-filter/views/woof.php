<?php  if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<?php
//+++
$args = array();
$args['show_count'] = get_option('woof_show_count', 0);
$args['show_count_dynamic'] = get_option('woof_show_count_dynamic', 0);
$args['hide_dynamic_empty_pos'] = 0;
$args['woof_autosubmit'] = $autosubmit;
//***
$_REQUEST['tax_only'] = $tax_only;
$_REQUEST['tax_exclude'] = $tax_exclude;
$_REQUEST['by_only'] = $by_only;


if (!function_exists('woof_only')) {

    function woof_only($key_slug, $type = 'taxonomy') {
        //var_dump($key_slug);
        switch ($type) {
            case 'taxonomy':

                if (!empty($_REQUEST['tax_only'])) {
                    if (!in_array($key_slug, $_REQUEST['tax_only'])) {
                        return FALSE;
                    }
                }

                if (!empty($_REQUEST['tax_exclude'])) {
                    if (in_array($key_slug, $_REQUEST['tax_exclude'])) {
                        return FALSE;
                    }
                }

                break;

            case 'item':
                if (!empty($_REQUEST['by_only'])) {
                    if (!in_array($key_slug, $_REQUEST['by_only'])) {
                        return FALSE;
                    }
                }
                break;
        }


        return TRUE;
    }

}

//Sort logic  for shortcode [woof] attr tax_only
if (!function_exists('woof_print_tax')) {

    function get_order_by_tax_only($t_order, $t_only) {
        $temp_array = array_intersect($t_order, $t_only);
        $i = 0;
        foreach ($temp_array as $key => $val) {
            $t_order[$key] = $t_only[$i];
            $i++;
        }
        return $t_order;
    }

}
//***
if (!function_exists('woof_print_tax')) {

    function woof_print_tax($taxonomies, $tax_slug, $terms, $exclude_tax_key, $taxonomies_info, $additional_taxes, $woof_settings, $args, $counter) {

        global $WOOF;

        if ($exclude_tax_key == $tax_slug) {
            //$terms = apply_filters('woof_exclude_tax_key', $terms);
            if (empty($terms)) {
                return;
            }
        }

        //***

        if (!woof_only($tax_slug, 'taxonomy')) {
            return;
        }

        //***


        $args['taxonomy_info'] = $taxonomies_info[$tax_slug];
        $args['tax_slug'] = $tax_slug;
        $args['terms'] = $terms;
        $args['all_terms_hierarchy'] = $taxonomies[$tax_slug];
        $args['additional_taxes'] = $additional_taxes;

        //***
        $woof_container_styles = "";
        if ($woof_settings['tax_type'][$tax_slug] == 'radio' OR $woof_settings['tax_type'][$tax_slug] == 'checkbox') {
            if ($WOOF->settings['tax_block_height'][$tax_slug] > 0) {
                $woof_container_styles = "max-height:{$WOOF->settings['tax_block_height'][$tax_slug]}px; overflow-y: auto;";
            }
        }
        //***
        //https://wordpress.org/support/topic/adding-classes-woof_container-div
        $primax_class = sanitize_key(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]));
        ?>
        <div data-css-class="woof_container_<?php echo $tax_slug ?>" class="woof_container woof_container_<?php echo $woof_settings['tax_type'][$tax_slug] ?> woof_container_<?php echo $tax_slug ?> woof_container_<?php echo $counter ?> woof_container_<?php echo $primax_class ?>">
            <div class="woof_container_overlay_item"></div>
            <div class="woof_container_inner woof_container_inner_<?php echo $primax_class ?>">
                <?php
                $css_classes = "woof_block_html_items";
                $show_toggle = 0;
                if (isset($WOOF->settings['show_toggle_button'][$tax_slug])) {
                    $show_toggle = (int) $WOOF->settings['show_toggle_button'][$tax_slug];
                }
                //***
                $search_query = $WOOF->get_request_data();
                $block_is_closed = true;
                if (in_array($tax_slug, array_keys($search_query))) {
                    $block_is_closed = false;
                }
                if ($show_toggle === 1 AND ! in_array($tax_slug, array_keys($search_query))) {
                    $css_classes .= " woof_closed_block";
                }

                if ($show_toggle === 2 AND ! in_array($tax_slug, array_keys($search_query))) {
                    $block_is_closed = false;
                }

                if (in_array($show_toggle, array(1, 2))) {
                    $block_is_closed = apply_filters('woof_block_toggle_state', $block_is_closed);
                    if ($block_is_closed) {
                        $css_classes .= " woof_closed_block";
                    } else {
                        $css_classes = str_replace('woof_closed_block', '', $css_classes);
                    }
                }
                //***
                switch ($woof_settings['tax_type'][$tax_slug]) {
                    case 'checkbox':         // echo '<pre>';		  //print_r($taxonomies_info);
                        if ($WOOF->settings['show_title_label'][$tax_slug]) {						
						$taxonomies_info['product_cat']->labels->name;						
						$taxonomies_info['product_tag']->labels->name;						
						if($taxonomies_info['product_cat']->labels->name == "Product categories"){	
						$taxonomies_info['product_cat']->labels->name = "<span>Sort By</span> Filters"; }
						if($taxonomies_info['product_tag']->labels->name == "Product tags")	{
							$taxonomies_info['product_tag']->labels->name = "Conditions";	}
                            ?>
                            <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php
                        }

                        if (!empty($woof_container_styles)) {
                            $css_classes .= " woof_section_scrolled";
                        }
                        ?>
                        <div class="<?php echo $css_classes ?>" <?php if (!empty($woof_container_styles)): ?>style="<?php echo $woof_container_styles ?>"<?php endif; ?>>
                            <?php
                            echo $WOOF->render_html(WOOF_PATH . 'views/html_types/checkbox.php', $args);
                            ?>
                        </div>
                        <?php
                        break;
                    case 'select':
                        if ($WOOF->settings['show_title_label'][$tax_slug]) {
                            ?>
                            <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php
                        }
                        ?>
                        <div class="<?php echo $css_classes ?>">
                            <?php
                            echo $WOOF->render_html(WOOF_PATH . 'views/html_types/select.php', $args);
                            ?>
                        </div>
                        <?php
                        break;
                    case 'mselect':
                        if ($WOOF->settings['show_title_label'][$tax_slug]) {
                            ?>
                            <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php
                        }
                        ?>
                        <div class="<?php echo $css_classes ?>">
                            <?php
                            echo $WOOF->render_html(WOOF_PATH . 'views/html_types/mselect.php', $args);
                            ?>
                        </div>
                        <?php
                        break;

                    default:
                        if ($WOOF->settings['show_title_label'][$tax_slug]) {
                            $title = WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]);
                            $title = explode('^', $title); //for hierarchy drop-down and any future manipulations
                            if (isset($title[1])) {
                                $title = $title[1];
                            } else {
                                $title = $title[0];
                            }
                            ?>
                            <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo $title ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php
                        }

                        if (!empty($woof_container_styles)) {
                            $css_classes .= " woof_section_scrolled";
                        }
                        ?>

                        <div class="<?php echo $css_classes ?>" <?php if (!empty($woof_container_styles)): ?>style="<?php echo $woof_container_styles ?>"<?php endif; ?>>
                            <?php
                            if (!empty(WOOF_EXT::$includes['taxonomy_type_objects'])) {
                                $is_custom = false;
                                foreach (WOOF_EXT::$includes['taxonomy_type_objects'] as $obj) {
                                    if ($obj->html_type == $woof_settings['tax_type'][$tax_slug]) {
                                        $is_custom = true;
                                        $args['woof_settings'] = $woof_settings;
                                        $args['taxonomies_info'] = $taxonomies_info;
                                        echo $WOOF->render_html($obj->get_html_type_view(), $args);
                                        break;
                                    }
                                }


                                if (!$is_custom) {
                                    echo $WOOF->render_html(WOOF_PATH . 'views/html_types/radio.php', $args);
                                }
                            } else {
                                echo $WOOF->render_html(WOOF_PATH . 'views/html_types/radio.php', $args);
                            }
                            ?>

                        </div>
                        <?php
                        break;
                }
                ?>

                <input type="hidden" name="woof_t_<?php echo $tax_slug ?>" value="<?php echo $taxonomies_info[$tax_slug]->labels->name ?>" /><!-- for red button search nav panel -->

            </div>
        </div>
        <?php
    }

}

if (!function_exists('woof_print_item_by_key')) {

    function woof_print_item_by_key($key, $woof_settings, $additional_taxes) {

        if (!woof_only($key, 'item')) {
            return;
        }

        //***

        global $WOOF;
        switch ($key) {
            case 'by_price':
                $price_filter = 0;
                if (isset($WOOF->settings['by_price']['show'])) {
                    $price_filter = (int) $WOOF->settings['by_price']['show'];
                }
                ?>

                <?php if ($price_filter == 1): ?>
                    <div data-css-class="woof_price_search_container" class="woof_price_search_container woof_container">
                        <div class="woof_container_overlay_item"></div>
                        <div class="woof_container_inner">
                            <div class="woocommerce widget_price_filter">
                                <?php //the_widget('WC_Widget_Price_Filter', array('title' => ''));        ?>
                                <?php if (isset($WOOF->settings['by_price']['title_text']) AND ! empty($WOOF->settings['by_price']['title_text'])): ?>
                                    <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate(null, $WOOF->settings['by_price']['title_text']); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                                <?php endif; ?>
                                <?php WOOF_HELPER::price_filter(); ?>
                            </div>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                <?php endif; ?>

                <?php if ($price_filter == 2): ?>
                    <div data-css-class="woof_price2_search_container" class="woof_price2_search_container woof_container">
                        <div class="woof_container_overlay_item"></div>
                        <div class="woof_container_inner">
                            <?php if (isset($WOOF->settings['by_price']['title_text']) AND ! empty($WOOF->settings['by_price']['title_text'])): ?>
                                <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate(null, $WOOF->settings['by_price']['title_text']); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php endif; ?>

                            <?php echo do_shortcode('[woof_price_filter type="select" additional_taxes="' . $additional_taxes . '"]'); ?>

                        </div>
                    </div>
                <?php endif; ?>


                <?php if ($price_filter == 3): ?>
                    <div data-css-class="woof_price3_search_container" class="woof_price3_search_container woof_container">
                        <div class="woof_container_overlay_item"></div>
                        <div class="woof_container_inner">
                            <?php if (isset($WOOF->settings['by_price']['title_text']) AND ! empty($WOOF->settings['by_price']['title_text'])): ?>
                                <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate(null, $WOOF->settings['by_price']['title_text']); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php endif; ?>

                            <?php echo do_shortcode('[woof_price_filter type="slider" additional_taxes="' . $additional_taxes . '"]'); ?>

                        </div>
                    </div>
                <?php endif; ?>


                <?php if ($price_filter == 4): ?>
                    <div data-css-class="woof_price4_search_container" class="woof_price4_search_container woof_container">
                        <div class="woof_container_overlay_item"></div>
                        <div class="woof_container_inner">
                            <?php if (isset($WOOF->settings['by_price']['title_text']) AND ! empty($WOOF->settings['by_price']['title_text'])): ?>
                                <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate(null, $WOOF->settings['by_price']['title_text']); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php endif; ?>

                            <?php echo do_shortcode('[woof_price_filter type="text" additional_taxes="' . $additional_taxes . '"]'); ?>

                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($price_filter == 5): ?>
                    <div data-css-class="woof_price5_search_container" class="woof_price5_search_container woof_container">
                        <div class="woof_container_overlay_item"></div>
                        <div class="woof_container_inner">
                            <?php if (isset($WOOF->settings['by_price']['title_text']) AND ! empty($WOOF->settings['by_price']['title_text'])): ?>
                                <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo WOOF_HELPER::wpml_translate(null, $WOOF->settings['by_price']['title_text']); ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
                            <?php endif; ?>

                            <?php echo do_shortcode('[woof_price_filter type="radio" additional_taxes="' . $additional_taxes . '"]'); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <?php
                break;

            default:
                do_action('woof_print_html_type_' . $key);
                break;
        }
    }

}
?>


<?php if ($autohide): ?>
    <div>
        <?php
        if (isset($this->settings['woof_auto_hide_button_img']) AND ! empty($this->settings['woof_auto_hide_button_img'])) {
            if ($this->settings['woof_auto_hide_button_img'] != 'none') {
                ?>
                <style type="text/css">
                    .woof_show_auto_form,.woof_hide_auto_form
                    {
                        background-image: url('<?php echo $this->settings['woof_auto_hide_button_img'] ?>') !important;
                    }
                </style>
                <?php
            } else {
                ?>
                <style type="text/css">
                    .woof_show_auto_form,.woof_hide_auto_form
                    {
                        background-image: none !important;
                    }
                </style>
                <?php
            }
        }
        //***
        $woof_auto_hide_button_txt = '';
        if (isset($this->settings['woof_auto_hide_button_txt'])) {
            $woof_auto_hide_button_txt = WOOF_HELPER::wpml_translate(null, $this->settings['woof_auto_hide_button_txt']);
        }
        ?>
        <a href="javascript:void(0);" class="woof_show_auto_form <?php if (isset($this->settings['woof_auto_hide_button_img']) AND $this->settings['woof_auto_hide_button_img'] == 'none') echo 'woof_show_auto_form_txt'; ?>"><?php echo __($woof_auto_hide_button_txt) ?></a><br />
        <div class="woof_auto_show woof_overflow_hidden" style="opacity: 0; height: 1px;">
            <div class="woof_auto_show_indent woof_overflow_hidden">
            <?php endif; ?>

            <div class="woof <?php if (!empty($sid)): ?>woof_sid woof_sid_<?php echo $sid ?><?php endif; ?>" <?php if (!empty($sid)): ?>data-sid="<?php echo $sid; ?>"<?php endif; ?> data-shortcode="<?php echo(isset($_REQUEST['woof_shortcode_txt']) ? $_REQUEST['woof_shortcode_txt'] : 'woof') ?>" data-redirect="<?php echo $redirect ?>" data-autosubmit="<?php echo $autosubmit ?>" data-ajax-redraw="<?php echo $ajax_redraw ?>">

                <?php if ($show_woof_edit_view AND ! empty($sid)): ?>
                    <a href="#" class="woof_edit_view" data-sid="<?php echo $sid ?>"><?php _e('show blocks helper', 'woocommerce-products-filter') ?></a>
                    <div></div>
                <?php endif; ?>

                <!--- here is possible drop html code which is never redraws by AJAX ---->
				<div class="woof_submit_search_form_container">

                            <?php if ($this->is_isset_in_request_data($this->get_swoof_search_slug())): global $woof_link; ?>

                                <?php
                                $woof_reset_btn_txt = WOOF_HELPER::wpml_translate(null, __('Reset', 'woocommerce-products-filter'));
                                ?>

                                <?php if ($woof_reset_btn_txt != 'none'): ?>
                                    <button style="float: right;" class="button woof_reset_search_form" data-link="<?php echo $woof_link ?>"><?php echo $woof_reset_btn_txt ?></button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if (!$autosubmit OR $ajax_redraw):
							
						
 /* $useragent=$_SERVER['HTTP_USER_AGENT'];
 
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):	 */

		?>
		<?php
		$woof_filter_btn_txt = WOOF_HELPER::wpml_translate(null, __('Filter', 'woocommerce-products-filter'));
		?>
		<button style="float: left;" class="button woof_submit_search_form"><?php echo $woof_filter_btn_txt ?></button>
		<?php endif; ?>

                        </div>
                <div class="woof_redraw_zone" data-woof-ver="<?php echo WOOF_VERSION ?>">
                    <?php echo apply_filters('woof_print_content_before_search_form', '') ?>

                    <?php
                    if (isset($start_filtering_btn) AND (int) $start_filtering_btn == 1) {
                        $start_filtering_btn = true;
                    } else {
                        $start_filtering_btn = false;
                    }

                    if (is_ajax()) {
                        $start_filtering_btn = false;
                    }

                    if ($this->is_isset_in_request_data($this->get_swoof_search_slug())) {
                        $start_filtering_btn = false;
                    }

                    $txt = apply_filters('woof_start_filtering_btn_txt', __('Show products filter form', 'woocommerce-products-filter'));
                    ?>

                    <?php if ($start_filtering_btn): ?>
                        <a href="#" class="woof_button woof_start_filtering_btn"><?php echo $txt ?></a>
                    <?php else: ?>
                        <?php
                        global $wp_query;
                        //+++
                        //if (!empty($taxonomies))
                        {
                            $exclude_tax_key = '';
                            //code-bone for pages like
                            //http://dev.pluginus.net/product-category/clothing/ with GET params
                            //another way when GET is actual no possibility get current taxonomy
                            if ($this->is_really_current_term_exists()) {
                                $o = $this->get_really_current_term();
                                $exclude_tax_key = $o->taxonomy;
                                //do_shortcode("[woof_products_ids_prediction taxonomies=product_cat:{$o->term_id}]");
                                //echo $o->term_id;exit;
                            }
                            //***
                            if (!empty($wp_query->query)) {
                                if (isset($wp_query->query_vars['taxonomy']) AND in_array($wp_query->query_vars['taxonomy'], get_object_taxonomies('product'))) {
                                    $taxes = $wp_query->query;
                                    if (isset($taxes['paged'])) {
                                        unset($taxes['paged']);
                                    }

                                    foreach ($taxes as $key => $value) {
                                        if (in_array($key, array_keys($this->get_request_data()))) {
                                            unset($taxes[$key]);
                                        }
                                    }
                                    //***
                                    if (!empty($taxes)) {
                                        $t = array_keys($taxes);
                                        $v = array_values($taxes);
                                        //***
                                        $exclude_tax_key = $t[0];
                                        $_REQUEST['WOOF_IS_TAX_PAGE'] = $exclude_tax_key;
                                    }
                                }
                            } else {
                                //***
                            }

                            //***

                            $items_order = array();

                            $taxonomies_keys = array_keys($taxonomies);
                            if (isset($woof_settings['items_order']) AND ! empty($woof_settings['items_order'])) {
                                $items_order = explode(',', $woof_settings['items_order']);
                            } else {
                                $items_order = array_merge($this->items_keys, $taxonomies_keys);
                            }

                            //*** lets check if we have new taxonomies added in woocommerce or new item
                            foreach (array_merge($this->items_keys, $taxonomies_keys) as $key) {
                                if (!in_array($key, $items_order)) {
                                    $items_order[] = $key;
                                }
                            }

                            //lets print our items and taxonomies
                            $counter = 0;
                            // var_dump($items_order);
                            if (count($tax_only) > 0) {
                                $items_order = get_order_by_tax_only($items_order, $tax_only);
                            }
                            foreach ($items_order as $key) {
                                if (in_array($key, $this->items_keys)) {
                                    woof_print_item_by_key($key, $woof_settings, $additional_taxes);
                                } else {
                                    if (!isset($woof_settings['tax'][$key])) {
                                        continue;
                                    }

                                    woof_print_tax($taxonomies, $key, $taxonomies[$key], $exclude_tax_key, $taxonomies_info, $additional_taxes, $woof_settings, $args, $counter);
                                }
                                $counter++;
                            }
                        }
                        ?>




                        


                    <?php endif; ?>



                </div>

            </div>



            <?php if ($autohide): ?>
            </div>
        </div>

    </div>
<?php endif; ?>