// JavaScript Document
(function($) {
    "use strict";
    
    //Change Tax Status Functionality
	$(document).on("submit", "#gb_purchaseVerifiction", function(e, target){
		e.preventDefault();

		var $userEmail 		= $("#userEmail").val();
		var $SpurchaseCode 	= $("#purchaseCode").val();

		if($userEmail == "" && $SpurchaseCode == "") {
			alert("Please enter both values!");
		} else {
			$.ajax({
				type: 'POST',
				data: {
					'action': 'gb_check_and_verify_purchase',
					'purchaseCode': $SpurchaseCode, 
					'userEmail': $userEmail 
				},
				url: ajax_obj.ajax_url,
				dataType: 'json',
	
				beforeSend: function() {
					$('.purchase_verification_alert').html("<div class='spinner is-active'></div>");
				},
				success: function(response) {
					//console.log(response);
					var message 	= response.message;
					var success 	= response.success;
					
					$('.purchase_verification_alert').html('<div class="callout success" data-closable="slide-out-right">'+message+'<button class="close-button" aria-label="Dismiss alert" type="button" data-close><span aria-hidden="true">&times;</span></button></div>');
					$("#purchase_box_update").load(window.location + " #purchase_box_update > *");
				}
			});
		}
	});

})(jQuery); //jQuery main function ends strict Mode on