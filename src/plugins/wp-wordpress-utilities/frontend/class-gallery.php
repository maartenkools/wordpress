<?php

/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 */
class WPU_Gallery {
	private static $cssClass = 'wpu-gallery';

	public static function render( $params ) {
		$options = WPU_Plugin::current()->get_options();

		$params = shortcode_atts( array(
			'tag'        => 'div',
			'cssClass'   => '',
			'randomize'  => false,
			'max_width'  => $options->getValue( 'gallery', 'max_width' ),
			'max_height' => $options->getValue( 'gallery', 'max_height' )
		), $params );

		$post = ( isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null );

		$dom  = new DOMDocument();
		$root = $dom->createElement( $params['tag'] );
		$root->setAttribute( 'style', 'max-width: ' . $params['max_width'] . 'px; max-height: ' . $params['max_height'] . 'px;' );
		$dom->appendChild( $root );

		$count = self::__renderChildren( $post, $dom, $params );

		$classes = explode( ' ', $params['cssClass'] );
		array_push( $classes, self::$cssClass );
		if ( $count == 0 ) {
			array_push( $classes, 'hidden' );
		}

		$root->setAttribute( 'class', implode( ' ', $classes ) );

		return $dom->saveHTML();
	}

	private static function __renderChildren( $post, DOMDocument $dom, $params ) {
		if ( ! self::__isPostSupported( $post ) ) {
			return 0;
		}

		$root = $dom->childNodes->item( 0 );

		$maxDimensions = array(
			$params['max_width'],
			$params['max_height']
		);

		$images = self::__queryImages( $post->ID, $params['randomize'] );
		foreach ( $images as $image ) {
			$attachmentId = null;
			$caption      = null;

			if ( $image instanceof stdClass ) {
				$attachmentId = $image->id;
				$caption      = strip_tags( $image->fields->caption );
			} else {
				$attachmentId = $image->ID;
				$caption      = $image->post_excerpt;
			}

			$alt = get_post_meta( $attachmentId, '_wp_attachment_image_alt', true );

			$div = $root->appendChild( $dom->createElement( 'div' ) );

			$a = $dom->createElement( 'a' );
			$div->appendChild( $a );
			$a->setAttribute( 'data-lightbox', $post->ID );
			if ( ! empty( $caption ) ) {
				$a->setAttribute( 'data-title', $caption );
			}
			$a->setAttribute( 'href', wp_get_attachment_url( $attachmentId ) );

			$src = wp_get_attachment_image_src( $attachmentId, $maxDimensions );

			$dimensions = self::__resizeImage( array( $src[1], $src[2] ), $maxDimensions );

			$img = $dom->createElement( 'img' );
			$a->appendChild( $img );
			$img->setAttribute( 'src', $src[0] );
			$img->setAttribute( 'width', $dimensions[0] );
			$img->setAttribute( 'height', $dimensions[1] );
			$img->setAttribute( 'class', 'img-responsive' );
			if ( ! empty( $alt ) ) {
				$img->setAttribute( 'alt', $alt );
			}
		}

		return count( $images );
	}

	private static function __queryImages( $randomize ) {
		$images = array();

		// If the Attachments plugin is enabled, use that to retrieve the images.
		if ( class_exists( 'Attachments' ) ) {
			$attachments = new Attachments( 'attachments' );

			while ( $attachment = $attachments->get() ) {
				$images[] = $attachment;
			}

			if ( count( $images ) != 0 ) {
				if ( $randomize ) {
					shuffle( $images );
				} // Randomize the order
				return $images;
			}
		}

		return $images;
	}

	private static function __resizeImage( $dimensions, $maxDimensions ) {
		$width  = $dimensions[0];
		$height = $dimensions[1];

		if ( $width > $maxDimensions[0] ) {
			$ratio = $maxDimensions[0] / $width;

			$width  = $width * $ratio;
			$height = $height * $ratio;
		}

		if ( $height > $maxDimensions[1] ) {
			$ratio = $maxDimensions[1] / $height;

			$width  = $width * $ratio;
			$height = $height * $ratio;
		}

		return array( $width, $height );
	}

	private static function __isPostSupported( $post ) {
		if ( ! isset( $post ) ) {
			return false;
		}
		if ( ! isset( $post->ID ) ) {
			return false;
		}
		if ( ! is_single( $post->ID ) ) {
			return false;
		}
		if ( get_post_type( $post->ID ) != 'post' ) {
			return false;
		}

		return true;
	}
}
