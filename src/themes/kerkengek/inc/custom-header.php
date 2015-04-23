<?php

function kerkengek_custom_header_setup()
{
    add_theme_support('custom-header', apply_filters('bushwick_custom_header_args', array(
        'default-image' => get_stylesheet_directory_uri() . '/img/header.jpg',
        'default-text-color' => 'fff',
        'width' => 900,
        'height' => 1600,
        'flex-height' => true,
        'flex-width' => true,
        'wp-head-callback' => 'bushwick_header_style',
        'admin-head-callback' => 'bushwick_admin_header_style',
        'admin-preview-callback' => 'bushwick_admin_header_image'
    )));
    
    add_action('admin_print_styles-appearance_page_custom-header', 'bushwick_fonts');
}

remove_action('after_setup_theme', 'bushwick_custom_header_setup');
add_action('after_setup_theme', 'kerkengek_custom_header_setup');