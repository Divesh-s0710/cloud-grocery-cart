<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wgby_dashboard_page' ) ) :
    function wgby_dashboard_page () {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die ( esc_html__( 'You do not have sufficient permissions to access this page.', 'grocery-shop-grocerybuddy' ) );
        }
        
        $General_settings = 'is-active';
        $General_settingstwo = $General_settingsthree = $advanced_settings_four = '';
        
        if ( isset ( $_POST ['wgby_version_settings'] ) && sanitize_text_field($_POST ['wgby_version_settings']) == '2') {
            $General_settingstwo = 'is-active';
            $General_settings = '';
        }
        if ( isset ( $_POST ['wgby_version_settings'] ) && sanitize_text_field($_POST ['wgby_version_settings']) == '3') {
            $General_settingsthree = 'is-active';
            $General_settings = '';
        }

        if ( isset ( $_POST ['wgby_version_settings'] ) && sanitize_text_field($_POST ['wgby_version_settings']) == '4') {
            $advanced_settings_four = 'is-active';
            $General_settings = '';
        }

        

        $content ='';

        $content .='<div class="main-container webful-grocery">';
        $content .='<div class="grid-x grid-container grid-margin-x grid-padding-y" style="width:100%;">';
        $content .='<div class="large-12 medium-12 small-12 cell">';
        $content .='<div class="team-wrap grid-x" data-equalizer data-equalize-on="medium">';
        $content .='<div class="cell medium-3 thebluebg sidebarmenu">';

        $logourl = WGBY_GROCERY_BUDDY_URL . '/assets/admin/images/logo.png';
        $content .= '<div class="the-brand-logo"><img src="' . esc_url( $logourl ) . '" class="gb-menu-logo" /></div>';

        $content .='<ul class="vertical tabs thebluebg" data-tabs="82ulyt-tabs" id="example-tabs">';
        
        $content .='<li class="tabs-title '.esc_attr($General_settings).' " role="presentation">
                    <a href="#panel1" role="tab" aria-controls="panel1" aria-selected="false" id="panel1-label">
                        <h2>'.esc_html__( 'General Settings', 'grocery-shop-grocerybuddy' ).'</h2>
                    </a>
                    </li>';

        $content .='<li class="tabs-title '.esc_attr($General_settingstwo).'" role="presentation">
                    <a href="#panel2" role="tab" aria-controls="panel2" aria-selected="false" id="panel2-label">
                        <h2>'.esc_html__( 'Options', 'grocery-shop-grocerybuddy' ).'</h2>
                    </a>
                    </li>';

            $content .='<li class="tabs-title '.esc_attr($General_settingsthree).'" role="presentation">
                        <a href="#panel3" role="tab" aria-controls="panel3" aria-selected="false" id="panel3-label">
                            <h2>'.esc_html__( 'Styling', 'grocery-shop-grocerybuddy' ).'</h2>
                        </a>
                        </li>';

            $content .='<li class="tabs-title '.esc_attr($advanced_settings_four).'" role="presentation">
                        <a href="#panel4" role="tab" aria-controls="panel4" aria-selected="false" id="panel4-label">
                            <h2>'.esc_html__( 'Advanced', 'grocery-shop-grocerybuddy' ).'</h2>
                        </a>
                        </li>';                        

        $content .= apply_filters( 'wcgb_settings_tab_menu_item', '' );

        $content .='<li class="thespacer"><hr></li>';
        $content .='<li class="external-title"><a href="https://www.webfulcreations.com/contact-us/" target="_blank"><h2><span class="dashicons dashicons-buddicons-pm"></span> '.esc_html__( 'Contact Us', 'grocery-shop-grocerybuddy' ).'</h2></a></li>';
        $content .='<li class="external-title"><a href="https://www.facebook.com/WebfulCreations" target="_blank"><h2><span class="dashicons dashicons-facebook"></span> '.esc_html__( 'Chat With Us', 'grocery-shop-grocerybuddy' ).'</h2></a></li>';
        $content .='</ul>';
        $content .='</div> <!-- Sidebar Menu /-->';

        $content .='<div class="cell medium-9 thewhitebg contentsideb">';
        $content .='<div class="tabs-content vertical" data-tabs-content="example-tabs">';
        $content .='<div class="tabs-panel team-wrap '.esc_attr($General_settings).'" id="panel1" role="tabpanel" aria-labelledby="panel1-label">';
        $content .= wgby_settings_page();
        $content .='</div> <!-- tabs-panel /-->';

        $content .='<div class="tabs-panel team-wrap '.esc_attr($General_settingstwo).'" id="panel2" role="tabpanel" aria-labelledby="panel2-label">';
        $content .= wgby_settings_page_options();
        $content .='</div> <!-- tabs-panel /-->';

        $content .='<div class="tabs-panel team-wrap '.esc_attr($General_settingsthree).'" id="panel3" role="tabpanel" aria-labelledby="panel3-label">';
        $content .= wgby_settings_page_style();
        $content .='</div> <!-- tabs-panel /-->';

        $content .='<div class="tabs-panel team-wrap '.esc_attr($advanced_settings_four).'" id="panel4" role="tabpanel" aria-labelledby="panel4-label">';
        $content .= wgby_settings_advanced_settings();
        $content .='</div> <!-- tabs-panel /-->';
        
        $content .= apply_filters( 'wcgb_settings_tab_menu_content', '' );

        $content .='</div><!-- tabs content ends -->';
        $content .='</div>';
        $content .='</div><!-- Team Wrap /-->';
        $content .='</div><!-- Columns /-->';
        $content .='</div><!-- Grid Container /-->';
        $content .='</div><!-- Main Container /-->';

        // echo $content ;

        $allowedHTML = ( function_exists( 'wgby_return_allowed_tags' ) ) ? wgby_return_allowed_tags() : '';
        echo wp_kses( $content, $allowedHTML );	
    }
endif;