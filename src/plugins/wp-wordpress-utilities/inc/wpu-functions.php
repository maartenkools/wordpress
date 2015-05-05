<?php
add_filter( 'getarchives_where', 'wpu_getarchives_where' );
add_filter( 'getarchives_join', 'wpu_getarchives_join' );

function wpu_getarchives_join( $x ) {
	global $wpdb;

	return $x . " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
}

function wpu_getarchives_where( $x ) {
	global $wpdb;

	$values = explode( ',', WPU_Plugin::current()->get_options()->getValue( 'archives', 'excluded_categories' ) );

	$ids = array();
	foreach ( $values as $value ) {
		$ids[] = intval( $value );
	}

	$exclude = implode( ',', $ids ); // category id to exclude
	if ( ! empty( $exclude ) ) {
		return $x . " AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.term_id NOT IN ($exclude)";
	}

	return $x;
}

if ( ! function_exists( 'wpu_gallery' ) ) {
	function wpu_gallery( $tag = 'div', $cssClass = '', $randomize = false, $max_width = - 1, $max_height = - 1, $autoplay_speed = -1 ) {
		$params = array(
			'tag'       => $tag,
			'cssClass'  => $cssClass,
			'randomize' => $randomize
		);

		if ( $max_width > 0 ) {
			$params['max_width'] = $max_width;
		}
		if ( $max_height > 0 ) {
			$params['max_height'] = $max_height;
		}
		if ( $autoplay_speed >= 0 ) {
			$params['autoplay_speed'] = $autoplay_speed;
		}

		echo WPU_Gallery::render( $params );
	}
}
