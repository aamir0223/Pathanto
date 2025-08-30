<?php

require get_stylesheet_directory() . '/customizer/options-services.php';
require get_stylesheet_directory() . '/customizer/options-top-posts.php';
require get_stylesheet_directory() . '/sections/services.php';
require get_stylesheet_directory() . '/sections/top-posts.php';

add_action( 'wp_enqueue_scripts', 'education_empowerment_chld_thm_parent_css' );
function education_empowerment_chld_thm_parent_css() {

    wp_enqueue_style( 
    	'education_empowerment_chld_css', 
    	trailingslashit( get_template_directory_uri() ) . 'style.css', 
    	array( 
    		'bootstrap',
    		'font-awesome-5',
    		'bizberg-main',
    		'bizberg-component',
    		'bizberg-style2',
    		'bizberg-responsive' 
    	) 
    );

    if ( is_rtl() ) {
        wp_enqueue_style( 
            'education_empowerment_parent_rtl', 
            trailingslashit( get_template_directory_uri() ) . 'rtl.css'
        );
    }
    
}

add_action( 'after_setup_theme', 'education_empowerment_setup_theme' );
function education_empowerment_setup_theme() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
}

add_filter( 'bizberg_sidebar_settings', 'education_empowerment_sidebar_settings' );
function education_empowerment_sidebar_settings(){
    return '1';
}

add_filter( 'bizberg_footer_social_links' , 'education_empowerment_footer_social_links' );
function education_empowerment_footer_social_links(){
    return [];
}

add_filter( 'bizberg_link_color', 'education_empowerment_link_color' );
function education_empowerment_link_color(){
    return '#64686d';
}

add_filter( 'bizberg_theme_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_header_menu_color_hover_sticky_menu', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_header_button_color_sticky_menu', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_header_button_color_hover_sticky_menu', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_header_menu_color_hover', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_header_button_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_header_button_color_hover', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_slider_title_box_highlight_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_slider_arrow_background_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_slider_dot_active_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_read_more_background_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_read_more_background_color_2', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_link_color_hover', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_blog_listing_pagination_active_hover_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_sidebar_widget_link_color_hover', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_sidebar_widget_title_color', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_footer_social_icon_background', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_background_color_1', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_background_color_2', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_transparent_header_menu_color_hover', 'education_empowerment_change_theme_color' );
add_filter( 'bizberg_transparent_header_sticky_menu_color_hover', 'education_empowerment_change_theme_color' );
function education_empowerment_change_theme_color(){
    return '#ffb606';
}

add_filter( 'bizberg_three_col_listing_radius', 'education_empowerment_three_col_listing_radius' );
function education_empowerment_three_col_listing_radius(){
    return '0';
}

add_filter( 'bizberg_transparent_header_homepage', 'education_empowerment_transparent_header_homepage' );
function education_empowerment_transparent_header_homepage(){
    return false;
}

add_filter( 'bizberg_transparent_navbar_background', 'education_empowerment_transparent_navbar_background' );
function education_empowerment_transparent_navbar_background(){
    return 'rgba(10,10,10,0)';
}

add_filter( 'bizberg_header_blur', 'education_empowerment_header_blur' );
function education_empowerment_header_blur(){
    return 0;
}

add_filter( 'bizberg_transparent_header_menu_sticky_background', 'education_empowerment_transparent_header_menu_sticky_background' );
add_filter( 'bizberg_transparent_header_menu_toggle_color_mobile', 'education_empowerment_transparent_header_menu_sticky_background' );
function education_empowerment_transparent_header_menu_sticky_background(){
    return '#fff';
}

add_filter( 'bizberg_transparent_header_menu_sticky_text_color', 'education_empowerment_transparent_header_menu_sticky_text_color' );
function education_empowerment_transparent_header_menu_sticky_text_color(){
    return '#64686d';
}

add_filter( 'bizberg_banner_spacing', 'education_empowerment_banner_spacing' );
function education_empowerment_banner_spacing(){
    return [
        'padding-top'    => '60px',
        'padding-bottom' => '60px',
        'padding-left'   => '150px',
        'padding-right'  => '150px',
    ];
}

add_filter( 'bizberg_banner_image', 'education_empowerment_banner_image' );
function education_empowerment_banner_image(){
    return [
        'background-color'      => 'rgba(20,20,20,.8)',
        'background-image'      => get_stylesheet_directory_uri() . '/img/book-read-interior-reading-shelf-tv-710499-pxhere.jpg',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed'
    ];
}

add_filter( 'bizberg_banner_title', 'education_empowerment_banner_title' );
function education_empowerment_banner_title(){
    return current_user_can( 'edit_theme_options' ) ? esc_html__( "Empowering Minds, Igniting Futures", 'education-empowerment' ) : '';
}

add_filter( 'bizberg_banner_subtitle', 'education_empowerment_banner_subtitle' );
function education_empowerment_banner_subtitle(){
    return current_user_can( 'edit_theme_options' ) ? esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form by injected humour.', 'education-empowerment' ) : '';
}

