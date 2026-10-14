<?php
/**
 * How it works, scroll-driven. blocks.js maps scroll progress to the three states.
 * Without JavaScript it shows the final state (summary card next to the read document).
 *
 * @var array $attributes Block attributes.
 * @package ClausewayBlocks
 */

defined( 'ABSPATH' ) || exit;

$a     = $attributes;
$steps = cwb_lines( $a['steps'] ?? '' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'how', 'id' => 'how' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-read="<?php echo esc_attr( $a['readLabel'] ?? '' ); ?>">
	<div class="wrap sticky">
		<div class="steps">
			<div class="shead" style="margin-bottom:18px"><h2 style="margin-bottom:0"><?php echo esc_html( $a['heading'] ?? '' ); ?></h2></div>
			<?php
			foreach ( $steps as $i => $line ) :
				list( $title, $text ) = cwb_cells( $line, 2 );
				?>
				<div class="step<?php echo 0 === $i ? ' on' : ''; ?>"><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $text ); ?></p></div>
			<?php endforeach; ?>
		</div>
		<div class="hs" id="hs">
			<div class="hdoc" id="hdoc">
				<div class="fr"><?php echo cwb_icon( 'doc' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $a['fileName'] ?? '' ); ?></div>
				<div class="up"><i id="up"></i></div>
				<?php
				foreach ( cwb_lines( $a['clauses'] ?? '' ) as $line ) :
					list( $name, $level, $text ) = cwb_cells( $line, 3 );
					?>
					<div class="hl"><h5 aria-level="4"><?php echo esc_html( $name ); ?><?php echo $level ? ' <small>' . esc_html( $level ) . '</small>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?></h5><p><?php echo esc_html( $text ); ?></p></div>
				<?php endforeach; ?>
				<div class="hbeam" id="hbeam"></div>
				<div class="cnt" id="hcnt"></div>
			</div>
			<div class="hrep" id="hrep"><h4><?php echo esc_html( $a['reportTitle'] ?? '' ); ?></h4>
				<?php
				foreach ( cwb_lines( $a['reportRows'] ?? '' ) as $line ) :
					list( $what, $level ) = cwb_cells( $line, 2 );
					?>
					<div class="srow <?php echo 'high' === strtolower( $level ) ? 'h' : ( 'medium' === strtolower( $level ) ? 'm' : '' ); ?>"><i></i><?php echo esc_html( $what ); ?><em><?php echo esc_html( $level ); ?></em></div>
				<?php endforeach; ?>
				<span class="btn pri sm" style="width:100%;margin-top:18px"><?php echo esc_html( $a['reportButton'] ?? '' ); ?></span>
			</div>
		</div>
	</div>
</section>
