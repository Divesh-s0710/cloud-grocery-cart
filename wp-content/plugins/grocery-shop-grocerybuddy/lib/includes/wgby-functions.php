<?php
//Check if WP else Exit
if ( ! defined ( 'ABSPATH' ) ) {
	exit ();
}

/**
 * Functions Used throughout the plugin
 * 
 * Main Webful Grocery Functions
 */

//Adding styles and scripts for wordpress BackEnd.
if ( ! function_exists( 'wgby_admin_enqueue_style' ) ) :
	function wgby_admin_enqueue_style () {
		global $pagenow;

		$wc_the_page  = ( isset( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : "";
		if ( ( ! empty( $wc_the_page ) && ( 'wgby-webful-grocery-handle' === $wc_the_page ) ) ) {
			if ( 'edit.php' !== $pagenow ) {
				wp_enqueue_style ('foundation-css', WGBY_GROCERY_BUDDY_URL . '/assets/admin/css/foundation.min.css', array(), WGBY_GROCERY_BUDDY_VERSION, 'all', true  );
				wp_enqueue_style ('select2', WGBY_GROCERY_BUDDY_URL . '/assets/admin/css/select2.min.css', array(), WGBY_GROCERY_BUDDY_VERSION, 'all', true  );
			}
		}
		wp_enqueue_style ('wgby-my-admin-style', WGBY_GROCERY_BUDDY_URL . '/assets/admin/css/wgby-my-admin-style.css', array(), WGBY_GROCERY_BUDDY_VERSION, 'all' );
				
	}//end of adding styles and scripts for wordpress admin.
	add_action ( 'admin_enqueue_scripts', 'wgby_admin_enqueue_style', 1 );
endif;

if(!function_exists("wgby_admin_scripts_js")):
	function wgby_admin_scripts_js () {
		global $pagenow;

		$wc_the_page  = ( isset( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : "";
		if ( ( ! empty( $wc_the_page ) && ( 'wgby-webful-grocery-handle' === $wc_the_page ) ) ) {
			if ( 'edit.php' !== $pagenow ) {
				//foundationjs
				wp_enqueue_script ('foundation-js', WGBY_GROCERY_BUDDY_URL . '/assets/admin/js/foundation.min.js', array( 'jquery' ), '6.8.1', true );		
				wp_enqueue_script ('select2', WGBY_GROCERY_BUDDY_URL . '/assets/admin/js/select2.min.js', array('jquery'), WGBY_GROCERY_BUDDY_VERSION, true );
				wp_enqueue_script ('wcgb-scripts', WGBY_GROCERY_BUDDY_URL . '/assets/admin/js/wgby-my-admin-scripts.js', array('jquery'), WGBY_GROCERY_BUDDY_VERSION, true );
				wp_enqueue_style( 'wp-color-picker' );				
				wp_enqueue_script( 'wcgb-script-color', WGBY_GROCERY_BUDDY_URL . '/assets/admin/js/colorscript.js', array( 'wp-color-picker' ), false, true );

			}
		}				
	}
	add_action( 'admin_enqueue_scripts', 'wgby_admin_scripts_js', 1 );
endif;

if ( ! function_exists( 'wgby_register_script_foundation' ) ) :
    /**
     * Register Scripts
     * Register Styles
     * 
     * To Enque within Shortcodes 
     */
	function wgby_register_script_foundation() {
		
		wp_register_style ('foundation-css', WGBY_GROCERY_BUDDY_URL . DS . 'assets' . DS . 'admin' .DS. 'css' . DS . 'foundation.min.css', array(), WGBY_GROCERY_BUDDY_VERSION, 'all', true  );
		wp_enqueue_style ('wgby-style', WGBY_GROCERY_BUDDY_URL . DS . 'assets' . DS . 'css' .DS. 'style.css', array(), WGBY_GROCERY_BUDDY_VERSION, 'all', true );
		wp_register_script ('foundation-js', WGBY_GROCERY_BUDDY_URL . DS . 'assets' . DS . 'admin' .DS.  'js' . DS . 'foundation.min.js', array(), WGBY_GROCERY_BUDDY_VERSION, 'all', true );
		wp_register_script ('wgby-js', WGBY_GROCERY_BUDDY_URL . DS . 'assets' . DS . 'js' .DS. 'wgby_scripts.js', array(), WGBY_GROCERY_BUDDY_VERSION, 'all', true );


    }// adding styles and scripts for wordpress admin.
	add_action( 'init', 'wgby_register_script_foundation' );
endif;

// Categories List
if(!function_exists("wgby_get_categories_list_post_type")):
	function wgby_get_categories_list_post_type ( $post_type ) {

		if ( empty( $post_type ) ) {
			return '';
		}		
		$content 	  		= '';		
		$args = array(
			'taxonomy'     	=> 'product_cat',
			'orderby'       => 'id',
			'order' 		=> 'ASC',
			'hide_empty' 	=> true,
		);

		$wb_wg_main_categorie  = get_option ( "wgby_main_categories_list_name" );

		$all_categories = get_categories( $args );

		foreach ($all_categories as $cat) {
			if($cat->category_parent == 0) {
				if (!empty ($wb_wg_main_categorie) && ($wb_wg_main_categorie) == $cat->slug) {
					$content .= '<input type="radio"  name="wgby_main_categories_list_name" value="'. $cat->slug .'" id="'. $cat->name .'" checked> 
					<label for="'. $cat->name .'">'. $cat->name .'</label>';				
				} else {
					$content .= '<input type="radio"  name="wgby_main_categories_list_name" value="'. $cat->slug .'" id="'. $cat->name .'" > 
					<label for="'. $cat->name .'">'. $cat->name .'</label>';
				}			
			}
		}
		return $content;
	}
endif;

// Categories List dropdown 
if(!function_exists("wgby_get_categories_option_list_post_type")):
	function wgby_get_categories_option_list_post_type ( $post_type ) {

		if ( empty( $post_type ) ) {
			return '';
		}		

		$content 	  		= '';		
		
		$args = array(
			'taxonomy'     	=> 'product_cat',
			'orderby'       => 'id',
			'order' 		=> 'ASC',
			'hide_empty' 	=> true,
		);

		$wb_wg_main_categorie  			= get_option ( "wgby_main_categories_list_name" );
		$wgby_exclude_categories  		= unserialize(get_option ( "wgby_exclude_categories" ));

		$all_categories = get_categories( $args );

		foreach ($all_categories as $cat) {
			
			$the_slug 		= $cat->slug;
			$the_term_id  	= $cat->term_id ;
			$the_name 		= $cat->name;

			if (!empty ($wb_wg_main_categorie) && ($wb_wg_main_categorie) == $the_slug) {
				$content .= '';	
			} elseif (is_array($wgby_exclude_categories) && (in_array ($the_term_id , $wgby_exclude_categories)) ) {
				$content .= '<option value="'. $the_term_id .'" selected>'. $the_name .'</option>';
			} else {
				$content .= '<option value="'. $the_term_id .'" >'. $the_name .'</option>';
			}

		}

		return $content;
	}
endif;

if(!function_exists("wgby_get_categories_option_product_list")):
	function wgby_get_categories_option_product_list ( $post_type ) {

		if ( empty( $post_type ) ) {
			return '';
		}		

		$content 	  		= '';
		
		$wgby_exclude_products  		= unserialize(get_option ( "wgby_exclude_products" ));

		$args = array( 
			'post_type' => 'product', 
			'posts_per_page' => -1, 
			'orderby' => 'title',
			'order' => 'ASC',
			'tax_query' => array( array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'operator' => 'NOT IN',
			) ),
		);

		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) : $loop->the_post(); 
			global $product;

			if (is_array($wgby_exclude_products) && (in_array ($loop->post->ID , $wgby_exclude_products)) ) {
				$content .= '<option value="'. $loop->post->ID .'" selected>'. get_the_title() .'</option>';
			} else {
				$content .= '<option value="'. $loop->post->ID .'" >'. get_the_title() .'</option>';
			}

		endwhile;
		wp_reset_query();
			
		return $content;
	}
endif;

// Categories List dropdown 
if(!function_exists("wgby_get_categories_sort_list_post_type")):
	function wgby_get_categories_sort_list_post_type ( $post_type ) {

		if ( empty( $post_type ) ) {
			return '';
		}		

		$content 	  		= '';		
		
		$args = array(
			'taxonomy'     	=> 'product_cat',
			'orderby'       => 'id',
			'order' 		=> 'ASC',
			'hide_empty' 	=> true,
		);

		$wb_wg_main_categorie  			= get_option ( "wgby_main_categories_list_name" );
		$wgby_exclude_categories  		= unserialize(get_option ( "wgby_exclude_categories" ));

		$all_categories = get_categories( $args );

		foreach ($all_categories as $cat) {
			
			$the_slug 		= $cat->slug;
			$the_term_id  	= $cat->term_id ;
			$the_name 		= $cat->name;

			$content .= '<option value="'. $the_term_id .'" >'. $the_name .'</option>';

		}

		return $content;
	}
endif;


if(!function_exists("wgby_return_allowed_tags")):
	function wgby_return_allowed_tags() {
		$allowed_tags = array(
		'div' => array(
			'class' 		  	=> array(),
			'id' 			  	=> array(),
			'style' 		  	=> array(),
			'data-position'   	=> array(),
			'data-alignment'  	=> array(),
			'data-dropdown'   	=> array(),
			'data-auto-focus' 	=> array(),
			'data-reveal' 	  	=> array(),
			'data-abide-error' 	=> array(),
			'data-tabs-content' => array(),
			'role' 				=> array(),
			'aria-labelledby' 	=> array(),
			'aria-hidden' 		=> array()
		),
		'span' => array(
			'class' 		  	=> array(),
			'id' 			  	=> array(),
			'style' 		  	=> array(),
			'dir' 		  		=> array(),
			'data-select2-id' 	=> array(),
			'role' 		  		=> array(),
			'aria-haspopup' 	=> array(),
			'aria-expanded' 	=> array(),
			'tabindex' 		  	=> array(),
			'aria-disabled' 	=> array(),
		),
		'form' => array(
			'class' => array(),
			'id' => array(),
			'name' => array(),
			'method' => array(),
			'action' => array(),
			'data-async' => array(),
			'data-success-class' => array(),
			'data-abide' => array(),
			'data-print-reply' => array()
		),
		'label' => array(
			'class' => array(),
			'id' => array(),
			'for'	=> array()
		),
		'input' => array(
			'class' => array(),
			'id' => array(),
			'type'	=> array(),
			'name'	=> array(),
			'required' => array(),
			'value'	=> array(),
			'placeholder'	=> array(),
			'checked' => array(),
			'step'	=> array(),
			'disabled'	=> array(),
			'dt-product_id'	=> array(),
			'dt-product_name'	=> array(),
			'dt-product_category'	=> array(),
			'dt-product-cat-slug'	=> array(),
			'dt-product-cat-name'	=> array(),
			'dt-product-price'	=> array(),
			'min'	=> array(),
			'name'	=> array(),
			'value'	=> array(),
			'type'	=> array(),
		),
		'textarea' => array(
			'class' => array(),
			'id' => array(),
			'type'	=> array(),
			'name'	=> array(),
			'required' => array(),
			'placeholder'	=> array(),
			'cols'	=> array(),
			'tabindex'	=> array(),
			'rows' => array(),
			'autocorrect' => array(),
			'autocapitalize' => array(),
			'spellcheck' => array(),
			'role' => array(),
			'aria-autocomplete' => array(),
			'autocomplete' => array(),
			'aria-label' => array(),
			'aria-describedby' => array(),
			'style' => array()
		),
		'select' => array(
			'class' => array(),
			'id' => array(),
			'name'	=> array(),
			'required' => array(),
			'data-security' => array(),
			'data-placeholder' => array(),
			'data-exclude_type' => array(),
			'data-display_stock' => array(),
			'data-post' => array(),
			'style' => array(),
			'multiple' => array(),
			'required' => array(),
			'data-select2-id' => array(),
			'tabindex' => array(),
			'aria-hidden' => array()
		),
		'option' => array(
			'value' => array(),
			'selected' => array(),
			'data-select2-id' => array()
		),
		'button' => array(
			'class' => array(),
			'id' => array(),
			'for'	=> array(),
			'type' => array(),
			'data-open' => array(),
			'data-close' => array(),
			'data-type' => array(),
			'data-job-id' => array(),
			'onclick' => array(),
			'stepDown' => array(),
			'stepUp' => array(),
			'data-toggle' => array()
		),
		'fieldset' => array(
			'class' => array(),
		),
		'legend' => array(
			'class' => array(),
		),
		'table' => array(
			'class' => array(),
			'id' => array(),
			'cellpadding' => array(),
			'cellspacing' => array()
		),
		'thead' => array(
			'class' => array(),
			'id' => array()
		),
		'tbody' => array(
			'class' => array(),
			'id' => array()
		),
		'tr' => array(
			'class' => array(),
			'id' => array()
		),
		'th' => array(
			'class' => array(),
			'id' => array(),
			'colspan' => array(),
			'data-colname' => array()
		),
		'td' => array(
			'class' => array(),
			'id' => array(),
			'align' => array(),
			'colspan' => array(),
			'data-colname' => array()
		),
		'img' => array(
			'class' => array(),
			'id' => array(),
			'src' => array(),
			'alt' => array()
		),
		'h2' => array(
			'class' => array(),
			'id' 	=> array(),
		),
		'ul' => array(
			'class' 				=> array(),
			'id' 					=> array(),
			'data-accordion'		=> array(),
			'data-multi-expand'		=> array(),
			'data-allow-all-closed' => array(),
			'data-tabs'				=> array(),
		),
		'li' => array(
			'class' 				=> array(),
			'id' 					=> array(),
			'accordion-item' 		=> array(),
			'data-accordion-item' 	=> array(),
		),		
		'h1' => array(
			'class' => array()
		),
		'h2' => array(
			'class' => array()
		),
		'h3' => array(
			'class' => array()
		),
		'h4' => array(
			'class' => array()
		),
		'h5' => array(
			'class' => array()
		),
		'h6' => array(
			'class' => array()
		),
		'p' => array(
			'class' => array()
		),
		'b' => array(
			'class' => array(),
			'id' 	=> array(),
		),
		'br' => array(),
		'em' => array(),
		'em' => array(),
		'hr' => array(),
		'small' => array(),
		'strong' => array(),
		'span' => array(
			'class' => array()
		),
		'a' => array(
			'class' => array(),
			'id' => array(),
			'href'	=> array(),
			'title'	=> array(),
			'target' => array(),
			'recordid' => array(),
			'data-open' => array(),
			'data-type' => array(),
			'data-value' => array(),
			'style' => array(),
			'dt_brand_device' => array(),
			'dt_brand_id' => array(),
			'aria-controls' => array(),
			'aria-expanded' => array(),
		),
		'style' => array()
		// 'root' => array()
	);

		return $allowed_tags;
	}
endif;

if ( ! function_exists( 'wgby_update_cart' ) ) :
	function wgby_update_cart () {
		$message = '';
		$error = 0;
		if ( class_exists( 'WooCommerce' ) ) {

			// Verify that the nonce is valid.
			if ( ! isset( $_POST['wgby_product_add_security_sub'] ) || ! wp_verify_nonce( sanitize_key( $_POST['wgby_product_add_security_sub']), 'wgby_product_add_security' ) ) {
				return;
			}

			if ( isset( $_POST ) ) {
				$productQuantity 	 = ( isset( $_POST['productQuantity'] ) ) ? sanitize_text_field( $_POST['productQuantity'] ) : '';
				$dt_product_id 		 = ( isset( $_POST['dt_product_id'] ) ) ? sanitize_text_field( $_POST['dt_product_id'] ) : '';
				$dt_product_name 	 = ( isset( $_POST['dt_product_name'] ) ) ? sanitize_text_field( $_POST['dt_product_name'] ) : '';
				$dt_product_category = ( isset( $_POST['dt_product_category'] ) ) ? sanitize_text_field( $_POST['dt_product_category'] ) : '';
				$dt_product_cat_slug = ( isset( $_POST['dt_product_cat_slug'] ) ) ? sanitize_text_field( $_POST['dt_product_cat_slug'] ) : ''; 
				$dt_product_cat_name = ( isset( $_POST['dt_product_cat_name'] ) ) ? sanitize_text_field( $_POST['dt_product_cat_name'] ) : '';
				$dt_product_price 	 = ( isset( $_POST['dt_product_price'] ) ) ? sanitize_text_field( $_POST['dt_product_price'] ) : '';

				if ( $productQuantity > 0 ) {
					//Remove Cart item
					$product_cart_id = WC()->cart->generate_cart_id( $dt_product_id );
					if( WC()->cart->find_product_in_cart( $product_cart_id ) ){
						// Yep, the product with ID 55 is NOT in the cart, let's add it then!
						WC()->cart->remove_cart_item( $product_cart_id );
					}
					WC()->cart->add_to_cart( $dt_product_id, $productQuantity );
				} else {
					//Remove from cart if exists
					$product_cart_id = WC()->cart->generate_cart_id( $dt_product_id );
					if( WC()->cart->find_product_in_cart( $product_cart_id ) ){
						// Yep, the product with ID 55 is NOT in the cart, let's add it then!
						WC()->cart->remove_cart_item( $product_cart_id );
					}
				}
			} else {
				$message = esc_html__( 'Something is not right', 'grocery-shop-grocerybuddy' );
				$error = 1;
			} 

		} else {
			$message = esc_html__( 'WooCommerce required', 'grocery-shop-grocerybuddy' );
			$error = 1;
		}

		$return_values = array(
			'message' => $message,
			'error' => $error
		);
		wp_send_json( $return_values );
		wp_die();
	}
	add_action( 'wp_ajax_wgby_update_cart', 'wgby_update_cart' );
	add_action( 'wp_ajax_nopriv_wgby_update_cart', 'wgby_update_cart' );
endif;

if ( ! function_exists( 'wgby_remove_the_cart' ) ) : 
	function wgby_remove_the_cart() {
		$message = '';
		$error = 0;

		// Verify that the nonce is valid.
		if ( ! isset( $_POST['products_cart_item_inserted_security_sub'] ) || ! wp_verify_nonce( sanitize_key( $_POST['products_cart_item_inserted_security_sub']), 'products_cart_item_inserted_security' ) ) {
			return;
		}

		if ( class_exists( 'WooCommerce' ) ) :
		
			if ( isset( $_POST ) ) {
				$removetype 	 = ( isset( $_POST['type'] ) ) ? sanitize_text_field( $_POST['type'] ) : '';
				$dt_product_id 	 = ( isset( $_POST['dt_product_id'] ) ) ? sanitize_text_field( $_POST['dt_product_id'] ) : '';

				if ( $removetype == 'remove_item' ) {
					//Remove item
					if ( ! empty( $dt_product_id ) ) {
						$product_cart_id = WC()->cart->generate_cart_id( $dt_product_id );
						if( WC()->cart->find_product_in_cart( $product_cart_id ) ){
							// Yep, the product with ID 55 is NOT in the cart, let's add it then!
							WC()->cart->remove_cart_item( $product_cart_id );
						}	
					}
				}

				if ( $removetype == 'empty_cart' ) {
					WC()->cart->empty_cart();
				}

			} else {
				$message = esc_html__( 'Something is not right', 'grocery-shop-grocerybuddy' );
				$error = 1;
			}
		else:
			$message = esc_html__( 'WooCommerce required', 'grocery-shop-grocerybuddy' );
			$error = 1;
		endif;

		$return_values = array(
			'message' => $message,
			'error' => $error
		);
		wp_send_json( $return_values );
		wp_die();
	}
	add_action( 'wp_ajax_wgby_remove_the_cart', 'wgby_remove_the_cart' );
	add_action( 'wp_ajax_nopriv_wgby_remove_the_cart', 'wgby_remove_the_cart' );
endif;

if ( ! function_exists( 'wgby_get_cart_qty_product_id' ) ) :
	function wgby_get_cart_qty_product_id( $product_id ) {
		if ( empty( $product_id ) ) {
			return 0;
		}
		if (is_admin()) {
			return 0;
		}
		if ( ! class_exists( 'WooCommerce' ) ) {
			return 0;
		}

		if (is_object( WC()->cart )) {
			if ( empty( WC()->cart->get_cart_item_quantities() ) ) {
				return 0;
			} else {
				$quantities = WC()->cart->get_cart_item_quantities(); 
	
				if ( isset( $quantities[$product_id] ) && $quantities[ $product_id ] > 0 ) {
					return $quantities[ $product_id ];
				} else {
					return 0;
				}
			}
		}
			
	}
endif;

// Add additional fees based on shipping class
if ( ! function_exists( 'wgby_fee_based_on_shipping_class' ) ) :
	function wgby_fee_based_on_shipping_class() {
		global $woocommerce;

		$wgby_fees_order_less = get_option ( "wgby_fees_order_less_than" );
		$wgby_fees_order_less_than  = (int)$wgby_fees_order_less ;
		
		$extraFees = 0;
		if ( isset( $woocommerce->cart->cart_contents_total ) && $woocommerce->cart->cart_contents_total > 0 && $woocommerce->cart->cart_contents_total < $wgby_fees_order_less_than ) {
			$extraFees = $wgby_fees_order_less_than-$woocommerce->cart->cart_contents_total;

			$name      = 'Delivery Fee';
			$amount    = $extraFees;
			$taxable   = true;
			$tax_class = '';

			$woocommerce->cart->add_fee( $name, $amount, $taxable, $tax_class );
		}

	}
	add_action( 'woocommerce_cart_calculate_fees', 'wgby_fee_based_on_shipping_class' );
endif;

// Add additional fees based on shipping class
if ( ! function_exists( 'wgby_fee_based_on_flat_shipping' ) ) :
	function wgby_fee_based_on_flat_shipping() {
		global $woocommerce;
	
		$wgby_flat_shipping_rate = get_option ( "wgby_flat_shipping_rate" );
		$wgby_flat_shipping_rate_than  = (int)$wgby_flat_shipping_rate ;

		if (!empty($wgby_flat_shipping_rate_than) && ($wgby_flat_shipping_rate_than > 0)) {
		
			$extraFees = 0;
			if ( isset( $woocommerce->cart->cart_contents_total ) && $woocommerce->cart->cart_contents_total > 0 && $woocommerce->cart->cart_contents_total) {
				
				$extraFees = $wgby_flat_shipping_rate_than;

				$name      = 'Flat Shipping Rate';
				$amount    = $extraFees;
				$taxable   = true;
				$tax_class = '';

				$woocommerce->cart->add_fee( $name, $amount, $taxable, $tax_class );
			}
		}

	}
	add_action( 'woocommerce_cart_calculate_fees', 'wgby_fee_based_on_flat_shipping' );
endif;


if ( ! function_exists( 'wgby_register_color' ) ) :
	
    /**
     * Register Colors
     * Register Styles
     *  
     */

	function wgby_register_color() {
		
		$wgby_color_primer 		        	= get_option ( "wgby_color_primer" );   
		$wgby_color_secondary 		        = get_option ( "wgby_color_secondary" );
		$wgby_color_headings		        = get_option ( "wgby_color_headings" );
		$wgby_color_text	    	        = get_option ( "wgby_color_text" );
		$wgby_color_transparent_text	    = get_option ( "wgby_color_transparent_text" );

		if (empty ($wgby_color_primer)) {
			$wgby_color_primer = "#032541";
		} else {
			$wgby_color_primer = $wgby_color_primer;
		}
		if (empty ($wgby_color_secondary)) {
			$wgby_color_secondary = "#f16759";
		} else {
			$wgby_color_secondary = $wgby_color_secondary;
		}
		if (empty ($wgby_color_headings)) {
			$wgby_color_headings = "#000";
		} else {
			$wgby_color_headings = $wgby_color_headings;
		}
		if (empty ($wgby_color_text)) {
			$wgby_color_text = "#555";
		} else {
			$wgby_color_text = $wgby_color_text;
		}
		if (empty ($wgby_color_transparent_text)) {
			$wgby_color_transparent_text = "#fff";
		} else {
			$wgby_color_transparent_text = $wgby_color_transparent_text;
		}

		$content ='';

		$content .='<style>:root {';
		$content .=' --wgby_color_primer:'.esc_html($wgby_color_primer).';';
		$content .=' --wgby_color_secondary:'.esc_html($wgby_color_secondary).';';
		$content .=' --wgby_color_headings:'.esc_html($wgby_color_headings).';';
		$content .=' --wgby_color_text:'.esc_html($wgby_color_text).';';
		$content .=' --wgby_color_transparent_text:'.esc_html($wgby_color_transparent_text).';';
		$content .='}</style>';

		$allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
		echo wp_kses( $content, $allowedHTML );	

    }// adding styles and scripts for wordpress.
	add_action( 'wp_enqueue_scripts', 'wgby_register_color' );
endif;

if ( ! function_exists( 'gb_license_state' ) ):
	function gb_license_state() {
		$_LICENSE_ACTIVATION = GB_LICENSE_ACTIVATION::getInstance();
		$_LICENSE_ACTIVATION->grocerybuddy_verify_purchase( '', '' );

		//Get purchase data.
		$purchase_arr = get_option( $_LICENSE_ACTIVATION->LICENSE_DETAILS_ID );

		if ( empty( $purchase_arr ) ) {
			return FALSE;
		}
		if ( ! is_array( $purchase_arr ) ) {
			return FALSE;
		}
		$licenseState = ( isset( $purchase_arr['license_state'] ) && ! empty( $purchase_arr['license_state'] ) ) ? $purchase_arr['license_state'] : '';

		if($licenseState != "valid") {
			return FALSE;
		}
		$licenseExpiry 	= ( isset( $purchase_arr['support_until'] ) && ! empty( $purchase_arr['support_until'] ) ) ? $purchase_arr['support_until'] : '';

		$licenseExpiry 	= ( ! empty( $licenseExpiry ) ) ? date( 'Y-m-d', strtotime( $licenseExpiry ) ) : '';
		
		if(empty($licenseExpiry)) {
			return FALSE;
		}

		$date_now = date( 'Y-m-d' );
		
		if ( $date_now > $licenseExpiry ) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
endif;