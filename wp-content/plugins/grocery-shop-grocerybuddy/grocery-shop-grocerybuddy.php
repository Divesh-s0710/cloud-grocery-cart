<?php
/***
	Plugin Name: GroceryBuddy - Grocery Shop - For WooCommerce
	Plugin URI: https://www.webfulcreations.com/products/grocerybuddy-grocery-shop-for-woocommerce/
	Description: WordPress Woocommerce Grocery Shop Management Plugin let your customers order various items from single page.
	Version: 1.3
	Author: Webful Creations
	Author URI: https://www.webfulcreations.com/
	License: GPLv2 or later.
	License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	Text Domain: grocery-shop-grocerybuddy
	Domain Path: languages
	Requires at least: 5.0
	Tested up to: 6.6.1
	Requires PHP: 8.0
	@package : 1.3
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'WGBY_GROCERY_BUDDY_VERSION', '1.3' );
define( 'WGBY_GROCERY_BUDDY_FILE', __FILE__ );
define( 'WGBY_GROCERY_BUDDY_FOLDER', dirname( plugin_basename(__FILE__) ) );
define( 'WGBY_GROCERY_BUDDY_DIR', plugin_dir_path( __FILE__ ) );	
define( 'WGBY_GROCERY_BUDDY_URL', plugins_url( '', __FILE__ ) );

if ( ! defined( 'DS') ) {
	define( 'DS', '/');
}

if( ! function_exists( 'wgby_language_plugin_init' ) ) :
	function wgby_language_plugin_init() {
		load_plugin_textdomain( 'grocery-shop-grocerybuddy', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}
	add_action( 'plugins_loaded', 'wgby_language_plugin_init');
endif;

require_once WGBY_GROCERY_BUDDY_DIR . 'lib' . DS . 'enginestarter.php';

//Installation of plugin check already exist.
$wgby_plugin_is_activated = get_option ( "wgby_plugin_is_activated" );

if ( empty ( $wgby_plugin_is_activated ) || $wgby_plugin_is_activated !== 'yes') {
	register_activation_hook ( WGBY_GROCERY_BUDDY_FILE, 'wgby_install' ) ;
}

//Ajax Script Enque
if(!function_exists("wgby_ajax_script_enqueue")):
	function wgby_ajax_script_enqueue() {
		wp_enqueue_script( 'ajax_script', plugin_dir_url(__FILE__ ).'assets/admin/js/wgby-ajax-scripts.js', array('jquery'), WGBY_GROCERY_BUDDY_VERSION, true );
		wp_localize_script( 'ajax_script', 'ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
	add_action ( 'admin_enqueue_scripts', 'wgby_ajax_script_enqueue' );
endif;

if ( ! function_exists( 'wgby_front_ajax_script_enqueue' ) ) :
	/**
	 * Ajax Script Enque
	 * For Front-End
	 *
	 * @Since 1.0.0
	 */
	function wgby_front_ajax_script_enqueue() {
		wp_enqueue_script( 'wg_ajax_script', plugin_dir_url( __FILE__ ) . 'assets/js/ajax_scripts.js', array( 'jquery' ), WGBY_GROCERY_BUDDY_VERSION, true );
		wp_localize_script( 'wg_ajax_script', 'ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
	add_action ( 'wp_enqueue_scripts', 'wgby_front_ajax_script_enqueue' );
endif;

/**
* Check if WooCommerce is activated
*/
if ( ! function_exists( 'wgby_is_woocommerce_activated' ) ) :
	function wgby_is_woocommerce_activated() {
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true; 
		} else { 
			return false; 
		}
	}
endif;

if ( !function_exists ("wgby_get_woocommerce_activated") ):
	function wgby_get_woocommerce_activated () {
		if (! empty (wgby_is_woocommerce_activated() === false)) {
			$content = '<div class="error">';
			$content .= '<p>'.esc_html__(" Grocery Shop requires WooCommerce to be installed and active.!", "grocery-shop-grocerybuddy").'</p>';
			$content .= '</div>';
			$allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
			echo wp_kses( $content, $allowedHTML );			
		}
	}	
	add_action ("admin_notices", "wgby_get_woocommerce_activated" );
endif;