add_filter( 'bizberg_banner_title_font_status' , 'education_empowerment_banner_title_font_status' );
function education_empowerment_banner_title_font_status(){
    return true;
}

add_filter( 'bizberg_banner_title_font_desktop' , 'education_empowerment_banner_title_font_desktop' );
function education_empowerment_banner_title_font_desktop(){
    return [
        'font-family'    => 'Lato',
        'variant'        => '500',
        'font-size'      => '50px',
        'line-height'    => '1.1',
        'letter-spacing' => '0',
        'text-transform' => 'none'
    ];
}

add_filter( 'bizberg_banner_title_font_tablet' , 'education_empowerment_banner_title_font_tablet' );
function education_empowerment_banner_title_font_tablet(){
    return [
        'font-size'      => '70px',
        'line-height'    => '1',
        'letter-spacing' => '0'
    ];
}

add_filter( 'bizberg_banner_title_font_mobile' , 'education_empowerment_banner_title_font_mobile' );
function education_empowerment_banner_title_font_mobile(){
    return [
        'font-size'      => '55px',
        'line-height'    => '1',
        'letter-spacing' => '0'
    ];
}

add_filter( 'bizberg_banner_subtitle_font_status' , 'education_empowerment_banner_subtitle_font_status' );
function education_empowerment_banner_subtitle_font_status(){
    return true;
}

add_filter( 'bizberg_banner_subtitle_font_settings_desktop' , 'education_empowerment_banner_subtitle_font_settings_desktop' );
function education_empowerment_banner_subtitle_font_settings_desktop(){
    return [
        'font-family'    => 'Poppins',
        'variant'        => 'regular',
        'font-size'      => '16px',
        'line-height'    => '1.6',
        'letter-spacing' => '0',
        'text-transform' => 'none'
    ];
}

add_filter( 'bizberg_transparent_header_sticky_menu_toggle_color_mobile' , 'education_empowerment_transparent_header_sticky_menu_toggle_color_mobile' );
function education_empowerment_transparent_header_sticky_menu_toggle_color_mobile(){
    return '#434343';
}

add_filter( 'bizberg_site_title_font', 'education_empowerment_site_title_font' );
function education_empowerment_site_title_font(){
    return [
        'font-family'    => 'Montserrat',
        'variant'        => '600',
        'font-size'      => '23px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'uppercase',
        'text-align'     => 'left',
    ];
}

add_filter( 'bizberg_site_tagline_font', 'education_empowerment_site_tagline_font' );
function education_empowerment_site_tagline_font(){
    return [
        'font-family'    => 'Montserrat',
        'variant'        => '300',
        'font-size'      => '13px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
        'text-align'     => 'left',
    ];
}

add_filter( 'bizberg_sidebar_spacing_status', 'education_empowerment_sidebar_spacing_status' );
function education_empowerment_sidebar_spacing_status(){
    return '0px';
}

add_filter( 'bizberg_sidebar_widget_border_color', 'education_empowerment_sidebar_widget_background_color' );
add_filter( 'bizberg_sidebar_widget_background_color', 'education_empowerment_sidebar_widget_background_color' );
function education_empowerment_sidebar_widget_background_color(){
    return 'rgba(251,251,251,0)';
}

add_filter( 'bizberg_sticky_header_status', 'education_empowerment_sticky_header_status' );
function education_empowerment_sticky_header_status(){
    return 'false';
}

add_filter( 'bizberg_sticky_sidebar_margin_top_status', 'education_empowerment_sticky_sidebar_margin_top_status' );
function education_empowerment_sticky_sidebar_margin_top_status(){
    return 20;
}

add_filter( 'bizberg_banner_text_position' , 'education_empowerment_banner_text_position' );
function education_empowerment_banner_text_position(){
    return 'center';
}

add_filter( 'bizberg_sticky_content_sidebar' , 'education_empowerment_sticky_content_sidebar' );
function education_empowerment_sticky_content_sidebar(){
    return false;
}

add_filter( 'bizberg_hide_homepage_page_title' , 'education_empowerment_hide_homepage_page_title' );
function education_empowerment_hide_homepage_page_title(){
    return 'none';
}

add_filter( 'bizberg_getting_started_screenshot', 'education_empowerment_getting_started_screenshot' );
function education_empowerment_getting_started_screenshot(){
    return true;
}

add_action( 'after_switch_theme', 'education_empowerment_switch_theme' );
function education_empowerment_switch_theme() {

    $flag = get_theme_mod( 'education_empowerment_copy_settings', false );

    if ( true === $flag ) {
        return;
    }

    foreach( Kirki::$fields as $field ) {
        set_theme_mod( $field["settings"],$field["default"] );
    }

    //Set flag
    set_theme_mod( 'education_empowerment_copy_settings', true );
    
}