<?php
/**
 * The file contains the functions related to Pro Version Setting Page
 *
 * Help setup pages to they can be used in notifications and other items
 *
 * @package grocery-shop-grocerybuddy
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

class WCGB_SETTING_PRO_VERSION {
	private $TABID = "wcrb_styling";

	function __construct() {
        add_filter( 'wcgb_settings_tab_menu_item', array( $this, 'wcgb_setting_pro_menu_item' ), 10, 2 );
        add_filter( 'wcgb_settings_tab_menu_content', array( $this, 'wcgb_setting_pro_menu_content' ), 10, 2 );
    }

	function wcgb_setting_pro_menu_item() {
        $active = '';

        $menu_output = '<li class="tabs-title' . esc_attr( $active ) . '" role="presentation">';
        $menu_output .= '<a href="#' . esc_attr( $this->TABID ) . '" role="tab" aria-controls="' . esc_attr( $this->TABID ) . '" aria-selected="true" id="' . esc_attr( $this->TABID ) . '-label">';
        $menu_output .= '<h2>' . esc_html__( 'Pro Version', 'grocery-shop-grocerybuddy' ) . '</h2>';
        $menu_output .=	'</a>';
        $menu_output .= '</li>';

        return wp_kses_post( $menu_output );
    }
	
	function wcgb_setting_pro_menu_content() {
        $active = '';

		$setting_body = '<div class="tabs-panel team-wrap' . esc_attr( $active ) . '" 
        id="' . esc_attr( $this->TABID ) . '" 
        role="tabpanel" 
        aria-hidden="true" 
        aria-labelledby="' . esc_attr( $this->TABID ) . '-label">';

		$setting_body .= '<div class="wc-rb-manage-devices pro_version">';
		$setting_body .= '<h2>' . esc_html__( 'Pro Version', 'grocery-shop-grocerybuddy' ) . '</h2>';

		$setting_body .= '<p class="pro-features">Support the development of this awesome plugin and enjoy our premium support with great existing features in our pro version and also contineously increasing more features.</p>';
		$setting_body .= '<ul class="pro-features">';
        $setting_body .= '<li>- Variations Support</li>';
        $setting_body .= '<li>- Ability to Exclude Categories</li>';
        $setting_body .= '<li>- Ability to Exclude Products by IDs</li>';
        $setting_body .= '<li>- Sort Categories Ability</li>';
        $setting_body .= '<li>- Flat Shipping Rate</li>';
		$setting_body .= '<li>- Order Discount If meet minimum criteria</li>';
		$setting_body .= '<li>- Additional or Shipment fee if does not meet minimum criteria. e.g $50 or less orders additional fees 10$</li>';
		$setting_body .= '<li>- Better Styling Options</li>';
		$setting_body .= '<li>- 24/7 Premium Support</li>';
		$setting_body .= '</ul>';
		
		$setting_body .= '<h2 class="pro-features">' . esc_html__( 'Get Pro Version only in 29$', 'grocery-shop-grocerybuddy' ) . '</h2>';
		$setting_body .= '<a href="" class="button primary pro-features">' . esc_html__( 'Get Pro Version', 'grocery-shop-grocerybuddy' ) . '</a>';

		$setting_body .= '</div><!-- wc rb Devices /-->';
		$setting_body .= '</div><!-- Tabs Panel /-->';

		$allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
		return wp_kses( $setting_body, $allowedHTML );
	}
}