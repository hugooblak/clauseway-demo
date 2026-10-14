<?php
/**
 * Security section. Dark band with a pinned graphic: the document folds into a card, the lock
 * closes, a SEALED pill appears. blocks.js drives it from scroll. Without JavaScript the graphic
 * shows the sealed end state.
 *
 * @var array $attributes Block attributes.
 * @package ClausewayBlocks
 */

defined( 'ABSPATH' ) || exit;

$a = $attributes;
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'sec-dark', 'id' => 'security' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="wrap sticky">
		<div>
			<h2><?php echo esc_html( $a['heading'] ?? '' ); ?></h2>
			<p class="sub"><?php echo esc_html( $a['sub'] ?? '' ); ?></p>
			<ul class="bul">
				<?php
				foreach ( cwb_lines( $a['bullets'] ?? '' ) as $line ) :
					list( $icon, $text ) = cwb_cells( $line, 2 );
					?>
					<li><?php echo cwb_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $text ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<svg class="vault" id="vault" viewBox="0 0 400 420" aria-hidden="true" focusable="false">
			<rect id="vcard" x="90" y="70" width="220" height="260" rx="30" fill="none" stroke="#fff" stroke-width="2.5" opacity=".35"/>
			<g id="vdoc">
				<rect x="100" y="60" width="200" height="270" rx="16" fill="#fff"/>
				<rect x="124" y="92" width="110" height="12" rx="6" fill="#111"/>
				<rect x="124" y="124" width="152" height="9" rx="4.5" fill="#CFC8C1"/><rect x="124" y="146" width="140" height="9" rx="4.5" fill="#CFC8C1"/><rect x="124" y="168" width="152" height="9" rx="4.5" fill="#E5391F"/><rect x="124" y="190" width="120" height="9" rx="4.5" fill="#CFC8C1"/><rect x="124" y="212" width="146" height="9" rx="4.5" fill="#CFC8C1"/><rect x="124" y="234" width="100" height="9" rx="4.5" fill="#CFC8C1"/>
			</g>
			<g id="vlock" opacity="0">
				<path id="vsh" d="M170 186v-24a30 30 0 0 1 60 0v24" fill="none" stroke="#fff" stroke-width="12" stroke-linecap="round"/>
				<rect x="150" y="184" width="100" height="78" rx="16" fill="#fff"/>
				<circle cx="200" cy="218" r="9" fill="#111"/><rect x="196" y="222" width="8" height="18" rx="4" fill="#111"/>
			</g>
			<g id="vseal" opacity="0"><rect x="125" y="352" width="150" height="50" rx="25" fill="#E5391F"/><text x="200" y="385" text-anchor="middle" fill="#fff" font-family="Inter, sans-serif" font-weight="700" font-size="22" letter-spacing="1.5"><?php echo esc_html( $a['sealLabel'] ?? '' ); ?></text></g>
		</svg>
	</div>
</section>
