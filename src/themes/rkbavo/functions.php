<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 11/23/2014
 * Time: 3:38 PM
 */

// Set up autoloading
spl_autoload_register( function ( $className ) {
	$stylesheet_directory = get_stylesheet_directory();

	$classes = array(
		'ThemeUpdateChecker'  => $stylesheet_directory . '/updater/theme-update-checker.php',
		'ThemeUpdate'         => $stylesheet_directory . '/updater/theme-update-checker.php',
		'Vc_Grid_Item_Editor' => vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/editor/class-vc-grid-item-editor.php' )
	);

	if ( isset( $classes[ $className ] ) ) {
		require_once( $classes[ $className ] );

		return;
	}

	// Split by namespace
	$components      = explode( '\\', $className );
	$component_count = count( $components );

	$namespacePath = $component_count > 1
		? implode( '/', array_slice( $components, 0, count( $components ) - 1 ) )
		: '';

	$className = end( $components );

	$classPath = path_join( $stylesheet_directory . '/includes', $namespacePath ) . '/class-' . strtolower( $className ) . '.php';

	if ( file_exists( $classPath ) ) {
		include( $classPath );
	}
} );

$theme = new rkbavo\Theme();
$theme->run();

