<?php
/**
 * Demo form. No action, no request: blocks.js swaps it for the thank-you line.
 *
 * @var array $attributes Block attributes.
 * @package ClausewayBlocks
 */

defined( 'ABSPATH' ) || exit;

$a = $attributes;
$id = 'cwb-email';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'cta-form' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<form class="form" id="form" novalidate>
		<label class="fl" for="<?php echo esc_attr( $id ); ?>"><span><?php echo esc_html( $a['label'] ?? '' ); ?></span><input id="<?php echo esc_attr( $id ); ?>" type="email" required autocomplete="email"></label>
		<button class="btn pri" type="submit"><?php echo esc_html( $a['button'] ?? '' ); ?></button>
	</form>
	<p class="thanks" id="thanks" role="status" hidden><?php echo esc_html( $a['thanks'] ?? '' ); ?></p>
</div>
