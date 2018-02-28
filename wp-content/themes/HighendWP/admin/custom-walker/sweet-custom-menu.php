<?php
/*
Plugin Name: Sweet Custom Menu
Plugin URL: http://remicorson.com/sweet-custom-menu
Description: A little plugin to add attributes to WordPress menus
Version: 0.1
Author: Remi Corson
Author URI: http://remicorson.com
Contributors: corsonr
Text Domain: rc_scm
Domain Path: languages
*/

class rc_sweet_custom_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// load the plugin translation files
		add_action( 'init', array( $this, 'textdomain' ) );
		
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'rc_scm_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'rc_scm_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'rc_scm_edit_walker'), 10, 2 );

	} // end constructor
	
	
	/**
	 * Load the plugin's text domain
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function textdomain() {
		//load_plugin_textdomain( 'rc_scm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_add_custom_nav_fields( $menu_item ) {
	
	    $menu_item->subtitle = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
	    $menu_item->megamenu = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
        $menu_item->megamenu_background = get_post_meta( $menu_item->ID, '_menu_item_megamenu_background', true );
        $menu_item->megamenu_widgetarea = get_post_meta( $menu_item->ID, '_menu_item_megamenu_widgetarea', true );
        $menu_item->megamenu_styles = get_post_meta( $menu_item->ID, '_menu_item_megamenu_styles', true );
        $menu_item->megamenu_columns = get_post_meta( $menu_item->ID, '_menu_item_megamenu_columns', true );
	    $menu_item->megamenu_captions = get_post_meta( $menu_item->ID, '_menu_item_megamenu_captions', true );
        return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if (!isset($_REQUEST['menu-item-subtitle'][$menu_item_db_id])) {
            $_REQUEST['menu-item-subtitle'][$menu_item_db_id] = '';
            
        }
	    $subtitle_value = $_REQUEST['menu-item-subtitle'][$menu_item_db_id];
	    update_post_meta( $menu_item_db_id, '_menu_item_subtitle', $subtitle_value );

	    if (!isset($_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id])) {
            $_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id] = '';
            
        }
        $menu_mega_enabled_value = $_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id];        
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $menu_mega_enabled_value );

        if (!isset($_REQUEST['menu-item-megamenu-background'][$menu_item_db_id])) {
        $_REQUEST['menu-item-megamenu-background'][$menu_item_db_id] = '';
            
        }
        $mega_menu_background_value = $_REQUEST['menu-item-megamenu-background'][$menu_item_db_id];        
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_background', $mega_menu_background_value );


        if (!isset($_REQUEST['menu-item-megamenu-widgetarea'][$menu_item_db_id])) {
        $_REQUEST['menu-item-megamenu-widgetarea'][$menu_item_db_id] = '';
            
        }
        $mega_menu_widgetarea_value = $_REQUEST['menu-item-megamenu-widgetarea'][$menu_item_db_id];        
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_widgetarea', $mega_menu_widgetarea_value );


        if (!isset($_REQUEST['menu-item-megamenu-styles'][$menu_item_db_id])) {
        $_REQUEST['menu-item-megamenu-styles'][$menu_item_db_id] = '';
            
        }
        $mega_menu_styles_value = $_REQUEST['menu-item-megamenu-styles'][$menu_item_db_id];        
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_styles', $mega_menu_styles_value );

        if (!isset($_REQUEST['menu-item-megamenu-columns'][$menu_item_db_id])) {
        $_REQUEST['menu-item-megamenu-columns'][$menu_item_db_id] = '';
            
        }
        $mega_menu_columns_value = $_REQUEST['menu-item-megamenu-columns'][$menu_item_db_id];        
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_columns', $mega_menu_columns_value );
	   

	   	if (!isset($_REQUEST['edit-menu-item-megamenu-captions'][$menu_item_db_id])) {
            $_REQUEST['edit-menu-item-megamenu-captions'][$menu_item_db_id] = '';
            
        }
        $menu_mega_captions_enabled_value = $_REQUEST['edit-menu-item-megamenu-captions'][$menu_item_db_id];        
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_captions', $menu_mega_captions_enabled_value );

	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_edit_walker($walker,$menu_id) {
	
	    return 'Walker_Nav_Menu_Edit_Custom';
	    
	}

}

// instantiate plugin's class
$GLOBALS['sweet_custom_menu'] = new rc_sweet_custom_menu();


include_once( 'edit_custom_walker.php' );
include_once( 'custom_walker.php' );