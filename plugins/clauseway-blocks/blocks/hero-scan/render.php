<?php
/**
 * Hero scan. A contract page with flagged clauses and comment cards. blocks.js plays the scan.
 * Without JavaScript it shows the finished state (see blocks.css).
 *
 * @var array $attributes Block attributes.
 * @package ClausewayBlocks
 */

defined( 'ABSPATH' ) || exit;

$a       = $attributes;
$clauses = cwb_lines( $a['clauses'] ?? '' );
$cards   = cwb_lines( $a['cards'] ?? '' );
$tags    = array(
	'high' => __( 'High risk', 'clauseway-blocks' ),
	'med'  => __( 'Medium', 'clauseway-blocks' ),
);
$card_i  = 0;
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'stage', 'id' => 'stage' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="doc" id="doc">
		<div class="dhead"><h3 aria-level="2"><?php echo esc_html( $a['docTitle'] ?? '' ); ?></h3><span class="chip" id="chip"><?php esc_html_e( 'Ready', 'clauseway-blocks' ); ?></span></div>
		<?php
		foreach ( $clauses as $n => $line ) :
			list( $title, $text, $flag ) = cwb_cells( $line, 3 );
			$flag                        = in_array( $flag, array( 'high', 'med' ), true ) ? $flag : 'none';
			$data                        = 'none' === $flag ? '' : ' data-card="' . (int) $card_i++ . '"';
			?>
			<div class="cl" data-f="<?php echo esc_attr( $flag ); ?>"<?php echo $data; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<h4 aria-level="3"><span><?php echo (int) ( $n + 1 ) . '. ' . esc_html( $title ); ?></span><span class="tag"><?php echo 'none' === $flag ? '' : cwb_icon( 'flag' ) . esc_html( $tags[ $flag ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></h4>
				<p><?php echo esc_html( $text ); ?></p>
			</div>
		<?php endforeach; ?>
		<div class="beam" id="beam"></div>
	</div>
	<div class="cards" id="cards">
		<?php
		foreach ( $cards as $line ) :
			list( $title, $text ) = cwb_cells( $line, 2 );
			?>
			<div class="card"><h4 aria-level="3"><?php echo cwb_icon( 'flag' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $title ); ?></h4><p><?php echo esc_html( $text ); ?></p></div>
		<?php endforeach; ?>
	</div>
	<svg class="wires" id="wires" aria-hidden="true"></svg>
</div>
