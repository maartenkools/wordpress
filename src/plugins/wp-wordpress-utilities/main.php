<?php
/*
 * Plugin Name: WordPress Utilities
 * Description: This plugin provides a collection of shortcodes and utilities for WordPress websites.
 * Author: Maarten Kools
 * Version: {wp-major}.{wp-minor}.{build}.{revision}
 * Text Domain: wp-wordpress-utilities
 * Domain Path: /languages/
 * License: The MIT License
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Maarten Kools
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
if (!function_exists('add_filter')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

function wpu_autoload($class)
{
    static $classes = null;

    if ($classes === null) {
        $pluginDir = plugin_dir_path(__FILE__);

        $classes = array(
            // General
            'WPU_Plugin' => $pluginDir . 'inc/class-plugin.php',
            'WPU_Options' => $pluginDir . 'inc/class-options.php',
            'WPU_String' => $pluginDir . 'inc/class-string.php',
            'WPU_DataType' => $pluginDir . 'inc/enum-datatype.php',

            // Frontend
            'WPU_Gallery' => $pluginDir . 'frontend/class-gallery.php',
            'WPU_SinglePost' => $pluginDir . 'frontend/class-single-post.php',
            'WPU_Icon' => $pluginDir . 'frontend/class-icon.php',

            // Admin
            'WPU_Admin' => $pluginDir . 'admin/class-admin.php',
            'WPU_TextRenderer' => $pluginDir . 'admin/renderers/class-text-renderer.php',
            'WPU_NumericRenderer' => $pluginDir . 'admin/renderers/class-numeric-renderer.php',
            'WPU_CheckboxRenderer' => $pluginDir . 'admin/renderers/class-checkbox-renderer.php',

            // Widgets
            'WPU_RecentPostsWidget' => $pluginDir . 'widgets/class-recent-posts-widget.php',
            'WPU_CategoriesWidget' => $pluginDir . 'widgets/class-categories-widget.php',

            'PucFactory' => $pluginDir . 'updater/plugin-update-checker.php'
        );
    }

    if (isset($classes[$class])) {
        require_once($classes[$class]);
    }
}

if (function_exists('spl_autoload_register')) {
    spl_autoload_register('wpu_autoload');
}

$GLOBALS['wpu_plugin'] = new WPU_Plugin(__FILE__);
