<?php
/**
 * License Activation
 *
 * Helps to activate the license
 *
 * @package rentalbuddy
 * @version 1.521
 */

defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', array( 'GB_LICENSE_ACTIVATION', 'getInstance' ) );

class GB_LICENSE_ACTIVATION {

    private $PR_ID_ON_WEBFUL = '143'; //Should match product id on webfulcreations.com/members/products.php for license verification

    private $PURCHASE_EMAIL_ID = 'gb_customer_purchase_email';
    private $PURCHASE_CODE_ID  = 'gb_customer_purchase_code';
    public $LICENSE_DETAILS_ID = 'gb_customer_license_details';

    private $PURCHASE_URL = 'https://www.webfulcreations.com/products/grocerybuddy-grocery-shop-for-woocommerce/';

    private $TABID = 'gb_settings_activation';

    private static $instance = NULL;

	static public function getInstance() {
		if ( self::$instance === NULL )
			self::$instance = new GB_LICENSE_ACTIVATION();
		return self::$instance;
	}

    public function __construct() {
        add_action( 'wcgb_settings_tab_menu_item', array( $this, 'gb_activation_tab_menu_item' ), 12 );
        add_action( 'wcgb_settings_tab_menu_content', array( $this, 'gb_activation_tab_body' ), 12 );

        add_action( 'wp_ajax_gb_check_and_verify_purchase', array( $this, 'gb_check_and_verify_purchase' ) );
	}

    function gb_activation_tab_menu_item() {
        $active = '';

        $menu_output = '<li><hr></li><li class="tabs-title' . esc_attr($active) . '" role="presentation">';
        $menu_output .= '<a href="#' . $this->TABID . '" role="tab" aria-controls="' . $this->TABID . '" aria-selected="true" id="' . $this->TABID . '-label">';
        $menu_output .= '<h2>' . esc_html__( 'Pro Version', 'rentalbuddy' ) . '</h2>';
        $menu_output .=	'</a>';
        $menu_output .= '</li>';

        return wp_kses_post( $menu_output );
    }
	
	function gb_activation_tab_body() {
        global $wpdb;

        $active = '';

		$setting_body = '<div class="tabs-panel team-wrap' . esc_attr($active) . '" 
        id="' . $this->TABID . '" role="tabpanel" aria-hidden="true" aria-labelledby="' . $this->TABID . '-label">';

		$setting_body .= '<div id="license_activation">';

		$theOutPut = $this->gb_activation_form();
		$setting_body .= $theOutPut;
		$setting_body .= '</div><!-- Post Stuff/-->';
		
		$setting_body .= '</div><!-- Tabs Panel /-->';

		$allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
		return wp_kses( $setting_body, $allowedHTML );
	}

    function gb_activation_form() {
        $userEmail 		= get_option( $this->PURCHASE_EMAIL_ID );
        $purchaseCode 	= get_option( $this->PURCHASE_CODE_ID );

        $output = '<div class="purchase_verification_alert"></div>';

        $output .= '<form method="post" id="gb_purchaseVerifiction">
                    <div class="purchase_form_wrap" id="purchase_box_update">';
        $output .= '<div class="grid-x grid-margin-x">';

        $output .= $this->grocerybuddy_purchase_details();

        $output .= '<div class="cell medium-offset-3 medium-6">';
        $output .= '<label for="userEmail">'.esc_html__("Purchaser Email", "rentalbuddy");
        $output .= '<input name="userEmail" type="text" 
        class="form-control login-field" value="'.$userEmail.'" 
        required="" id="userEmail">';
        $output .= '</label>';
        $output .= '</div><!-- Column End /-->';

        $output .= '<div class="cell medium-offset-3 medium-6">';
        $output .= '<label for="purchaseCode">'.esc_html__("Purchase Code", "rentalbuddy");
        $output .= '<input name="purchaseCode" type="password" 
        class="form-control login-field" value="'.$purchaseCode.'" 
        required="" id="purchaseCode">';
        $output .= '</label>';
        $output .= '</div><!-- Column End /-->';

        $output .= '<div class="cell medium-offset-3 medium-6">';
        $output .= '<input type="submit" class="button button-primary" value="'.esc_html__("Verify Purchase", "rentalbuddy").'" />';
        $output .= '</div><!-- Column End /-->';

        $output .= $this->gb_new_purchase_link("license");

        $output .= '</div><!-- Row end /-->';
        $output .= '</div><!-- purchase form end /-->';

        $output .= "</form>";

        return $output;
    }

