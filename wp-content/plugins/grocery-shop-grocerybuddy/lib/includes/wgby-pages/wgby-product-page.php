<?php
//List product shortcode

defined( 'ABSPATH' ) || exit;

function wgby_product_page() {

	ob_start();
	global $woocommerce;

	$content 			='';
	$input_number 		= "'input[type=number]'";

	wp_enqueue_style ("foundation-css");
	wp_enqueue_script ("foundation-js");
	wp_enqueue_script ("wgby-js");
	
	$wb_wg_main_categorie 			= get_option ( "wgby_main_categories_list_name" );
	$wgby_exclude_categories  		= unserialize(get_option ( "wgby_exclude_categories" ));
	$wgby_exclude_products  		= unserialize(get_option ( "wgby_exclude_products" ));
	$wgby_sort_categories  			= unserialize(get_option ( "wgby_sort_categories" ));
	$wgby_flat_shipping_rate 		= get_option ( "wgby_flat_shipping_rate" );
	$woocommerce_currency 			= get_woocommerce_currency_symbol();

	if (empty($wgby_flat_shipping_rate)) {
		$wgby_flat_shipping_rate = '0';
	} else {
		$wgby_flat_shipping_rate =	$wgby_flat_shipping_rate;
	}

	if (empty ($wb_wg_main_categorie)) {
		$content .='<h2>'.esc_html__("Choose your main category first!", "grocery-shop-grocerybuddy").'</h2>';
	} else {
		
		$content .= wp_nonce_field('wgby_product_add_security', 'wgby_product_add_security_sub');

		$content .='<div class="main-products-content grid-x grid-padding-x">';

		$content .='<div class="cell small-12 medium-12 large-8 w70">';

		$args = array( 
			'post_type' 	 => 'product', 
			'posts_per_page' => -1, 
			'product_cat' 	 => $wb_wg_main_categorie, // Category slug "clothing"
			'orderby' => 'title',
			'order' => 'ASC',
			'post__not_in'				=> $wgby_exclude_products,
		);
		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) : $loop->the_post(); global $product;
			
			$content .='<div class="main-products-wrap">';
			$content .='<div class="grid-x grid-padding-x">';
			$content .='<div class="cell small-12 medium-4 large-4">';					
			if (has_post_thumbnail( $loop->post->ID )) {
				$content .= get_the_post_thumbnail($loop->post->ID, 'woocommerce_thumbnail');
			}
			$content .='</div><!-- Columns -->';

			$content .='<div class="cell small-12 medium-8 large-8">';
			$content .='<h3>'.get_the_title().'</h3>';
			$content .='<div class="products-wrap-content">';
			//$content .= $loop->post->post_content;
			$content .='</div>';
			$content .='<div class="products-box-footer">';
			$content .='<span class="price">'.$product->get_price_html().'</span>';

				$theProductPrice = $product->get_price();
				$theProductTitle = get_the_title( $loop->post->ID );
				$term = get_term_by( 'slug', $wb_wg_main_categorie, 'product_cat');

				$catName = $catSlug = $catID = '';
				if ( ! empty( $term ) ) {
					$catSlug = $term->slug;
					$catName = $term->name;
					$catID   = $term->term_id;
				}

			$content .='<div class="number-input">';
			$content .='<button onclick="this.parentNode.querySelector('.$input_number.').stepDown()" class="wg_update-the-cart"></button>';
			$content .='
				<input disabled class="quantity wg-update-total" dt-product_id="'.esc_attr( $loop->post->ID ).'" 
				dt-product_name="'.esc_html( $theProductTitle ).'" 
				dt-product_category="'.esc_attr( $catID ).'" 
				dt-product-cat-slug="'.esc_html( $catSlug ).'" 
				dt-product-cat-name="'.esc_html( $catName ).'" 
				dt-product-price="'.esc_html( $theProductPrice ).'" 
				min="0" name="quantity" value="'.esc_html( wgby_get_cart_qty_product_id( $loop->post->ID ) ).'" type="number">';

			$content .='<button onclick="this.parentNode.querySelector('.$input_number.').stepUp()" class="plus wg_update-the-cart"></button>';

			$content .='</div> </div> </div><!-- Columns --> </div><!-- grid /--> </div>';
									
		endwhile;
		wp_reset_query();

		if (is_array ($wgby_exclude_categories) && (is_array($wgby_sort_categories))) {
			$array_final = array_diff($wgby_sort_categories, $wgby_exclude_categories);
		} else {
			$array_final = $wgby_sort_categories;
		}

		if ( is_array($wgby_sort_categories) && in_array('Default', $wgby_sort_categories) ){
			$args = array(
				'taxonomy'      		=> 'product_cat',
				'field' 				=> 'slug',
				'orderby' 				=> 'id',
				'order' 				=> 'ASC',
				'hide_empty' 			=> true,
				'exclude'				=> $wgby_exclude_categories,
			);
		} else {
			$args = array(
				'taxonomy'      		=> 'product_cat',
				'field' 				=> 'slug',
				'orderby' 				=> 'include',
				'order' 				=> 'ASC',
				'include' 				=> $array_final,
				'hide_empty' 			=> true,
				'exclude'				=> $wgby_exclude_categories,
			);
		}

		$all_categories = get_categories( $args );
		$content .='<div class="main-products-cat-wrap">';
		$content .='<ul class="accordion" data-accordion data-allow-all-closed="true" role="tablist">';

		foreach ($all_categories as $group) {

			$group_slug = $group->slug;
			$group_name = $group->name;
			$group_description = $group->description;

			if ( $group_slug == $wb_wg_main_categorie ) {
				//empty categories
				$args = array( 
					'post_type' => 'product', 
					'posts_per_page' => -1, 
					'product_cat' => $group_slug , // Category slug "main category"
					'orderby' => 'title',
					'order' => 'ASC',
					'post__not_in'				=> $wgby_exclude_products,
					'tax_query' => array( array(
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'operator' => 'NOT IN',
					) ),
				);
				
				$loop = new WP_Query( $args );

				$content .='<li class="accordion-item accordion-item-hd-lg" data-accordion-item>';
				$content .='<a href="#" class="accordion-title"> '.esc_html( $group_name).' </a>';
				$content .='<div class="accordion-content"data-tab-content>';

					if (! empty($group_description)) {
						$content .='<div class="cat-wrap_description">';
						$content .='<p>'.esc_html( $group_description).'</p>';
						$content .='</div>';												
					}
					while ( $loop->have_posts() ) : $loop->the_post(); global $product;

						$content .='<div class="main-products-cat-wrap-box">';
						$content .='<div class="cat-wrap-box-the-title">';

						if (has_post_thumbnail( $loop->post->ID )) {
							$content .= get_the_post_thumbnail($loop->post->ID, 'woocommerce_thumbnail');
						}

						$content .='<h3>'.get_the_title().'</h3>';
						$content .='</div>';
						$content .='<div class="cat-wrap-box-price">';
						$content .='<span class="price">'.$product->get_price_html().'</span>';
						$content .='</div>';
						$content .='<div class="cat-wrap-box-add-to-cart">';
						$theProductPrice = $product->get_price();
						$theProductTitle = get_the_title( $loop->post->ID );
						$term = get_term_by( 'slug', $group_slug , 'product_cat');

						$catName = $catSlug = $catID = '';
						if ( ! empty( $term ) ) {
							$catSlug = $term->slug;
							$catName = $term->name;
							$catID   = $term->term_id;
						}

						$content .='<div class="number-input">';
						$content .='<button onclick="this.parentNode.querySelector('.$input_number.').stepDown()" class="wg_update-the-cart"></button>';
						$content .='
							<input disabled class="quantity wg-update-total" dt-product_id="'.esc_attr( $loop->post->ID ).'" 
							dt-product_name="'.esc_html( $theProductTitle ).'" 
							dt-product_category="'.esc_attr( $catID ).'" 
							dt-product-cat-slug="'.esc_html( $catSlug ).'" 
							dt-product-cat-name="'.esc_html( $catName ).'" 
							dt-product-price="'.esc_html( $theProductPrice ).'" 
							min="0" name="quantity" value="'.esc_html( wgby_get_cart_qty_product_id( $loop->post->ID ) ).'" type="number">';				
						$content .='<button onclick="this.parentNode.querySelector('.$input_number.').stepUp()" class="plus wg_update-the-cart"></button>';
						$content .='</div>';

						$content .='</div>';
						$content .='</div> <!-- main-products -->';
						$content .='';
						$content .='';
						$content .='';
					endwhile;
					wp_reset_query();

					$content .='</div>';
					$content .='</li>';

			} else {
				$args = array( 
					'post_type' => 'product', 
					'posts_per_page' => -1, 
					'product_cat' => $group_slug , // Category slug "clothing"
					'orderby' => 'title',
					'order' => 'ASC',
					'post__not_in'				=> $wgby_exclude_products,
					'tax_query' => array( array(
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'operator' => 'NOT IN',
					) ),
				);
		
				$loop = new WP_Query( $args );

				$content .='<li class="accordion-item" data-accordion-item>';
				$content .='<a href="#" class="accordion-title"> '.esc_html( $group_name).'</a>';
				$content .='<div class="accordion-content" data-tab-content>';
				if (! empty($group_description)) {
					$content .='<div class="cat-wrap_description">';
					$content .='<p>'.esc_html( $group_description).'</p>';
					$content .='</div>';							
				}

				while ( $loop->have_posts() ) : $loop->the_post(); global $product;

					$content .='<div class="main-products-cat-wrap-box">';
					$content .='<div class="cat-wrap-box-the-title">';

					if (has_post_thumbnail( $loop->post->ID )) {
						$content .= get_the_post_thumbnail($loop->post->ID, 'woocommerce_thumbnail');
					}
					
					$content .='<h3>'.get_the_title().'</h3>';
					$content .='</div>';
					$content .='<div class="cat-wrap-box-price">';
					$content .='<span class="price">'.$product->get_price_html().'</span>';
					$content .='</div>';
					$content .='<div class="cat-wrap-box-add-to-cart">';
					
					$theProductPrice = $product->get_price();
					$theProductTitle = get_the_title( $loop->post->ID );
					$term = get_term_by( 'slug', $group_slug , 'product_cat');

					$catName = $catSlug = $catID = '';
					if ( ! empty( $term ) ) {
						$catSlug = $term->slug;
						$catName = $term->name;
						$catID   = $term->term_id;
					}

					$content .='<div class="number-input">';
					$content .='<button onclick="this.parentNode.querySelector('.$input_number.').stepDown()" class="wg_update-the-cart"></button>';
					$content .='
						<input disabled class="quantity wg-update-total" dt-product_id="'.esc_attr( $loop->post->ID ).'" 
						dt-product_name="'.esc_html( $theProductTitle ).'" 
						dt-product_category="'.esc_attr( $catID ).'" 
						dt-product-cat-slug="'.esc_html( $catSlug ).'" 
						dt-product-cat-name="'.esc_html( $catName ).'" 
						dt-product-price="'.esc_html( $theProductPrice ).'" 
						min="0" name="quantity" value="'.esc_html( wgby_get_cart_qty_product_id( $loop->post->ID ) ).'" type="number">';
	
					$content .='<button onclick="this.parentNode.querySelector('.$input_number.').stepUp()" class="plus wg_update-the-cart"></button>';
					$content .='</div>';
					$content .='</div>';
					$content .='</div> <!-- main-products -->';

				endwhile;
				wp_reset_query();

				$content .='</div>';
				$content .='</li>';
			}			
		}

		$content .='</ul> <!-- accordion /-->';
		$content .='</div>';
		$content .='</div> <!-- Columns -->';

		$content .='<div class="cell small-12 medium-12 large-4 w30">';
		$content .='<div class="main-products-cart-box mobile-cart-dnone">';
		$content .='<div class="main-products-cart-box-top">';
		$content .='<h3>'.esc_html__( 'Summary', 'grocery-shop-grocerybuddy' ).'</h3>';
		$content .='</div>';
		$content .='<table class="cart-item-heading-table">';
		$content .='<thead><tr>';
		$content .='<th>'.esc_html__( 'Item', 'grocery-shop-grocerybuddy' ).'</th>';
		$content .='<td>'.esc_html__( 'Qty', 'grocery-shop-grocerybuddy' ).'</td>';
		$content .='<td>'.esc_html__( 'Price', 'grocery-shop-grocerybuddy' ).'</td>';
		$content .='</tr></thead>';
		$content .='</table>';
		$content .='<div class="main-products-cart-item" id="theCartHolder">';
		$content .='<div class="cartUpdatingMsg"></div>';
		$content .= wp_nonce_field('products_cart_item_inserted_security', 'products_cart_item_inserted_security_sub');

		$wgby_fees_order_less = get_option ( "wgby_fees_order_less_than" );
		
		$wgby_fees_order_less_than  = (int)$wgby_fees_order_less ;

		if ( is_object( WC()->cart ) && ! is_admin() && ! empty( WC()->cart->get_cart() ) && sizeof( WC()->cart->get_cart() ) > 0 ) {

			$content .='<div class="category_wrapper-box cartThatNeedsToUpdate">';

			$args = array(
				'taxonomy'      => 'product_cat',
				'field' 		=> 'slug',
				'orderby'       => 'id',
				'order'			=> 'ASC',							
				'hide_empty' 	=> true,
			);

			$all_categories = get_categories( $args );

			$my_categories = array();

			//Setting Featured Category

			if ( ! empty( $wb_wg_main_categorie ) ) {
				$Featuredterm = get_term_by( 'slug', $wb_wg_main_categorie, 'product_cat');

				$my_categories[$Featuredterm->term_id] = array(
					'cat_name' => $Featuredterm->name,
					'cat_slug' => $Featuredterm->slug,
					'cat_id' => $Featuredterm->term_id,
					'cart_items' => array(),
				);	
			}

			foreach ($all_categories as $group) {
				if ( $group->slug != $wb_wg_main_categorie ) {
					$my_categories[$group->term_id] = array(
						'cat_name' => $group->name,
						'cat_slug' => $group->slug,
						'cat_id' => $group->term_id,
						'cart_items' => array(),
					);
				}
			}

			$cartItems = $woocommerce->cart->get_cart();

			$cart_array = array();

			foreach( $cartItems as $cartItem ) {
				// gets the product object
				$product_id         = $cartItem['product_id'];
				$line_total         = $cartItem['line_total'];
				$quantity           = $cartItem['quantity'];

				$product            = $cartItem['data'];
				$name               = $product->get_name();
				$categories         = $product->get_category_ids();
				$category = ( is_array( $categories ) && ! empty( $categories ) ) ? $categories[0] : '';

				$theCartArr = array(
					'product_name' => $name,
					'product_id' => $product_id,
					'subtotal' => $line_total,
					'qty' => $quantity,
					'categories' => $category,
				);
				$cart_array[] = $theCartArr;
				
				$my_categories[$category]['cart_items'][] = $theCartArr;
			}

			$my_categories = array_reverse( $my_categories );
			foreach( $my_categories as $theFinalCategory ) {
				if ( ! empty( $theFinalCategory['cart_items'] )  ) {

					$content .='<div class="category_wrapper" id="'.esc_attr( $theFinalCategory['cat_slug'] ).'">';
					$content .='<div class="main-products-cart-box-text">';
					$content .='<h3>'.esc_html( $theFinalCategory['cat_name'] ).'</h3>';
					$content .='</div>';
					$content .='<table>';
					
					$products_array = $theFinalCategory['cart_items'];
					$products_array = array_reverse( $products_array );
					if ( is_array( $products_array ) ) {
						foreach( $products_array as $product_arrayp ) {
							//Something 
							$productName  = $product_arrayp['product_name'];
							$productQty   = $product_arrayp['qty'];
							$productTotal = $product_arrayp['subtotal'];
							$productID    = $product_arrayp['product_id'];
								
							$content .='<tr class="products-cart-item-inserted">';
							$content .='<th><span class="main-products-cart-item-title">'.esc_html( $productName ).'</span></th>';
							$content .='<td>'.esc_html( $productQty ).'</td>';
							$content .='<td>';
							$content .='<span class="main-products-cart-item-text">';
							$content .= wc_price( $productTotal );
							$content .='<a href="#" dt_remove_product_id="'.esc_attr( $productID ).'" class="main-products-cart-item-text wg-remove-cart-item button-remove-item">x</a>';
							$content .='</span>';
							$content .='</td>';
							$content .='</tr>';				
						} //End foreach
					} // End if

					$content .='</table>';
					$content .='</div>';
				} // End If
			} //EndForEach

			$extraFees = 0;
			if ( isset( WC()->cart->cart_contents_total ) && WC()->cart->cart_contents_total > 0 && WC()->cart->cart_contents_total < $wgby_fees_order_less_than ) {
				$extraFees = $wgby_fees_order_less_than-WC()->cart->cart_contents_total;
			}
			if ( $extraFees > 0 ) :
				$content .='<div class="category_wrapper">';
				$content .='<div class="main-products-cart-box-text">';
				$content .='<h3>'.esc_html__( 'Extra Fees', 'grocery-shop-grocerybuddy' ).'</h3>';
				$content .='</div>';
				$content .='<table>';
				$content .='<tr class="products-cart-item-inserted">';
				$content .='<th>';
				$content .='<span class="main-products-cart-item-title">'.esc_html__( 'Fees on order less than', 'grocery-shop-grocerybuddy' ).' '.$woocommerce_currency.''.$wgby_fees_order_less_than.'</span>';
				$content .='</th>';
				$content .='<td>1</td>';
				$content .='<td>';
				$content .='<span class="main-products-cart-item-text">';
				$content .='<span class="woocommerce-Price-amount amount">';
				$content .='<bdi>';				
				$content .='<span class="woocommerce-Price-currencySymbol">'.$woocommerce_currency.'</span>'.$extraFees.'';				
				$content .='</bdi>';
				$content .='</span>';
				$content .='</span>';
				$content .='</td>';
				$content .='</tr>';
				$content .='</table>';
				$content .='</div> <!-- End of Fees Field. /-->';
			endif;

			if (! empty ($wgby_flat_shipping_rate)) {

				$content .='<div class="category_wrapper">';
				$content .='<div class="main-products-cart-box-text">';
				$content .='<h3>'.esc_html__( 'Flat Shipping Rate', 'grocery-shop-grocerybuddy' ).'</h3>';
				$content .='</div>';
				$content .='<table>';
				$content .='<tr class="products-cart-item-inserted">';
				$content .='<th>';
				$content .='<span class="main-products-cart-item-title">'.esc_html__( 'Flat Shipping', 'grocery-shop-grocerybuddy' ).' '.$woocommerce_currency.''.$wgby_flat_shipping_rate.'</span>';
				$content .='</th>';
				$content .='<td>1</td>';
				$content .='<td>';
				$content .='<span class="main-products-cart-item-text">';
				$content .='<span class="woocommerce-Price-amount amount">';
				$content .='<bdi>';				
				$content .='<span class="woocommerce-Price-currencySymbol">'.$woocommerce_currency.'</span>'.$wgby_flat_shipping_rate.'';				
				$content .='</bdi>';
				$content .='</span>';
				$content .='</span>';
				$content .='</td>';
				$content .='</tr>';
				$content .='</table>';
				$content .='</div> <!-- End of Fees Field. /-->';

			}

			$content .='</div>';
			
			$content .='<div class="products-cart-item-view-footer ">';
			$content .='<a class="Proceed-to-checkout-cart-btn" id="finalcontinuebtn" dt-extra-fees="'.esc_html( $extraFees ).'" href="'.esc_url(wc_get_checkout_url()).'">'.esc_html__( 'Continue to Checkout', 'grocery-shop-grocerybuddy' ).'</a>';
			$content .='<span>'.wc_price( WC()->cart->cart_contents_total + $extraFees + $wgby_flat_shipping_rate ).'</span>';
			$content .='</div>';
			$content .='<div class="checkout-cart-below-box">';
			$content .='<a href="#" dt-wg-remove-cart="yes" class="wg-remove-cart-full" type="button">'.esc_html__( 'Clear All', 'grocery-shop-grocerybuddy' ).'</a>';

			$wgby_minimum_order 		        = get_option ( "wgby_minimum_order" );
			$wgby_fees_order_less_than 			= get_option ( "wgby_fees_order_less_than" );

			if (!empty($wgby_fees_order_less_than)) {
				$content .='<br><br><h6>'.esc_html(''.$wgby_minimum_order.' '.$woocommerce_currency.''.$wgby_fees_order_less_than.'').',-</h6>';
			}

			$content .='</div>';
			
		} else {
			$content .='<div class="checkout-cart-below-box-empty">';

			$content .='<img src="'.esc_html(WGBY_GROCERY_BUDDY_URL.'/assets/images/sad.svg' ).'" />';

			$wgby_bag_empty_headings 		  	= get_option ( "wgby_bag_empty_headings" );
			$wgby_bag_empty_description 		= get_option ( "wgby_bag_empty_description" );

			if ( empty ($wgby_bag_empty_headings)) {
				$wgby_bag_empty_headings = esc_html__('Your Grocery bag is empty', 'grocery-shop-grocerybuddy');
			} else {
				$wgby_bag_empty_headings = $wgby_bag_empty_headings;
			}
			
			if (empty ($wgby_bag_empty_description)) {
				$wgby_bag_empty_description = esc_html__('You haven t picked anything yet', 'grocery-shop-grocerybuddy');
			} else {
				$wgby_bag_empty_description = $wgby_bag_empty_description;
			}

			$content .='<h4>'.esc_html( $wgby_bag_empty_headings).'</h4>';
			$content .='<p>'.esc_html( $wgby_bag_empty_description).'</p>';
			$content .='</div>';
		}
		
		$content .='</div> <!-- checkout-cart-below-box /-->';
		$content .='</div> <!-- main-products-cart-box /-->';
		$content .='</div> <!-- Columns -->';
		$content .='</div> <!-- grid /-->';

		$content .='<div class="mobile-cart-item-view-footer">';
		$content .='<div class="mobile-cart-item-view-footer-subtotal">';
		$content .='<button type="button" class="mobile-cart-item-view-footer-subtotal-btn">';
		$content .='<span>'.esc_html__('Continue', 'grocery-shop-grocerybuddy').'</span>';
		$content .='<img src="'.esc_url(WGBY_GROCERY_BUDDY_URL.'/assets/images/chevron.png' ).'" />';			
		$content .='</button>';
		$content .='</div>';
		$content .='<div class="mobile-cart-item-view-footer-continue mobile-cart-dnone">';
		$content .='<button type="button" class="mobile-cart-item-view-footer-continue-btn btn-left">';
		$content .='<img src="'.esc_url(WGBY_GROCERY_BUDDY_URL.'/assets/images/chevron-l.png' ).'" />';
		$content .='<span>'.esc_html__('Back', 'grocery-shop-grocerybuddy').'</span>';
		$content .='</button>';
		$content .='<a href="'.esc_url( wc_get_checkout_url()).'" class="mobile-cart-item-view-footer-continue-btn btn-right">';
		$content .='<span>'.esc_html__('Continue', 'grocery-shop-grocerybuddy').'</span>';
		$content .='<img src="'.esc_url(WGBY_GROCERY_BUDDY_URL.'/assets/images/chevron.png' ).'" />';
		$content .='</a>';
		$content .='</div>';
		$content .='</div>';
	}

	$content .= ob_get_contents();
	ob_end_clean(); 
	return  $content;

}//wc_list_services.
add_shortcode( 'wgby_product_page', 'wgby_product_page' );