// JavaScript Document
(function($) {
    "use strict";
    
    //calling foundation js
    jQuery(document).foundation();

    $( '.mobile-cart-item-view-footer-subtotal button' ).on('click', function() {
        $(".mobile-cart-item-view-footer-continue").removeClass("mobile-cart-dnone");
        $("button.mobile-cart-item-view-footer-subtotal-btn").addClass("mobile-cart-dnone");
        $(".main-products-content .cell.small-12.medium-12.large-8.w70").addClass("mobile-cart-dnone");
        $(".main-products-cart-box").removeClass("mobile-cart-dnone");
    });

    $( 'button.mobile-cart-item-view-footer-continue-btn.btn-left' ).on('click', function() {
        $(".mobile-cart-item-view-footer-continue").addClass("mobile-cart-dnone");
        $("button.mobile-cart-item-view-footer-subtotal-btn").removeClass("mobile-cart-dnone");
        $(".main-products-content .cell.small-12.medium-12.large-8.w70").removeClass("mobile-cart-dnone");
        $(".main-products-cart-box").addClass("mobile-cart-dnone");
    });
    
    $(window).on('load', function() {
        if ($(".main-products-content").hasClass("grid-x")) {
            var $window_width = $(window).width();
            if ($window_width <= 1023) {
                $(".accordion-item:first-child a.accordion-title").trigger('click');
            }
        }
    })

})(jQuery); //jQuery main function ends strict Mode on