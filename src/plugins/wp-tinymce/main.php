<?php
/*
 * Plugin Name: TinyMCE
 * Description: Ensures TinyMCE is loaded on admin pages
 * Author: Maarten Kools
 * Version: {wp-major}.{wp-minor}.{build}.{revision}
 * Text Domain: wp-tinymce
 * License: The MIT License
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Maarten Kools
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

function wp_tinymce_autoload($class)
{
	static $classes = null;

	if ($classes === null) {
		$pluginDir = plugin_dir_path(__FILE__);

		$classes = array(
			// General
			'wp_tinymce\Plugin' => $pluginDir . 'inc/class-plugin.php',

			'PucFactory' => $pluginDir . 'updater/plugin-update-checker.php'
		);
	}

	if (isset($classes[$class])) {
		require_once($classes[$class]);
	}
}

if (function_exists('spl_autoload_register')) {
	spl_autoload_register('wp_tinymce_autoload');
}

$GLOBALS['wp_tinymce_plugin'] = new wp_tinymce\Plugin(__FILE__);
