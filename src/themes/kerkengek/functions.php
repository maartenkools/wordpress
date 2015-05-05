<?php

define('KG_THEME_VERSION', '{wp-major}.{wp-minor}.{build}.{revision}');

function kerkengek_enqueue_scripts()
{
    wp_dequeue_style('bushwick-style');
    wp_deregister_style('bushwick-style');

    wp_enqueue_style('bushwick-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('kerkengek-style', get_stylesheet_uri(), array('bushwick-style'), KG_THEME_VERSION);

    wp_enqueue_script('kerkengek-functions', get_stylesheet_directory_uri() . '/js/functions.js', array('jquery'), KG_THEME_VERSION);
}

add_action('wp_enqueue_scripts', 'kerkengek_enqueue_scripts', PHP_INT_MAX);

require 'updater/theme-update-checker.php';
$example_update_checker = new ThemeUpdateChecker(
	'kerkengek',
	'https://raw.githubusercontent.com/maartenkools/wordpress/master/updater/kerkengek.json'
);

/**
 * Implement the Custom Header feature.
 */
require get_stylesheet_directory() . '/inc/custom-header.php';
