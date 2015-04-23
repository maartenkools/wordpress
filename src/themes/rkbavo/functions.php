<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 11/23/2014
 * Time: 3:38 PM
 */

define('RKBAVO_THEME_VERSION', '{wp-major}.{wp-minor}.{build}.{revision}');

function wp_rkbavo_enqueue_styles()
{
    if (is_child_theme()) {
        wp_enqueue_style('main_style', get_template_directory_uri() . '/style.css');
    }

    wp_enqueue_style('rkbavo_style', get_stylesheet_uri(), array(), RKBAVO_THEME_VERSION);
}

add_action('wp_enqueue_scripts', 'wp_rkbavo_enqueue_styles', 20);
