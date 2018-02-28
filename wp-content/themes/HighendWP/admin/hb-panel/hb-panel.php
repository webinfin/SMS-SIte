<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('HB_Panel')){
	class HB_Panel{
		
		// Constructor
		function __construct(){

			/* add admin menu */
			add_action( 'admin_menu', array($this,'hb_register_hb_menu'));
		}

		// Register HB Menu
		function hb_register_hb_menu(){
			add_menu_page(
				'Highend', 
				'Highend', 
				'manage_options',
				'highend_options', 
				null,
				'dashicons-performance',
				'2.3'
			);

			add_submenu_page(
			    'highend_options',        // parent slug, same as above menu slug
			    'Highend Options',        // empty page title
			    'Highend Options',        // empty menu title
			    'manage_options',        // same capability as above
			    'highend_options',        // same menu slug as parent slug
			    null        // same function as above
			);

			add_submenu_page(
					"highend_options",
					__("Sidebar Manager","hbthemes"),
					__("Sidebar Manager","hbthemes"),
					"manage_options",
					"hb-sidebar-manager",
					array($this,'load_sidebar_generator'));

			if ( hb_module_enabled('hb_module_demo_importer') ) {
				add_submenu_page(
						"highend_options",
						__("Import Demos","hbthemes"),
						__("Import Demos","hbthemes"),
						"manage_options",
						"hb-import-demos",
						array($this,'load_demos'));
			}
			if ( hb_module_enabled('hb_module_system_diagnostics') ) {
				add_submenu_page(
					"highend_options",
					__("System Diagnostics","hbthemes"),
					__("System Diagnostics","hbthemes"),
					"manage_options",
					"hb-system-diagnostics",
					array($this,'load_diagnostics'));
				add_submenu_page(
					"highend_options",
					__("Theme Support","hbthemes"),
					__("Theme Support","hbthemes"),
					"manage_options",
					"hb-theme-support",
					array($this,'load_support'));
			}
			
		}

		// Loaders
		function load_diagnostics(){
			require_once('diagnostics.php');
		}

		function load_support(){
			require_once('support.php');
		}

		function load_sidebar_generator(){
			 require_once('sidebar-manager.php');
		}

		function load_demos(){
			 require_once('load-demos.php');
		}

		

	}
	new HB_Panel;
}
?>