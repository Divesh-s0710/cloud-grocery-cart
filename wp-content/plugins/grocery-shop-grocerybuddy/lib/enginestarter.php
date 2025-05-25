<?php
	//Check if WP else Exit
	if ( ! defined( 'ABSPATH' ) ) {
		exit();
	}
	
	require_once WGBY_GROCERY_BUDDY_DIR . "lib" . DS . 'activate.php';
	require_once WGBY_GROCERY_BUDDY_DIR . "lib" . DS . 'update.php';

	/**
	 * Functions file
	 * 
	 * File Includes Main functions
	 */
	require_once WGBY_GROCERY_BUDDY_DIR . "lib" . DS . "includes" . DS . 'wgby-functions.php'; //include functions menu file.
	
	/**
	 * Admin Menu Generator
	 * 
	 * File to handle Admin menu
	 */
	require_once WGBY_GROCERY_BUDDY_DIR . "lib" . DS . 'admin-menu.php'; //include admin menu file.
		
	/**
	 * Settings Page
	 * 
	 * For plugin settings function available in.
	 */
	require_once WGBY_GROCERY_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'main-page.php';

	// Admin pages starts here.

	require_once WGBY_GROCERY_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'wgby-pages' . DS . 'wgby-product-page.php';
	
	require_once WGBY_GROCERY_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'wgby-settings.php';

	require_once WGBY_GROCERY_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'classes' . DS . 'index.php';
	
	// Admin pages ends here