<?php
/** @var $this WPBakeryShortCode_VC_Tab */
$output = $title = $icon = $tab_id = '';
$predefined = $this->predefined_atts;
$predefined['icon'] = 'hb-moon-plus-circle';
extract(shortcode_atts($predefined, $atts));

wp_enqueue_script( 'jquery_ui_tabs_rotate' );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_tab ui-tabs-panel wpb_ui-tabs-hide vc_clearfix', $this->settings['base'], $atts );
$output .= "\n\t\t\t" . '<div class="tab-content">';
$output .= "\n\t\t\t" . '<div id="tab-' . ( empty( $tab_id ) ? sanitize_title( $title ) : $tab_id ) . '" class="' . $css_class . ' tab-pane">';
$output .= ( $content == '' || $content == ' ' ) ? __( "Empty tab. Edit page to add content here.", "js_composer" ) : "\n\t\t\t\t" . wpb_js_remove_wpautop( $content );
$output .= "\n\t\t\t" . '</div>';
$output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment( '.wpb_tab' );

echo $output;