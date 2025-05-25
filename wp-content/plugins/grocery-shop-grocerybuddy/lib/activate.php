<?php
defined( 'ABSPATH' ) || exit;

//Installation of plugin starts here.
if ( ! function_exists( 'wgby_install' ) ) :
	function wgby_install() {
		//Installs default values on activation.
		global $wpdb;
		require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
		
		$charset_collate = $wpdb->get_charset_collate();
	
		update_option( "grocerybuddy_version", WGBY_GROCERY_BUDDY_VERSION );
		
		update_option ( 'wgby_plugin_is_activated', 'yes' );
	}//end of function wc_restaurant_install()
endif;