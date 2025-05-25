<?php
if ( ! defined( 'ABSPATH' ) ) { 
	exit;
}

$rs_pr_curr_ver = get_option( 'grocerybuddy_version' );

//Installation of plugin starts here.
if(!function_exists("grocerybuddy_update_plugin_features")):
	function grocerybuddy_update_plugin_features() {
		//Installs default values on activation.
		global $wpdb;
		require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
		
		$charset_collate = $wpdb->get_charset_collate();
		
		//Let's call the install function here.
		wgby_install();

		//Verify
		$_LICENSE_ACTIVATION = GB_LICENSE_ACTIVATION::getInstance();
		$_LICENSE_ACTIVATION->grocerybuddy_verify_purchase( '', '' );
		
	}//end of function wc_restaurant_install()
endif;	
	
/*
	check Update status and run functions
*/
if(	empty( $rs_pr_curr_ver ) || $rs_pr_curr_ver != WGBY_GROCERY_BUDDY_VERSION ) {
	grocerybuddy_update_plugin_features();
	update_option( "grocerybuddy_version", WGBY_GROCERY_BUDDY_VERSION );
}