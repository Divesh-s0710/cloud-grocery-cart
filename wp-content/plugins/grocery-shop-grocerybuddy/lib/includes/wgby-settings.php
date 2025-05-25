<?php
defined( 'ABSPATH' ) || exit;

if(!function_exists("wgby_settings_page")):
  function wgby_settings_page () {

    if ( !current_user_can ( 'manage_options' ) ) {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $wgby_categories_get_list = wgby_get_categories_list_post_type ('product');

    if ( isset ( $wgby_categories_get_list ) && ! empty ($wgby_categories_get_list) ) {
        $wgby_categories_get_list = wgby_get_categories_list_post_type ('product');
    } else {
        $wgby_categories_get_list = esc_html__( 'Add product categories', 'grocery-shop-grocerybuddy' );
    }

    $content ='';
    $content .= '<div class="main-wrap wcrb_dashboard_nav wcrb_dashboard_section">';
    $content .= '<div class="page-title">';
    $content .= '<h4>'.esc_html__( 'Webful Grocery', 'grocery-shop-grocerybuddy' ).'</h4>';
    $content .= '</div>';
    
    $content .= '<form method="post">';
    $content .= wp_nonce_field( 'wgby_meta_settings_nonce', 'wgby_settings_sub' );
    $content .= '<table cellpadding="5" cellspacing="5" class="hover">';
    $content .= '<fieldset class="large-5 cell">';    
    $content .= '<legend> ' . esc_html__( 'Select Featured Category To Show Products on Top! Suitable for bundles like Fruits Basket, Vegitables Bag', 'grocery-shop-grocerybuddy' ) . ' </legend>';
    $content .= $wgby_categories_get_list;
    $content .= '</fieldset>';   
    $content .= '<tr>';
    $content .= '<td>';
    $content .= '<input 
                    class="button button-primary" 
                    type="Submit"  
                    value="'.esc_html__("Save Changes", "grocery-shop-grocerybuddy").'"/>';
    $content .= '</td>';
    $content .= '<td>';
    $content .= '<input type="hidden" name="wgby_version_settings" value="1" />';
    $content .= '</td>';
    $content .= '</tr>';
    $content .= '</table>';

    $content .='</form>';

    $content .='</div>';
    $content .='<div class="main-wrap wcrb_dashboard_nav wcrb_dashboard_section">';
    $content .='<div class="page-title">';
    $content .='<h4>'.esc_html__( 'Shortcodes', 'grocery-shop-grocerybuddy' ).'</h4>';
    $content .='</div>';
    $content .='<div class="documentation-section">';
    $content .='<h4>'.esc_html__( 'Main Order Page', 'grocery-shop-grocerybuddy' ).'</h4>';
    $content .= '<p>' . esc_html__( 'Plase create a new page and add the shortcode you see below that page will include your products to checkout from single page.', 'grocery-shop-grocerybuddy' ) . '</p>';
    
    $content .='<pre>[wgby_product_page]</pre>';
    
    $content .='</div>';
    $content .='</div>';

    $allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
    return wp_kses( $content, $allowedHTML );
}
endif;

if(!function_exists("wgby_settings_page_options")):
    function wgby_settings_page_options () {
  
        if ( !current_user_can ( 'manage_options' ) ) {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        
        $wgby_main_menu_name_wg 		    = get_option ( "wgby_main_menu_name_wg" );
        $wgby_bag_empty_headings 	        = get_option ( "wgby_bag_empty_headings" );
        $wgby_bag_empty_description 		= get_option ( "wgby_bag_empty_description" );
        $wgby_fees_order_less_than 		    = get_option ( "wgby_fees_order_less_than" );
        $wgby_minimum_order 		        = get_option ( "wgby_minimum_order" );
        $wgby_flat_shipping_rate 		    = get_option ( "wgby_flat_shipping_rate" );

        if ( empty ( $wgby_main_menu_name_wg ) ) {
            $wgby_main_menu_name_wg = esc_html__( 'Webful Grocery', 'grocery-shop-grocerybuddy' );
        } else {
            $wgby_main_menu_name_wg = $wgby_main_menu_name_wg ;
        }
        if ( empty ($wgby_bag_empty_headings)) {
            $wgby_bag_empty_headings = esc_html__("Your Grocery bag is empty", "grocery-shop-grocerybuddy");
        } else {
            $wgby_bag_empty_headings = $wgby_bag_empty_headings;
        }
        if (empty ($wgby_bag_empty_description)) {
            $wgby_bag_empty_description = esc_html__("You haven t picked anything yet.", "grocery-shop-grocerybuddy");
        } else {
            $wgby_bag_empty_description = $wgby_bag_empty_description;
        }
        if (empty ($wgby_minimum_order)) {
            $wgby_minimum_order = esc_html__("Extra Fee on Order Less Than", "grocery-shop-grocerybuddy");
        } else {
            $wgby_minimum_order = $wgby_minimum_order;
        }
        if (empty ($wgby_fees_order_less_than)) {
            $wgby_fees_order_less_than = "0";
        } else {
            $wgby_fees_order_less_than = $wgby_fees_order_less_than;
        }
        if (empty ($wgby_flat_shipping_rate)) {
            $wgby_flat_shipping_rate = "0";
        } else {
            $wgby_flat_shipping_rate = $wgby_flat_shipping_rate;
        }
    
        $content ='';
        $content .= '<div class="main-wrap wcrb_dashboard_nav wcrb_dashboard_section">';
        $content .= '<div class="page-title">';
        $content .= '<h4>'.esc_html__( 'Options', 'grocery-shop-grocerybuddy' ).'</h4>';
        $content .= '</div>';
        
        $content .= '<form method="post">';
        $content .= wp_nonce_field( 'wgby_meta_settings_nonce_two', 'wgby_settings_sub_two' );
        $content .= '<table cellpadding="5" cellspacing="5" class="hover">';
        $content .= '<tr>';
        $content .= '<th scope="row">';
        $content .= '<label for="wgby_main_menu_name_wg">'.esc_html__("Menu Name e.g Webful Grocery", "grocery-shop-grocerybuddy").'</label>';
        $content .= '</th>';
        $content .= '<td>';    
        $content .= '<input 
                        name="wgby_main_menu_name_wg" 
                        id="wgby_main_menu_name_wg" 
                        class="regular-text" 
                        value="'.esc_html($wgby_main_menu_name_wg).'" 
                        type="text" 
                        placeholder="'.esc_html__("Enter Menu Name Default WordPress Accounting", "grocery-shop-grocerybuddy").'"/>';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th scope="row">';
        $content .= '<label for="wgby_bag_empty_headings">'.esc_html__("Bag Is Empty Headings", "grocery-shop-grocerybuddy").'</label>';
        $content .= '</th>';
        $content .= '<td>';
        $content .= '<input 
                        name="wgby_bag_empty_headings" 
                        id="wgby_bag_empty_headings" 
                        class="regular-text" 
                        value="'.esc_html($wgby_bag_empty_headings).'" 
                        type="text" 
                        placeholder="'.esc_html__("Bag Is Empty Headings", "grocery-shop-grocerybuddy").'"/>';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th scope="row">';
        $content .= '<label for="wgby_bag_empty_description">'.esc_html__("Bag Is Empty Description", "grocery-shop-grocerybuddy").'</label>';
        $content .= '</th>';
        $content .= '<td>';
        $content .= '<input 
                        name="wgby_bag_empty_description" 
                        id="wgby_bag_empty_description" 
                        class="regular-text" 
                        value="'.esc_html($wgby_bag_empty_description).'" 
                        type="text" 
                        placeholder="'.esc_html__("Bag Is Empty Description", "grocery-shop-grocerybuddy").'"/>';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th scope="row">';
        $content .= '<label for="wgby_minimum_order">'.esc_html__("Apply Extra Fees Label", "grocery-shop-grocerybuddy").'</label>';
        $content .= '</th>';
        $content .= '<td>';
        $content .= '<input 
                        name="wgby_minimum_order" 
                        id="wgby_minimum_order" 
                        class="regular-text" 
                        value="' . esc_html( $wgby_minimum_order ) . '" 
                        type="text" 
                        placeholder="'.esc_html__("Extra Fee on Order Less Than", "grocery-shop-grocerybuddy").'"/>';
        $content .= '</td>';
        $content .= '</tr>';

        $content .= '<tr>';
        $content .= '<th scope="row">';
        $content .= '<label for="wgby_fees_order_less_than">'.esc_html__("Extra fees to apply if order value is less than minimum", "grocery-shop-grocerybuddy").'</label>';
        $content .= '</th>';
        $content .= '<td>';
        $content .= '<input 
                        name="wgby_fees_order_less_than" 
                        id="wgby_fees_order_less_than" 
                        class="regular-text" 
                        value="' . esc_html( $wgby_fees_order_less_than ) . '" 
                        type="number"/>';
        $content .= '</td>';
        $content .= '</tr>';

        $content .= '<tr>';
        $content .= '<th scope="row">';
        $content .= '<label for="wgby_flat_shipping_rate">'.esc_html__("Flat Shipping Rate", "grocery-shop-grocerybuddy").'</label>';
        $content .= '</th>';
        $content .= '<td>';
        $content .= '<input 
                        name="wgby_flat_shipping_rate" 
                        id="wgby_flat_shipping_rate" 
                        class="regular-text" 
                        value="' . esc_html( $wgby_flat_shipping_rate ) . '" 
                        type="number"/>';
        $content .= '</td>';
        $content .= '</tr>';



        $content .= '<tr>';
        $content .= '<td>';
        $content .= '<input 
                        class="button button-primary" 
                        type="Submit"  
                        value="'.esc_html__("Save Changes", "grocery-shop-grocerybuddy").'"/>';
        $content .= '</td>';
        $content .= '<td>';
        $content .= '<input type="hidden" name="wgby_version_settings" value="2" />';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
    
        $content .='</form>';
    
        $content .='</div>';
    
        $allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
        return wp_kses( $content, $allowedHTML );

    }
endif;

if(!function_exists("wgby_settings_page_style")):
    function wgby_settings_page_style () {
  
        if ( !current_user_can ( 'manage_options' ) ) {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }

        $wgby_color_primer 		            = get_option ( "wgby_color_primer" );   
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
        $content .= '<div class="main-wrap wcrb_dashboard_nav wcrb_dashboard_section">';
        $content .= '<div class="page-title">';
        $content .= '<h4>'.esc_html__( 'Style', 'grocery-shop-grocerybuddy' ).'</h4>';
        $content .= '</div>';
        
        if ( ! gb_license_state() ) {
            $content .= '<h4>'.esc_html__("Not an active Version", "grocery-shop-grocerybuddy").'</h4>';
        }

        $content .= '<form method="post">';
        $content .= wp_nonce_field( 'wgby_meta_settings_nonce_three', 'wgby_settings_sub_three' );

        $content .= '<table>';
        $content .= '<tr>';
        $content .= '<th>';
        $content .= '<label for="wgby_color_primer">'.esc_html__("Color Primer", "grocery-shop-grocerybuddy").'</label>';
        $content .= '<input 
                    name="wgby_color_primer" 
                    id="wgby_color_primer" 
                    class="wgby-color-field" 
                    value="' . esc_html( $wgby_color_primer ) . '" 
                    type="text" />';
        $content .= '</th>';
        $content .= '<th>';
        $content .= '<label for="wgby_color_secondary">'.esc_html__("Color Secondary", "grocery-shop-grocerybuddy").'</label>';
        $content .= '<input 
                    name="wgby_color_secondary" 
                    id="wgby_color_secondary" 
                    class="wgby-color-field" 
                    value="'.esc_html($wgby_color_secondary).'" 
                    type="text" />';
        $content .= '</th>';
        $content .= '<th>';
        $content .= '<label for="wgby_color_headings">'.esc_html__("Color Headings", "grocery-shop-grocerybuddy").'</label>';
        $content .= '<input 
                    name="wgby_color_headings" 
                    id="wgby_color_headings" 
                    class="wgby-color-field" 
                    value="'.esc_html($wgby_color_headings).'" 
                    type="text" />';
        $content .= '</th>';
        $content .= '<th>';
        $content .= '<label for="wgby_color_text">'.esc_html__("Color Text", "grocery-shop-grocerybuddy").'</label>';
        $content .= '<input 
                    name="wgby_color_text" 
                    id="wgby_color_text" 
                    class="wgby-color-field" 
                    value="'.esc_html($wgby_color_text).'" 
                    type="text" />';
        $content .= '</th>';
        $content .= '<th><label for="wgby_color_transparent_text">'.esc_html__("Color Transparent", "grocery-shop-grocerybuddy").'</label>';
        $content .= '<input 
                    name="wgby_color_transparent_text" 
                    id="wgby_color_transparent_text" 
                    class="wgby-color-field" 
                    value="'.esc_html($wgby_color_transparent_text).'" 
                    type="text" />';
        $content .= '</th>';
        $content .= '</tr>';

        $content .= '<tr>';
        $content .= '<td>';
        $content .= '<input 
                        class="button button-primary" 
                        type="Submit"  
                        value="'.esc_html__("Save Changes", "grocery-shop-grocerybuddy").'"/>';
        $content .= '</td>';
        $content .= '<td>';
        $content .= '<input type="hidden" name="wgby_version_settings" value="3" />';
        $content .= '</td>';
        $content .= '</tr>';
        
        $content .= '</table>';

        $content .='</form>';  
        $content .='</div>';

        $allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
        return wp_kses( $content, $allowedHTML );
    }
endif;

if(!function_exists("wgby_settings_advanced_settings")):
    function wgby_settings_advanced_settings () {
        if ( !current_user_can ( 'manage_options' ) ) {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }

        $content ='';
        $content .= '<div class="main-wrap wcrb_dashboard_nav wcrb_dashboard_section">';
        $content .= '<div class="page-title">';
        $content .= '<h4>'.esc_html__( 'Advanced Settings', 'grocery-shop-grocerybuddy' ).'</h4>';
        $content .= '</div>';
        
        if ( ! gb_license_state() ) {
            $content .= '<h4>'.esc_html__("Not an active Version", "grocery-shop-grocerybuddy").'</h4>';
        }

        $content .= '<form method="post">';
        $content .= wp_nonce_field( 'wgby_meta_settings_nonce_four', 'wgby_settings_sub_four' );

        $content .= '<table>';

        $content .= '<tr>';
            $content .= '<th>';
                $wgby_categories_get_list = wgby_get_categories_option_list_post_type ('product');

                if ( isset ( $wgby_categories_get_list ) && ! empty ($wgby_categories_get_list) ) {
                    $wgby_categories_the_get_list = wgby_get_categories_option_list_post_type ('product');
                } else {
                    $wgby_categories_the_get_list = '<option value="">'.esc_html__( 'Add product categories', 'grocery-shop-grocerybuddy' ).'</option>';
                }

                $content .= '<label for="wgby_exclude_categories">'.esc_html__("Exclude Categories", "grocery-shop-grocerybuddy").'</label>';
                $content .= '<select class="wgby_exclude_categories" name="wgby_exclude_categories[]" multiple="multiple">';    
                $content .= $wgby_categories_the_get_list;
                $content .='</select> ';
            $content .= '</th>';                
        $content .= '</tr>';

        $content .= '<tr>';
            $content .= '<th>';
                $wgby_products_get_list = wgby_get_categories_option_product_list ('product');

                if ( isset ( $wgby_products_get_list ) && ! empty ($wgby_products_get_list) ) {
                    $wgby_products_get_list = wgby_get_categories_option_product_list ('product');
                } else {
                    $wgby_products_get_list = '<option value="">'.esc_html__( 'Add products', 'grocery-shop-grocerybuddy' ).'</option>';
                }

                $content .= '<label for="wgby_exclude_products">'.esc_html__("Exclude Products", "grocery-shop-grocerybuddy").'</label>';
                $content .= '<select class="wgby_exclude_products" name="wgby_exclude_products[]" multiple="multiple">';    
                $content .= $wgby_products_get_list;
                $content .='</select> ';
            $content .= '</th>';                
        $content .= '</tr>';

        $content .= '<tr>';

            $content .= '<th>';
                $wgby_categories_get_list = wgby_get_categories_sort_list_post_type ('product');

                if ( isset ( $wgby_categories_get_list ) && ! empty ($wgby_categories_get_list) ) {
                    $wgby_categories_the_get_list = wgby_get_categories_sort_list_post_type ('product');
                } else {
                    $wgby_categories_the_get_list = '<option value="">'.esc_html__( 'Add product categories', 'grocery-shop-grocerybuddy' ).'</option>';
                }

                $content .= '<label for="wgby_sort_categories">'.esc_html__("Sort Categories", "grocery-shop-grocerybuddy").'</label>';
                $content .= '<select class="wgby_sort_categories" name="wgby_sort_categories[]" multiple="multiple">';    

                $content .= $wgby_categories_the_get_list;
                $content .='</select> ';

                $wgby_sort_categories  		= unserialize(get_option ( "wgby_sort_categories" ));

                if (! empty ($wgby_sort_categories)){
                    $i = 0;                
                    $content .= '<br>';
                    foreach ($wgby_sort_categories as $cat) {
                        if (is_numeric($cat)){
                            $catname = get_the_category_by_ID($cat);
                            $content .= $i.'. ' .$catname;
                            $content .= '<br>';
                            $i++;
                        }   
                    }
                    $content .= '<label for="wgby_sort_categories_Reset">'.esc_html__("Sort Categories Reset to Default", "grocery-shop-grocerybuddy") ;
                    $content .= ' <input type="checkbox" id="wgby_sort_categories_Reset" name="wgby_sort_categories_Reset" value="Default"></label>';
                }

            $content .= '</th>';        
        $content .= '</tr>';

        $content .= '<tr>';
            $content .= '<td>';
            $content .= '<input 
                            class="button button-primary" 
                            type="Submit"  
                            value="'.esc_html__("Save Changes", "grocery-shop-grocerybuddy").'"/>';
            $content .= '</td>';
            $content .= '<td>';
            $content .= '<input type="hidden" name="wgby_version_settings" value="4" />';
            $content .= '</td>';
        $content .= '</tr>';
        
        $content .= '</table>';

        $content .='</form>';  
        $content .='</div>';

        $allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
        return wp_kses( $content, $allowedHTML );

    }
endif;

if ( ! function_exists( 'wgby_version_settings_submission' ) ) :
    function wgby_version_settings_submission() {
        global $wpdb; //to use database functions inside function.
  
        if ( isset ( $_POST ['wgby_version_settings'] ) && sanitize_text_field($_POST ['wgby_version_settings']) == '1') {

            // Verify that the nonce is valid.
            if ( ! isset( $_POST['wgby_settings_sub'] ) || ! wp_verify_nonce ( sanitize_key( $_POST['wgby_settings_sub']), 'wgby_meta_settings_nonce' ) ) {
                return;
            }
            
            $wgby_main_categories_list_name = ( ! isset( $_POST['wgby_main_categories_list_name'] ) ) ? '' : sanitize_text_field( $_POST['wgby_main_categories_list_name'] );
            update_option ('wgby_main_categories_list_name', $wgby_main_categories_list_name );
            
            //Show message.
            add_action    ("admin_notices", "wgby_main_settings_saved" );

        }

        if ( isset ( $_POST ['wgby_version_settings'] ) && sanitize_text_field($_POST ['wgby_version_settings']) == '2') {

            // Verify that the nonce is valid.
            if ( ! isset( $_POST['wgby_settings_sub_two'] ) || ! wp_verify_nonce ( sanitize_key( $_POST['wgby_settings_sub_two']), 'wgby_meta_settings_nonce_two' ) ) {
                return;
            }
            
            $wgby_main_menu_name_wg         = ( ! isset( $_POST['wgby_main_menu_name_wg'] ) ) ? '' : sanitize_text_field( $_POST['wgby_main_menu_name_wg'] );
            $wgby_bag_empty_headings        = ( ! isset( $_POST['wgby_bag_empty_headings'] ) ) ? '' : sanitize_text_field( $_POST['wgby_bag_empty_headings'] );
            $wgby_bag_empty_description     = ( ! isset( $_POST['wgby_bag_empty_description'] ) ) ? '' : sanitize_text_field( $_POST['wgby_bag_empty_description'] );
            $wgby_fees_order_less_than      = ( ! isset( $_POST['wgby_fees_order_less_than'] ) ) ? '' : sanitize_text_field( $_POST['wgby_fees_order_less_than'] );
            $wgby_minimum_order             = ( ! isset( $_POST['wgby_minimum_order'] ) ) ? '' : sanitize_text_field( $_POST['wgby_minimum_order'] );            
            $wgby_flat_shipping_rate        = ( ! isset( $_POST['wgby_flat_shipping_rate'] ) ) ? '' : sanitize_text_field( $_POST['wgby_flat_shipping_rate'] );

            update_option ('wgby_main_menu_name_wg', $wgby_main_menu_name_wg );
            update_option ('wgby_bag_empty_headings', $wgby_bag_empty_headings );
            update_option ('wgby_bag_empty_description', $wgby_bag_empty_description );
            update_option ('wgby_fees_order_less_than', $wgby_fees_order_less_than );
            update_option ('wgby_minimum_order', $wgby_minimum_order );
            update_option ('wgby_flat_shipping_rate', $wgby_flat_shipping_rate );

            //Show message.
            add_action    ("admin_notices", "wgby_main_settings_saved" );

        }

        if ( isset ( $_POST ['wgby_version_settings'] ) && sanitize_text_field($_POST ['wgby_version_settings']) == '3') {

            if ( ! isset( $_POST['wgby_settings_sub_three'] ) || ! wp_verify_nonce ( sanitize_key( $_POST['wgby_settings_sub_three']), 'wgby_meta_settings_nonce_three' ) ) {
                return;
            }

            if ( gb_license_state() ) {

                $wgby_color_primer = ( ! isset( $_POST['wgby_color_primer'] ) ) ? '' : sanitize_text_field( $_POST['wgby_color_primer'] );
                $wgby_color_secondary = ( ! isset( $_POST['wgby_color_secondary'] ) ) ? '' : sanitize_text_field( $_POST['wgby_color_secondary'] );
                $wgby_color_headings = ( ! isset( $_POST['wgby_color_headings'] ) ) ? '' : sanitize_text_field( $_POST['wgby_color_headings'] );
                $wgby_color_text = ( ! isset( $_POST['wgby_color_text'] ) ) ? '' : sanitize_text_field( $_POST['wgby_color_text'] );
                $wgby_color_transparent_text = ( ! isset( $_POST['wgby_color_transparent_text'] ) ) ? '' : sanitize_text_field( $_POST['wgby_color_transparent_text'] );

                update_option ('wgby_color_primer', $wgby_color_primer );
                update_option ('wgby_color_secondary', $wgby_color_secondary );
                update_option ('wgby_color_headings', $wgby_color_headings );
                update_option ('wgby_color_text', $wgby_color_text );
                update_option ('wgby_color_transparent_text', $wgby_color_transparent_text );
                
                //Show message.
                add_action    ("admin_notices", "wgby_main_settings_saved" );

            } else {
                //Show message.
                add_action    ("admin_notices", "wgby_main_settings_pro" );
            }

        }

        if ( isset ( $_POST ['wgby_version_settings'] ) && sanitize_text_field($_POST ['wgby_version_settings']) == '4') {

            if ( ! isset( $_POST['wgby_settings_sub_four'] ) || ! wp_verify_nonce ( sanitize_key( $_POST['wgby_settings_sub_four']), 'wgby_meta_settings_nonce_four' ) ) {
                return;
            }
            if ( gb_license_state() ) {

                $wgby_exclude_categories        = ( ! isset( $_POST['wgby_exclude_categories'] ) ) ? '' : sanitize_text_field(serialize($_POST['wgby_exclude_categories'])) ;
                $wgby_exclude_products          = ( ! isset( $_POST['wgby_exclude_products'] ) ) ? '' : sanitize_text_field(serialize($_POST['wgby_exclude_products'])) ;
                $wgby_sort_categories           = ( ! isset( $_POST['wgby_sort_categories'] ) ) ? '' : sanitize_text_field(serialize($_POST['wgby_sort_categories'])) ;
                $wgby_sort_categories_Reset     = ( ! isset( $_POST['wgby_sort_categories_Reset'] ) ) ? '' : sanitize_text_field($_POST['wgby_sort_categories_Reset']) ;

                update_option ('wgby_exclude_categories', $wgby_exclude_categories );
                update_option ('wgby_exclude_products', $wgby_exclude_products );

                if (! empty ($wgby_sort_categories)) {
                    update_option ('wgby_sort_categories', $wgby_sort_categories );
                }

                if (! empty ($wgby_sort_categories_Reset) && ($wgby_sort_categories_Reset == 'Default')) {
                    update_option ('wgby_sort_categories', '' );
                }
                //Show message.
                add_action    ("admin_notices", "wgby_main_settings_saved" );

            } else {
                //Show message.
                add_action    ("admin_notices", "wgby_main_settings_pro" );
            }

        }
  
    }//End of wgby_version_settings_submission()
  
    add_action( 'admin_init', 'wgby_version_settings_submission' );
  
endif;

if ( !function_exists ("wgby_main_settings_saved") ):
    function wgby_main_settings_saved () {
        $content = '<div class="updated">';
        $content .= '<p>'.esc_html__("Settings saved!", "grocery-shop-grocerybuddy").'</p>';
        $content .= '</div>';
        $allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
        echo wp_kses( $content, $allowedHTML );    
    }
endif;

if ( !function_exists ("wgby_main_settings_pro") ):
    function wgby_main_settings_pro () {
        $content = '<div class="notice notice-warning">';
        $content .= '<h5>'.esc_html__("Pro feature please purchase license for GroceryShop", "grocery-shop-grocerybuddy").'</h5>';
        $content .= '</div>';
        $allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
        echo wp_kses( $content, $allowedHTML );    
    }
endif;