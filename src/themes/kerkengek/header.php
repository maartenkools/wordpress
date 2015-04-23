<?php
/**
 * The Header for our theme.
 *
 * @package Bushwick
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width"/>
    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <link rel="icon" type="image/x-icon" href="<?= get_stylesheet_directory_uri() . '/img/favicon.ico' ?>"/>
    <link rel="icon" type="image/png" href="<?= get_stylesheet_directory_uri() . '/img/favicon.png' ?>"/>
    <link rel="apple-touch-icon-precomposed" href="<?= get_stylesheet_directory_uri() . '/img/favicon.png' ?>" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
get_template_part('sidebar', is_single() ? 'single' : 'index');
get_sidebar();
