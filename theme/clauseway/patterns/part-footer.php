<?php
/**
 * Title: Part: footer
 * Slug: clauseway/part-footer
 * Inserter: no
 *
 * Template part markup lives in a pattern so links use this site's own address.
 */

$cw_home = static function ( $hash = '' ) {
	return esc_url( home_url( '/' . $hash ) );
};
?>
<!-- wp:group {"className":"footer-in","layout":{"type":"default"}} -->
<div class="wp-block-group footer-in"><!-- wp:group {"className":"wrap fgrid","layout":{"type":"default"}} -->
<div class="wp-block-group wrap fgrid"><!-- wp:paragraph {"className":"logo"} -->
<p class="logo"><a href="<?php echo $cw_home(); // phpcs:ignore ?>">Clauseway</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"foot-links"} -->
<p class="foot-links"><a href="<?php echo $cw_home( '#product' ); // phpcs:ignore ?>">Product</a> <a href="<?php echo $cw_home( '#how' ); // phpcs:ignore ?>">How it works</a> <a href="<?php echo $cw_home( '#playbooks' ); // phpcs:ignore ?>">Playbooks</a> <a href="<?php echo $cw_home( '#security' ); // phpcs:ignore ?>">Security</a> <a href="<?php echo $cw_home( '#faq' ); // phpcs:ignore ?>">FAQ</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Concept site for a fictional company.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
