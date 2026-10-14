<?php
/**
 * Product tabs. Four scenes in one panel; blocks.js switches and plays them.
 * Without JavaScript the first tab (Review) is shown.
 *
 * @var array $attributes Block attributes.
 * @package ClausewayBlocks
 */

defined( 'ABSPATH' ) || exit;

$a      = $attributes;
$tabs   = cwb_lines( $a['tabs'] ?? '' );
$icons  = array( 'doc', 'pen', 'cmp', 'chk' );
$list   = cwb_lines( $a['clauseList'] ?? '' );
$acts   = array( 1, 1, 0, 3 );
$cls    = static function ( $level ) {
	$l = strtolower( $level );
	return 'high' === $l || 'worse' === $l ? 'h' : ( 'medium' === $l || 'review' === $l ? 'm' : '' );
};
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'frame', 'id' => 'frame' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="tabs" role="tablist">
		<?php foreach ( $tabs as $i => $label ) : ?>
			<button class="tab" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-t="<?php echo (int) $i; ?>"><?php echo cwb_icon( $icons[ $i ] ?? 'doc' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $label ); ?></button>
		<?php endforeach; ?>
	</div>
	<div class="panel">
		<div class="side">
			<h5 aria-level="3"><?php esc_html_e( 'Clauses', 'clauseway-blocks' ); ?></h5>
			<?php
			foreach ( $list as $i => $line ) :
				list( $name, $level ) = cwb_cells( $line, 2 );
				$c                    = $cls( $level );
				?>
				<div class="crow <?php echo esc_attr( trim( $c . ( $i === $acts[0] ? ' act' : '' ) ) ); ?>"><i></i><?php echo esc_html( $name ); ?><em><?php echo esc_html( $level ); ?></em></div>
			<?php endforeach; ?>
		</div>
		<div class="center" role="tabpanel" aria-label="<?php esc_attr_e( 'Selected step', 'clauseway-blocks' ); ?>">
			<div class="scene on" data-s="0">
				<div class="ctitle"><h4 aria-level="3"><?php echo esc_html( $a['reviewTitle'] ?? '' ); ?></h4><span class="pill"><?php echo esc_html( $a['reviewTag'] ?? '' ); ?></span></div>
				<p class="legal"><?php echo esc_html( $a['reviewText'] ?? '' ); ?></p>
				<div class="callout"><b><?php esc_html_e( 'Why we flagged this', 'clauseway-blocks' ); ?></b><?php echo esc_html( $a['reviewWhy'] ?? '' ); ?></div>
				<div class="callout plain"><b><?php esc_html_e( 'Ask for instead', 'clauseway-blocks' ); ?></b><?php echo esc_html( $a['reviewAsk'] ?? '' ); ?></div>
			</div>
			<div class="scene" data-s="1">
				<div class="ctitle"><h4 aria-level="3"><?php echo esc_html( $a['reviewTitle'] ?? '' ); ?></h4><span class="pill"><?php echo esc_html( $a['redlineTag'] ?? '' ); ?></span></div>
				<div class="lbl"><?php esc_html_e( 'Current', 'clauseway-blocks' ); ?></div>
				<p class="legal"><?php echo cwb_marked( $a['redlineCurrent'] ?? '', 'del' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<div class="lbl"><?php esc_html_e( 'Suggested', 'clauseway-blocks' ); ?></div>
				<p class="legal typed" data-text="<?php echo esc_attr( $a['redlineSuggested'] ?? '' ); ?>"><?php echo esc_html( $a['redlineSuggested'] ?? '' ); ?></p>
			</div>
			<div class="scene" data-s="2">
				<div class="ctitle"><h4 aria-level="3"><?php echo esc_html( $a['compareTitle'] ?? '' ); ?></h4><span class="pill"><?php echo esc_html( $a['compareTag'] ?? '' ); ?></span></div>
				<div class="two">
					<div class="col"><h6 aria-level="4"><?php esc_html_e( 'Your draft', 'clauseway-blocks' ); ?></h6><?php echo cwb_marked( $a['yourDraft'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
					<div class="col"><h6 aria-level="4"><?php esc_html_e( 'Their draft', 'clauseway-blocks' ); ?></h6><?php echo cwb_marked( $a['theirDraft'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				</div>
			</div>
			<div class="scene" data-s="3">
				<div class="ctitle"><h4 aria-level="3"><?php echo esc_html( $a['approveTitle'] ?? '' ); ?></h4><span class="pill" style="border-color:var(--ink);color:var(--ink)"><?php echo esc_html( $a['approveTag'] ?? '' ); ?></span></div>
				<?php
				foreach ( cwb_lines( $a['approvers'] ?? '' ) as $k => $line ) :
					list( $initials, $role, $what ) = cwb_cells( $line, 3 );
					?>
					<div class="appr"><span class="av"><?php echo esc_html( $initials ); ?></span><div><b><?php echo esc_html( $role ); ?></b><span><?php echo esc_html( $what ); ?></span></div><span class="st" style="--k:<?php echo (int) $k; ?>"><span class="w"><?php esc_html_e( 'Waiting', 'clauseway-blocks' ); ?></span><span class="a"><?php echo cwb_icon( 'chk' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Approved', 'clauseway-blocks' ); ?></span></span></div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="rcol">
			<div class="rscene on" data-r="0">
				<h5 aria-level="3"><?php esc_html_e( 'Risk summary', 'clauseway-blocks' ); ?></h5>
				<div class="ring" data-n="<?php echo (int) ( $a['attention'] ?? 0 ); ?>"><svg viewBox="0 0 140 140" aria-hidden="true"><circle class="tr" cx="70" cy="70" r="56"/><circle class="fg" cx="70" cy="70" r="56"/></svg><div><strong id="cnt"><?php echo (int) ( $a['attention'] ?? 0 ); ?></strong><span><?php esc_html_e( 'need attention', 'clauseway-blocks' ); ?></span></div></div>
				<div class="srow h"><i></i><?php esc_html_e( 'High risk', 'clauseway-blocks' ); ?><b><?php echo (int) ( $a['highCount'] ?? 0 ); ?></b></div>
				<div class="srow m"><i></i><?php esc_html_e( 'Medium risk', 'clauseway-blocks' ); ?><b><?php echo (int) ( $a['medCount'] ?? 0 ); ?></b></div>
				<div class="srow"><i></i><?php esc_html_e( 'Low risk', 'clauseway-blocks' ); ?><b><?php echo (int) ( $a['lowCount'] ?? 0 ); ?></b></div>
			</div>
			<div class="rscene" data-r="1">
				<h5 aria-level="3"><?php esc_html_e( 'Suggested edits', 'clauseway-blocks' ); ?></h5>
				<?php foreach ( cwb_lines( $a['edits'] ?? '' ) as $edit ) : ?>
					<div class="edit"><span><?php echo esc_html( $edit ); ?></span><button class="btn gho" type="button"><?php esc_html_e( 'Accept', 'clauseway-blocks' ); ?></button><span class="ok"><?php echo cwb_icon( 'chk' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Accepted', 'clauseway-blocks' ); ?></span></div>
				<?php endforeach; ?>
			</div>
			<div class="rscene" data-r="2">
				<h5 aria-level="3"><?php esc_html_e( 'Changes', 'clauseway-blocks' ); ?></h5>
				<?php
				foreach ( cwb_lines( $a['changes'] ?? '' ) as $line ) :
					list( $what, $level ) = cwb_cells( $line, 2 );
					?>
					<div class="srow <?php echo esc_attr( $cls( $level ) ); ?>"><i></i><?php echo esc_html( $what ); ?><b><?php echo esc_html( $level ); ?></b></div>
				<?php endforeach; ?>
			</div>
			<div class="rscene" data-r="3">
				<h5 aria-level="3"><?php esc_html_e( 'Status', 'clauseway-blocks' ); ?></h5>
				<div class="ready"><?php echo esc_html( $a['readyTitle'] ?? '' ); ?></div>
				<p class="note2"><?php echo esc_html( $a['readyText'] ?? '' ); ?></p>
				<div class="rbtn" style="margin-top:20px"><span class="btn pri sm"><?php echo esc_html( $a['sendLabel'] ?? '' ); ?></span></div>
			</div>
		</div>
	</div>
</div>
