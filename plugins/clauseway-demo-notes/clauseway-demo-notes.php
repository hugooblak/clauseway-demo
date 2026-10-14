<?php
/**
 * Plugin Name: Clauseway Demo Notes
 * Description: Portfolio helper. Shows a "this is a demo" bar and, when switched on, a note above each section explaining the design decision behind it. Delete this plugin on a real site.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Author: Hugo Oblak
 * License: GPL-2.0-or-later
 *
 * The notes are not stored in page content, so editors never see them and they vanish with the plugin.
 *
 * @package ClausewayDemoNotes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Notes on or off. ?notes=1 turns them on (and remembers it in a cookie while you browse), ?notes=0 turns them off.
 */
function npd_notes_on() {
	static $on = null;
	if ( null === $on ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['notes'] ) ) {
			$on = '1' === $_GET['notes'];
		} else {
			$on = isset( $_COOKIE['npd_notes'] ) && '1' === $_COOKIE['npd_notes'];
		}
		// phpcs:enable
	}
	return $on;
}

add_action(
	'send_headers',
	function () {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['notes'] ) && ! headers_sent() ) {
			setcookie( 'npd_notes', npd_notes_on() ? '1' : '0', 0, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
		}
	}
);

/**
 * The notes, keyed by the class or block name they sit above.
 *
 * @return array
 */
function npd_notes() {
	return array(
		'hero'  => array(
			'The product in one look',
			'The headline says what it does in three short lines, and the contract on the right shows it: a beam reads down the page, clauses are flagged as it passes and comment cards attach on drawn lines. It loops every ten seconds. The clauses and cards are edited in the block sidebar.',
		),
		'pb'    => array(
			'Rules, not a black box',
			'Buyers want to know the AI follows their standards. Each rule draws a line to the clause it matched and says whether it matches or is off playbook. Rules and clauses are edited in the block sidebar.',
		),
		'final' => array(
			'One ask',
			'A single email field and the one filled button on the page. In this demo nothing is sent and the form says so. On a real site the block connects to a form plugin or the sales calendar.',
		),
	);
}

/**
 * Put a note in front of each matching section when notes are on.
 */
add_filter(
	'render_block',
	function ( $html, $block ) {
		if ( is_admin() || ! npd_notes_on() ) {
			return $html;
		}
		$classes = preg_split( '/\s+/', (string) ( $block['attrs']['className'] ?? '' ) );
		foreach ( npd_notes() as $class => $note ) {
			if ( in_array( $class, $classes, true ) && 'core/group' === $block['blockName'] ) {
				return sprintf(
					'<aside class="npd-note" aria-label="%s"><p class="npd-note-title"><span>Design note</span> %s</p><p>%s</p></aside>',
					esc_attr( 'Design note: ' . $note[0] ),
					esc_html( $note[0] ),
					esc_html( $note[1] )
				) . $html;
			}
		}
		return $html;
	},
	10,
	2
);

/**
 * Demo bar at the very top of every page.
 *
 * It sits above the header, which would otherwise put its link ahead of WordPress's skip link in the
 * tab order. So we print our own skip link first and hide core's.
 */
add_action(
	'wp_body_open',
	function () {
		printf(
			'<a class="npd-skip skip-link screen-reader-text" href="#wp--skip-link--target">%s</a>',
			esc_html__( 'Skip to content', 'clauseway-demo-notes' )
		);
	},
	0
);

add_action(
	'wp_body_open',
	function () {
		$toggle = add_query_arg( 'notes', npd_notes_on() ? '0' : '1' );
		?>
		<div class="npd-bar" role="region" aria-label="Demo notice">
			<p><strong>Portfolio demo.</strong> Clauseway is a fictional company. The name, logos, contracts and figures are sample content, and nothing here is sent.
				<a href="<?php echo esc_url( $toggle ); ?>"><?php echo npd_notes_on() ? 'Hide the design notes' : 'Show the design notes behind each section'; ?></a>
			</p>
		</div>
		<?php
	},
	1
);

add_action(
	'wp_head',
	function () {
		?>
		<style id="npd-css">
			.npd-bar ~ a.skip-link:not(.npd-skip){display:none !important}
			.npd-bar{background:#000;color:#EEE9E2;font-size:.8125rem;line-height:1.5;padding:.55rem 1rem;text-align:center;position:relative;z-index:50}
			.npd-bar p{margin:0}.npd-bar a{color:#D9BE9B;font-weight:500;margin-left:.35rem}
			.npd-note{box-sizing:border-box;margin:0;padding:.9rem clamp(1rem,7.9%,10rem);background:#FFF8DB;border-top:2px dashed #C99A06;border-bottom:2px dashed #C99A06;color:#3D2E00;font-size:.9375rem;line-height:1.55}
			.npd-note p{margin:0;max-width:90ch}.npd-note-title{font-weight:700;margin-bottom:.25rem !important}
			.npd-note-title span{display:inline-block;background:#3D2E00;color:#FFF8DB;font-size:.6875rem;letter-spacing:.06em;text-transform:uppercase;padding:.1rem .4rem;margin-right:.35rem;vertical-align:.1em}
		</style>
		<?php
	}
);

/**
 * A short guide on the Dashboard for anyone reviewing the demo in WordPress Playground.
 */
add_action(
	'wp_dashboard_setup',
	function () {
		wp_add_dashboard_widget(
			'npd_guide',
			'Clauseway demo: where to look',
			function () {
				$items = array(
					array( home_url( '/?notes=1' ), 'Home page with the design notes switched on' ),
					array( home_url( '/#cta' ), 'Demo form (sends nothing)' ),
					array( admin_url( 'post.php?post=' . get_option( 'page_on_front' ) . '&action=edit' ), 'Edit the home page: normal blocks; contract text, comments, steps and rules are edited in the sidebar of each block' ),
					array( admin_url( 'site-editor.php' ), 'Site editor: styles, header, footer, templates' ),
				);
				echo '<ol>';
				foreach ( array_filter( $items ) as $item ) {
					printf( '<li><a href="%s">%s</a></li>', esc_url( $item[0] ), esc_html( $item[1] ) );
				}
				echo '</ol>';
			}
		);
	}
);
