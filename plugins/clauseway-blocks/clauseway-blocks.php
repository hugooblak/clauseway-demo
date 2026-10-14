<?php
/**
 * Plugin Name: Clauseway Blocks
 * Description: The moving parts of the Clauseway site: the hero scan, the product tabs, the scroll-driven how-it-works and security sections, the playbook and the demo-only form. Text is edited in each block's sidebar.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author: Hugo Oblak
 * License: GPL-2.0-or-later
 * Text Domain: clauseway-blocks
 *
 * Why a plugin and not theme code: the contract text, comments, steps and rules are content the
 * team edits. They must survive a redesign.
 *
 * @package ClausewayBlocks
 */

defined( 'ABSPATH' ) || exit;

define( 'CWB_URL', plugin_dir_url( __FILE__ ) );
define( 'CWB_VER', '1.0.0' );

add_action(
	'init',
	function () {
		foreach ( array( 'hero-scan', 'product-tabs', 'how-scroll', 'playbook', 'vault', 'cta-form' ) as $block ) {
			register_block_type( __DIR__ . '/blocks/' . $block );
		}
	}
);

// One stylesheet for every block, in the editor and on the site.
add_action(
	'enqueue_block_assets',
	function () {
		wp_enqueue_style( 'clauseway-blocks', CWB_URL . 'blocks.css', array(), CWB_VER );
	}
);

// One script for the whole page, front end only. It runs a single animation loop.
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_script( 'clauseway-blocks', CWB_URL . 'blocks.js', array(), CWB_VER, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
);

add_action(
	'enqueue_block_editor_assets',
	function () {
		wp_enqueue_script(
			'clauseway-blocks-editor',
			CWB_URL . 'editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
			CWB_VER,
			true
		);
	}
);

/**
 * Split a textarea into trimmed, non-empty lines.
 *
 * @param string $text Text from a block attribute.
 * @return string[]
 */
function cwb_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', explode( "\n", (string) $text ) ), 'strlen' ) );
}

/**
 * Split a line "a | b | c" into at least $n trimmed cells.
 *
 * @param string $line Line.
 * @param int    $n    Minimum number of cells.
 * @return string[]
 */
function cwb_cells( $line, $n = 3 ) {
	return array_pad( array_map( 'trim', explode( '|', (string) $line ) ), $n, '' );
}

/**
 * Escape text and turn [[word]] into <mark> (or <span class="del"> when $kind is "del").
 *
 * @param string $text Text with optional [[marks]].
 * @param string $kind 'mark' (compare view) or 'del' (struck-out words).
 * @return string HTML.
 */
function cwb_marked( $text, $kind = 'mark' ) {
	$parts = preg_split( '/\[\[(.*?)\]\]/', (string) $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	$out   = '';
	$c     = 0;
	foreach ( $parts as $i => $part ) {
		if ( 0 === $i % 2 ) {
			$out .= esc_html( $part );
		} elseif ( 'del' === $kind ) {
			$out .= '<span class="del">' . esc_html( $part ) . '</span>';
		} else {
			$out .= '<mark class="chg" style="--c:' . (int) $c++ . '">' . esc_html( $part ) . '</mark>';
		}
	}
	return $out;
}

/**
 * Small line icon, same set as the static site.
 *
 * @param string $name doc, pen, cmp, chk, flag, lock, eye, key, trash, arrow.
 * @return string SVG markup.
 */
function cwb_icon( $name ) {
	$paths = array(
		'doc'   => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>',
		'pen'   => '<path d="M4 20l4-1 11-11a2.1 2.1 0 0 0-3-3L5 16z"/><path d="M14 7l3 3"/>',
		'cmp'   => '<rect x="3" y="4" width="8" height="16" rx="2"/><rect x="13" y="4" width="8" height="16" rx="2"/>',
		'chk'   => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16 9.5"/>',
		'flag'  => '<path d="M5 21V4M5 4h11l-2 4 2 4H5"/>',
		'lock'  => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
		'eye'   => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
		'key'   => '<circle cx="8" cy="15" r="4"/><path d="M11 12l9-9M16 7l3 3"/>',
		'trash' => '<path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/>',
		'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	);
	return '<svg class="i" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . ( $paths[ $name ] ?? '' ) . '</svg>';
}
