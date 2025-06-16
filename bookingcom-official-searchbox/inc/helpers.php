<?php
/**
 * Helpers.
 *
 * @since 2.2.4
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

//////////////////////////
// include helpers libs //
//////////////////////////

// require_once BOS_PLUGIN_HELPER_DIR . '/logo.php';
require_once BOS_PLUGIN_HELPER_DIR . '/searchbox-fields.php';
require_once BOS_PLUGIN_HELPER_DIR . '/searchbox.php';
require_once BOS_PLUGIN_HELPER_DIR . '/date_array.php';
require_once BOS_PLUGIN_HELPER_DIR . '/meta_boxes.php';

if ( ! function_exists( 'bos_register_style' ) ) {

	/**
	 * Simple wrap for wp_register_style.
	 *
	 * @since 2.2.4
	 *
	 * @param string      $handle
	 * @param string      $src
	 * @param array       $deps
	 * @param bool|string $ver
	 * @param string      $media
	 */
	function bos_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {

		wp_register_style( $handle, $src, $deps, $ver, $media );
	}

}

if ( ! function_exists( 'bos_register_script' ) ) {

	/**
	 * Simple wrap for wp_register_script.
	 *
	 * @since 2.2.4
	 *
	 * @param string      $handle
	 * @param string      $src
	 * @param array       $deps
	 * @param bool|string $ver
	 * @param bool        $in_footer
	 */
	function bos_register_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {

		wp_register_script( $handle, $src, $deps, $ver, $in_footer );
	}
}

if ( ! function_exists( 'bos_register_script_in_footer' ) ) {

	/**
	 * Simple wrap for wp_register_script.
	 *
	 * @since 2.2.4
	 *
	 * @param string      $handle
	 * @param string      $src
	 * @param array       $deps
	 * @param bool|string $ver
	 * @param bool        $in_footer
	 */
	function bos_register_script_in_footer( $handle, $src, $deps = array(), $ver = false ) {
		bos_register_script( $handle, $src, $deps, $ver, true );
	}
}

if ( ! function_exists( 'wc_hex_is_light' )) {
	function wc_hex_is_light( $color ) {
		$hex = str_replace( '#', '', $color ?? '' );

		$c_r = hexdec( substr( $hex, 0, 2 ) );
		$c_g = hexdec( substr( $hex, 2, 2 ) );
		$c_b = hexdec( substr( $hex, 4, 2 ) );

		$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

		return $brightness > 155;
	}
}