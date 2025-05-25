// JavaScript Document
(function($) {
    "use strict";

    $(document).on("click", ".wg_update-the-cart", function(e) {
		e.preventDefault();

        var $productQuantity      			= $(this).parent('.number-input').children('.wg-update-total').val();
        var $dt_product_id       			= $(this).parent('.number-input').children('.wg-update-total').attr('dt-product_id');
        var $dt_product_name     			= $(this).parent('.number-input').children('.wg-update-total').attr('dt-product_name');
        var $dt_product_category 			= $(this).parent('.number-input').children('.wg-update-total').attr('dt-product_category');
        var $dt_product_cat_slug 			= $(this).parent('.number-input').children('.wg-update-total').attr('dt-product-cat-slug');
        var $dt_product_cat_name 			= $(this).parent('.number-input').children('.wg-update-total').attr('dt-product-cat-name');
        var $dt_product_price 				= $(this).parent('.number-input').children('.wg-update-total').attr('dt-product-price');
		var $wgby_product_add_security_sub  = $('#wgby_product_add_security_sub').val();

		$.ajax({
			type: 'POST',
			data: {
				'action': 'wgby_update_cart',
                'productQuantity': $productQuantity,
                'dt_product_id': $dt_product_id,
                'dt_product_name': $dt_product_name,
                'dt_product_category': $dt_product_category,
                'dt_product_cat_slug': $dt_product_cat_slug,
                'dt_product_cat_name': $dt_product_cat_name,
                'dt_product_price': $dt_product_price,
				'wgby_product_add_security_sub': $wgby_product_add_security_sub	
			},
			url: ajax_obj.ajax_url,
			dataType: 'json',

			beforeSend: function() {
				$('.cartUpdatingMsg').html("<div class='loader'></div>");
				$("#theCartHolder").css( 'opacity', '0.5');
			},
			success: function(response) {
				//console.log(response);
				var message = response.message;
				///#theCartHolder .cartThatNeedsToUpdate
				$('#theCartHolder').load(location.href + " #theCartHolder", function() {
					/* When load is done */
					$("#theCartHolder").css('opacity', '1');
					$('.cartUpdatingMsg').html(message);
			  	});
			}
		});
	});

	$(document).on("click", ".wg-remove-cart-item", function(e) {
		e.preventDefault();

        var $dt_product_id 									= $(this).attr('dt_remove_product_id');
		var $products_cart_item_inserted_security_sub      	= $('#products_cart_item_inserted_security_sub').val();

		$.ajax({
			type: 'POST',
			data: {
				'action': 'wgby_remove_the_cart',
                'dt_product_id': $dt_product_id,
				'products_cart_item_inserted_security_sub': $products_cart_item_inserted_security_sub,
				'type':'remove_item',
			},
			url: ajax_obj.ajax_url,
			dataType: 'json',

			beforeSend: function() {
				$('.cartUpdatingMsg').html("<div class='loader'></div>");
				$("#theCartHolder").css( 'opacity', '0.5');
			},
			success: function(response) {
				//console.log(response);
				var message = response.message;
				///#theCartHolder .cartThatNeedsToUpdate
				$('#theCartHolder').load(location.href + " #theCartHolder", function() {
					/* When load is done */
					$("#theCartHolder").css('opacity', '1');
					$('.cartUpdatingMsg').html(message);
			  	});
			}
		});
	});

	$(document).on("click", ".wg-remove-cart-full", function(e) {
		e.preventDefault();

		var $products_cart_item_inserted_security_sub      	= $('#products_cart_item_inserted_security_sub').val();

		$.ajax({
			type: 'POST',
			data: {
				'action': 'wgby_remove_the_cart',
				'products_cart_item_inserted_security_sub': $products_cart_item_inserted_security_sub,
				'type':'empty_cart',
			},
			url: ajax_obj.ajax_url,
			dataType: 'json',

			beforeSend: function() {
				$('.cartUpdatingMsg').html("<div class='loader'></div>");
				$("#theCartHolder").css( 'opacity', '0.5');
			},
			success: function(response) {
				//console.log(response);
				var message = response.message;
				///#theCartHolder .cartThatNeedsToUpdate
				$('#theCartHolder').load(location.href + " #theCartHolder", function() {
					/* When load is done */
					$("#theCartHolder").css('opacity', '1');
					$('.cartUpdatingMsg').html(message);
			  	});
			}
		});
	});
	
})(jQuery); //jQuery main function ends strict Mode on