    function grocerybuddy_purchase_details() {
		//Get purchase data.
		$purchase_arr = get_option( $this->LICENSE_DETAILS_ID );	

		if ( empty( $purchase_arr ) ) {
			return "";
		}
		if ( ! is_array( $purchase_arr ) ) {
			return "";
		}

		$userEmail 		= (isset($purchase_arr["user_email"]) && !empty($purchase_arr["user_email"])) ? $purchase_arr["user_email"] : "";
		$licenseExpiry 	= (isset($purchase_arr["support_until"]) && !empty($purchase_arr["support_until"])) ? $purchase_arr["support_until"] : "";
		$licenseExpiry 	= (!empty($licenseExpiry)) ? date_i18n(get_option('date_format'), strtotime($licenseExpiry)) : "";
		$licenseState 	= (isset($purchase_arr["license_state"]) && !empty($purchase_arr["license_state"])) ? ucfirst($purchase_arr["license_state"]) : "";

		$output = '<div class="cell medium-offset-3 medium-6"><div class="callout success">';
		$output .= "<table>";
		$output .= "<tr>
						<th>".esc_html__("Licensed to", "rentalbuddy")."</th>
						<td>".$userEmail."</td>
					</tr>";
		$output .= "<tr>
					<th>".esc_html__("License Expiry", "rentalbuddy")."</th>
					<td>".$licenseExpiry."</td>
				</tr>";
		$output .= "<tr>
					<th>".esc_html__("License State", "rentalbuddy")."</th>
					<td>".$licenseState."</td>
				</tr>";
		$output .= "</table>";
		$output .= '</div></div>';

		return $output;
	}

    function grocerybuddy_verify_purchase( $userEmail, $purchaseCode ) {

        if ( empty( $userEmail ) ) {
            $userEmail = get_option( $this->PURCHASE_EMAIL_ID );
        }
        if ( empty( $purchaseCode ) ) {
            $purchaseCode = get_option( $this->PURCHASE_CODE_ID );
        }
        if ( ! empty( $purchaseCode ) ) {
            $purchase_code = $purchaseCode;
            update_option( $this->PURCHASE_CODE_ID, $purchase_code );
        }
        if ( ! empty( $userEmail ) ) {
            $user_email = $userEmail;
            update_option( $this->PURCHASE_EMAIL_ID, $user_email );
        }
        if ( ! empty( $user_email ) && ! empty( $purchase_code ) ) {
            $url = 'https://www.webfulcreations.com/members/licensecheck.php';
    
            $args = array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers' 	  => array(),
                'body'        => array(
                    'user_email'     	=> $user_email,
                    'purchase_code' 	=> $purchase_code,
                    'product_id'        => $this->PR_ID_ON_WEBFUL
                ),
                'cookies'     => array()
            );
    
            $response = wp_remote_post( $url, $args );
    
            // error check
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return "Something went wrong: " . esc_html($error_message);
            } else {
                $body = wp_remote_retrieve_body( $response );
                $response = json_decode( $body );
    
                $product_id 	= (empty($response->product_id)) ? "" : $response->product_id;
                $support_until 	= (empty($response->support_until)) ? "" : $response->support_until;
                $license_state 	= (empty($response->license_state)) ? "" : $response->license_state; 
    
                if ( $license_state == "valid" && $product_id != $this->PR_ID_ON_WEBFUL ) {
                    $license_state = esc_html__("Invalid Product", "rentalbuddy");
                }
                $args = array(
                    "product_id" 	=> $product_id,
                    "support_until" => $support_until,
                    "license_state" => $license_state,
                    "user_email" 	=> $user_email,
                    "purchase_code" => $purchase_code
                );
                update_option( $this->LICENSE_DETAILS_ID, $args );
    
                return 'YES';
            }
        }
        //return $output;
    }
        
    function gb_check_and_verify_purchase() {
        global $wpdb;
    
        if ( isset( $_POST["purchaseCode"] ) && isset( $_POST["userEmail"] ) ) {
            $userEmail 		= sanitize_email( $_POST["userEmail"] );
            $purchaseCode 	= sanitize_text_field( $_POST["purchaseCode"] );

            $returned = $this->grocerybuddy_verify_purchase( $userEmail, $purchaseCode );
            $message = ( $returned == 'YES' ) ? esc_html__("Your purchase details updated.", "rentalbuddy") : $returned;	
        } else {
            $message = esc_html__("Record updated!", "rentalbuddy");	
        }
        $values['message'] = $message;
        $values['success'] = "YES";

        wp_send_json($values);
        wp_die();
    }


    function gb_new_purchase_link( $screen ) {
        if ( $screen == "license" ) {
            $output = '<div class="purchase_banner_wc">';
            $output .= '<h2>'.esc_html__("If you don't have license or want to purchase another one click link below.", "rentalbuddy").'</h2>';
            $output .= '<a href="'. esc_url( $this->PURCHASE_URL ) .'" 
            class="button btn-secondary secondary-btn primary" 
            target="_blank">'.esc_html__("Purchase License", "rentalbuddy").'</a>';
            $output .= '<p>'.esc_html__("Please check email for your account details to get your purchase code after buying plugin.", "rentalbuddy").'</p>';
            $output .= '</div>';
        } else {
            $output = '<h2>Print and This detail is available only in Premium Version. <a href="'. esc_url( $this->PURCHASE_URL ) .'" class="button btn-primary primary-btn primary" style="color:#FFF;background:orange;text-transform:uppercase;border:0px;" target="_blank">Check Details</a></h2>';
        }
        return $output;
    }
}