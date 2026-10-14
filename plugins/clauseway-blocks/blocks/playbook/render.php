<?php
/**
 * Playbook. Rules on the left, clauses on the right, connected by lines drawn in blocks.js.
 * Without JavaScript every rule shows its result.
 *
 * @var array $attributes Block attributes.
 * @package ClausewayBlocks
 */

defined( 'ABSPATH' ) || exit;

$a       = $attributes;
$rules   = cwb_lines( $a['rules'] ?? '' );
$clauses = cwb_lines( $a['clauses'] ?? '' );
$state   = array(); // Clause index => 'off' or 'ok'.
foreach ( $rules as $line ) {
	list( , $c, $s ) = cwb_cells( $line, 3 );
	$state[ max( 0, (int) $c - 1 ) ] = 'off' === $s ? 'off' : 'ok';
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'pgrid', 'id' => 'pgrid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="rules" id="rules">
		<?php
		foreach ( $rules as $i => $line ) :
			list( $rule, $c, $s ) = cwb_cells( $line, 3 );
			?>
			<div class="rule" data-c="<?php echo (int) max( 0, (int) $c - 1 ); ?>" data-off="<?php echo 'off' === $s ? '1' : '0'; ?>"><span class="ck"></span><div><b><?php echo esc_html( $rule ); ?></b><span><?php echo esc_html( ( $a['ruleLabel'] ?? '' ) . ' ' . ( $i + 1 ) ); ?></span></div></div>
		<?php endforeach; ?>
	</div>
	<div class="sp"></div>
	<div class="clauses" id="pcls">
		<?php
		foreach ( $clauses as $i => $line ) :
			list( $name, $text ) = cwb_cells( $line, 2 );
			$label               = isset( $state[ $i ] ) ? ( 'off' === $state[ $i ] ? ( $a['offLabel'] ?? '' ) : ( $a['okLabel'] ?? '' ) ) : '';
			?>
			<div class="pcl"><h4 aria-level="3"><span><?php echo esc_html( $name ); ?></span><em><?php echo esc_html( $label ); ?></em></h4><p><?php echo esc_html( $text ); ?></p></div>
		<?php endforeach; ?>
	</div>
	<svg class="pw" id="pw" aria-hidden="true"></svg>
</div>
