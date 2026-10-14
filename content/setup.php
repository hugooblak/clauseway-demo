<?php
/**
 * Demo site setup for Clauseway.
 *
 * Creates the home page from the theme's patterns, sets it as the front page and builds the menu.
 * Safe to run again: it skips anything that already exists.
 *
 * Playground runs this automatically (see blueprint.json).
 * Local run:  wp eval-file content/setup.php
 *
 * @package Clauseway
 */

defined( 'ABSPATH' ) || exit;

wp_set_current_user( 1 ); // Admin, so block markup is saved exactly as written.

/**
 * Pattern markup with nested pattern references expanded, so saved pages hold plain editable blocks.
 *
 * @param string $slug Pattern slug.
 * @return string
 */
function nps_pattern( $slug ) {
	$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
	if ( ! $pattern ) {
		nps_log( "Missing pattern: $slug" );
		return '';
	}
	return preg_replace_callback(
		'#<!-- wp:pattern \{"slug":"([^"]+)"\} /-->#',
		function ( $m ) {
			return nps_pattern( $m[1] );
		},
		$pattern['content']
	);
}

/**
 * Print a line when run from WP-CLI. Silent in Playground.
 *
 * @param string $msg Message.
 */
function nps_log( $msg ) {
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::log( $msg );
	}
}

/**
 * Create a page unless one with this slug exists.
 *
 * @param string $slug     Page slug.
 * @param string $title    Title.
 * @param string $content  Block markup.
 * @param string $excerpt  Used as the meta description.
 * @param string $template Page template slug.
 * @return int Page ID.
 */
function nps_page( $slug, $title, $content, $excerpt, $template = '' ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return $existing->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => $content,
			'post_excerpt' => $excerpt,
			'meta_input'   => $template ? array( '_wp_page_template' => $template ) : array(),
		)
	);
	nps_log( "Page: $title" );
	return $id;
}

/* --------------------------------------------------------------------------
 * Site settings
 * ----------------------------------------------------------------------- */
update_option( 'blogname', 'Clauseway' );
update_option( 'blogdescription', 'Contract review that reads every clause. A concept site for a fictional company.' );
update_option( 'timezone_string', 'America/New_York' );
update_option( 'date_format', 'F j, Y' );
update_option( 'start_of_week', 0 );

// Remove WordPress's sample content.
foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $slug => $type ) {
	$sample = get_page_by_path( $slug, OBJECT, $type );
	if ( $sample ) {
		wp_delete_post( $sample->ID, true );
	}
}
$privacy_draft = get_page_by_path( 'privacy-policy' );
if ( $privacy_draft && 'draft' === $privacy_draft->post_status ) {
	wp_delete_post( $privacy_draft->ID, true );
}

/* --------------------------------------------------------------------------
 * Main menu (Appearance > Editor > Navigation). Used by the header.
 * Created before the home page, so the header pattern can point at it.
 * ----------------------------------------------------------------------- */
if ( ! get_posts( array( 'post_type' => 'wp_navigation', 'title' => 'Main menu', 'numberposts' => 1, 'post_status' => 'publish' ) ) ) {
	$nav   = '';
	$items = array(
		array( 'Product', '#product', '' ),
		array( 'How it works', '#how', '' ),
		array( 'Playbooks', '#playbooks', '' ),
		array( 'Security', '#security', '' ),
		array( 'FAQ', '#faq', '' ),
		// Shown only in the phone menu; on a wide screen the button next to the menu does this job.
		array( 'Book a demo', '#cta', 'nav-cta' ),
	);
	foreach ( $items as $item ) {
		$attrs = array(
			'label' => $item[0],
			'url'   => home_url( '/' . $item[1] ),
			'kind'  => 'custom',
		);
		if ( $item[2] ) {
			$attrs['className'] = $item[2];
		}
		$nav .= '<!-- wp:navigation-link ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES ) . ' /-->';
	}
	wp_insert_post(
		array(
			'post_type'    => 'wp_navigation',
			'post_status'  => 'publish',
			'post_title'   => 'Main menu',
			'post_content' => $nav,
		)
	);
	nps_log( 'Menu created.' );
}

/* --------------------------------------------------------------------------
 * Pages
 * ----------------------------------------------------------------------- */
$home = nps_page(
	'home',
	'Clauseway',
	get_page_by_path( 'home' ) ? '' : nps_pattern( 'clauseway/page-home' ),
	'Clauseway reads every clause, flags what matters and explains why, so your team can negotiate with confidence. A concept site for a fictional company.',
	'page-landing'
);

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );

// Pretty links. set_permalink_structure() also updates the rules WordPress already loaded for
// this request; only saving the option would rebuild the old rules.
global $wp_rewrite;
$wp_rewrite->set_permalink_structure( '/%postname%/' );
flush_rewrite_rules( false );

nps_log( 'Clauseway demo ready.' );
