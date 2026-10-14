<?php
/**
 * Title: Part: header
 * Slug: clauseway/part-header
 * Inserter: no
 *
 * Template part markup lives in a pattern so links use this site's own address.
 */

// Point the menu at the site's "Main menu" (created by the demo setup, editable in Appearance > Editor > Navigation).
$cw_menu = get_posts( array( 'post_type' => 'wp_navigation', 'title' => 'Main menu', 'numberposts' => 1, 'fields' => 'ids', 'post_status' => 'publish' ) );
$cw_ref  = $cw_menu ? '"ref":' . (int) $cw_menu[0] . ',' : '';
?>
<!-- wp:group {"anchor":"top","className":"site-header","layout":{"type":"default"}} -->
<div id="top" class="wp-block-group site-header"><!-- wp:group {"className":"wide hbar","layout":{"type":"default"}} -->
<div class="wp-block-group wide hbar"><!-- wp:paragraph {"className":"logo"} -->
<p class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Clauseway</a></p>
<!-- /wp:paragraph -->

<!-- wp:navigation {<?php echo $cw_ref; // phpcs:ignore ?>"overlayMenu":"mobile","className":"nav","layout":{"type":"flex","justifyContent":"left"}} /-->

<!-- wp:group {"className":"hr","layout":{"type":"default"}} -->
<div class="wp-block-group hr"><!-- wp:paragraph {"className":"gho-link"} -->
<p class="gho-link"><a href="<?php echo esc_url( home_url( '/#cta' ) ); ?>">Log in</a></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"pri sm"} -->
<div class="wp-block-button pri sm"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#cta' ) ); ?>">Book a demo</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
