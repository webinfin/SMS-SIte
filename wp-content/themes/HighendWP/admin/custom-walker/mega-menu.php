<?php
function hb_menus_hook() {

	wp_enqueue_script( 'hb-menus-scripts', HBTHEMES_ADMIN_URI . '/assets/js/menus-scripts.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'hb-menus-styles', HBTHEMES_ADMIN_URI . '/assets/css/menus-styles.css' );
    wp_enqueue_media();
}

if ('nav-menus.php' == basename($_SERVER['PHP_SELF'])) {
	add_action( 'admin_init', 'hb_menus_hook' );
}
