<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $source
 * @var $text
 * @var $link
 * @var $google_fonts
 * @var $font_container
 * @var $el_class
 * @var $css
 * @var $font_container_data - returned from $this->getAttributes
 * @var $google_fonts_data - returned from $this->getAttributes
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Custom_heading
 */
$source = $text = $link = $google_fonts = $font_container = $el_class = $css = $font_container_data = $google_fonts_data = '';

// HB CUSTOM VARS
$tw_enable = $tw_prefix = $tw_highlight = $tw_highlight_color = $tw_highlight_text_color = $tw_speed = $tw_backdelay = $tw_loop = $tw_loop_count = '';
// END HB CUSTOM VARS

// This is needed to extract $font_container_data and $google_fonts_data
extract( $this->getAttributes( $atts ) );

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

$settings = get_option( 'wpb_js_google_fonts_subsets' );
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
} else {
	$subsets = '';
}

if ( isset( $google_fonts_data['values']['font_family'] ) ) {
	wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
}

if ( ! empty( $styles ) ) {
	$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
} else {
	$style = '';
}

if ( 'post_title' === $source ) {
	$text = get_the_title( get_the_ID() );
}

if ( ! empty( $link ) ) {
	$link = vc_build_link( $link );
	$text = '<a href="' . esc_attr( $link['url'] ) . '"'
		. ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' )
		. ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' )
		. '>' . $text . '</a>';
}

if ( $tw_enable == "yes" ) {
	$css_class .= ' hb-typed-text-wrap';
	$tw_highlight = $tw_highlight == "yes" ? "true" : "false";
	$tw_loop = $tw_loop == "yes" ? "true" : "false";
	$tw_loop_count = $tw_loop_count ? $tw_loop_count : "false";

	$text = '<div class="hb-typed-strings">' . hb_nl2p($text) . '</div>';
	
	if ( $tw_prefix ) $text .= '<span class="hb-typed-prefix">' . $tw_prefix . ' </span>';
	
	$text .= '<span class="hb-typed-text" data-highlight="' . $tw_highlight . '" data-highlightcolor="' . $tw_highlight_color . '" data-highlighttextcolor="' . $tw_highlight_text_color . '" data-speed="' . $tw_speed . '" data-backdelay="' . $tw_backdelay . '" data-loop="' . $tw_loop . '" data-loopcount="' . $tw_loop_count . '"></span>';
}

$output = '';
if ( apply_filters( 'vc_custom_heading_template_use_wrapper', false ) ) {
	$output .= '<div class="' . esc_attr( $css_class ) . '" >';
	$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' >';
	$output .= $text;
	$output .= '</' . $font_container_data['values']['tag'] . '>';
	$output .= '</div>';
} else {
	$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' class="' . esc_attr( $css_class ) . '">';
	$output .= $text;
	$output .= '</' . $font_container_data['values']['tag'] . '>';
}

echo $output;