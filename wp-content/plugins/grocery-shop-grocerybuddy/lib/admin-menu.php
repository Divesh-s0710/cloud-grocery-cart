<?php
defined( 'ABSPATH' ) || exit;
	
/**
 * Function to add admin menu
 * 
 * @Since 1.0
 * @package Grocery Management
 */
if ( ! function_exists( 'wgby_add_grocery_pages' ) ) :
	function wgby_add_grocery_pages() {
		// main_sub Menu Page
		$main_menu_name = ( empty( get_option ( 'wgby_main_menu_name_wg' ) ) ) ? esc_html__( 'Webful Grocery', 'grocery-shop-grocerybuddy' ) : get_option( 'wgby_main_menu_name_wg' );	

		add_menu_page ( $main_menu_name, $main_menu_name, 'manage_options', 'wgby-webful-grocery-handle', 'wgby_dashboard_page', WGBY_GROCERY_BUDDY_URL . '/assets/images/dashicons-admin.png', '3' );
				
	}
	add_action( 'admin_menu', 'wgby_add_grocery_pages' );
endif;