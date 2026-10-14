/**
 * Editor side of the Clauseway blocks. No build step: plain JavaScript using WordPress's globals.
 * Text is edited in the sidebar; the preview is drawn by the same PHP as the live site.
 * Each block lists its fields: [ attribute, label, kind, help ] where kind is "line" (one line)
 * or "area" (one entry per line) or "number".
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var Disabled = wp.components.Disabled;
	var ServerSideRender = wp.serverSideRender;
	var t = 'clauseway-blocks';

	function field( props, f ) {
		var key = f[ 0 ];
		var Control = 'area' === f[ 2 ] ? TextareaControl : TextControl;
		return el( Control, {
			key: key,
			label: f[ 1 ],
			help: f[ 3 ],
			type: 'number' === f[ 2 ] ? 'number' : undefined,
			rows: 'area' === f[ 2 ] ? 6 : undefined,
			value: props.attributes[ key ],
			onChange: function ( v ) {
				var o = {};
				o[ key ] = 'number' === f[ 2 ] ? ( parseFloat( v ) || 0 ) : v;
				props.setAttributes( o );
			},
		} );
	}

	function block( name, panels ) {
		wp.blocks.registerBlockType( name, {
			edit: function ( props ) {
				var sidebar = panels.map( function ( p, i ) {
					return el( PanelBody, { key: i, title: p[ 0 ], initialOpen: 0 === i }, p[ 1 ].map( function ( f ) { return field( props, f ); } ) );
				} );
				return el(
					'div',
					useBlockProps(),
					el( InspectorControls, null, sidebar ),
					el( Disabled, null, el( ServerSideRender, { block: name, attributes: props.attributes } ) )
				);
			},
			save: function () { return null; },
		} );
	}

	block( 'clauseway/hero-scan', [
		[ __( 'Contract', t ), [
			[ 'docTitle', __( 'Document title', t ), 'line' ],
			[ 'clauses', __( 'Clauses', t ), 'area', __( 'One per line: title | text | flag. Flag is high, med or none.', t ) ],
		] ],
		[ __( 'Comment cards', t ), [
			[ 'cards', __( 'Cards', t ), 'area', __( 'One per line: title | text. They attach to the flagged clauses, in order.', t ) ],
		] ],
	] );

	block( 'clauseway/product-tabs', [
		[ __( 'Tabs and clause list', t ), [
			[ 'tabs', __( 'Four tab names', t ), 'area', __( 'One per line.', t ) ],
			[ 'clauseList', __( 'Clause list', t ), 'area', __( 'One per line: name | High, Medium or Low.', t ) ],
		] ],
		[ __( 'Review tab', t ), [
			[ 'reviewTitle', __( 'Clause title', t ), 'line' ],
			[ 'reviewTag', __( 'Tag', t ), 'line' ],
			[ 'reviewText', __( 'Clause text', t ), 'area' ],
			[ 'reviewWhy', __( 'Why we flagged this', t ), 'area' ],
			[ 'reviewAsk', __( 'Ask for instead', t ), 'area' ],
			[ 'attention', __( 'Number needing attention', t ), 'number' ],
			[ 'highCount', __( 'High risk count', t ), 'number' ],
			[ 'medCount', __( 'Medium risk count', t ), 'number' ],
			[ 'lowCount', __( 'Low risk count', t ), 'number' ],
		] ],
		[ __( 'Redline tab', t ), [
			[ 'redlineTag', __( 'Tag', t ), 'line' ],
			[ 'redlineCurrent', __( 'Current text', t ), 'area', __( 'Put [[double brackets]] around words that get struck out.', t ) ],
			[ 'redlineSuggested', __( 'Suggested text', t ), 'area' ],
			[ 'edits', __( 'Suggested edits', t ), 'area', __( 'One per line.', t ) ],
		] ],
		[ __( 'Compare tab', t ), [
			[ 'compareTitle', __( 'Title', t ), 'line' ],
			[ 'compareTag', __( 'Tag', t ), 'line' ],
			[ 'yourDraft', __( 'Your draft', t ), 'area', __( 'Put [[double brackets]] around the words that differ.', t ) ],
			[ 'theirDraft', __( 'Their draft', t ), 'area', __( 'Same number of [[brackets]] as your draft.', t ) ],
			[ 'changes', __( 'Changes list', t ), 'area', __( 'One per line: what | Worse or Review.', t ) ],
		] ],
		[ __( 'Approve tab', t ), [
			[ 'approveTitle', __( 'Title', t ), 'line' ],
			[ 'approveTag', __( 'Tag', t ), 'line' ],
			[ 'approvers', __( 'Approvers', t ), 'area', __( 'One per line: initials | role | what they do.', t ) ],
			[ 'readyTitle', __( 'Status heading', t ), 'line' ],
			[ 'readyText', __( 'Status text', t ), 'line' ],
			[ 'sendLabel', __( 'Button', t ), 'line' ],
		] ],
	] );

	block( 'clauseway/how-scroll', [
		[ __( 'Steps', t ), [
			[ 'heading', __( 'Heading', t ), 'line' ],
			[ 'steps', __( 'Steps', t ), 'area', __( 'One per line: title | text.', t ) ],
		] ],
		[ __( 'Document and summary', t ), [
			[ 'fileName', __( 'File name', t ), 'line' ],
			[ 'clauses', __( 'Clauses', t ), 'area', __( 'One per line: name | level (High, Medium, or empty) | text.', t ) ],
			[ 'readLabel', __( 'Counter label', t ), 'line' ],
			[ 'reportTitle', __( 'Summary title', t ), 'line' ],
			[ 'reportRows', __( 'Summary rows', t ), 'area', __( 'One per line: what | level.', t ) ],
			[ 'reportButton', __( 'Summary button', t ), 'line' ],
		] ],
	] );

	block( 'clauseway/playbook', [
		[ __( 'Rules and clauses', t ), [
			[ 'rules', __( 'Rules', t ), 'area', __( 'One per line: rule | clause number | off or ok.', t ) ],
			[ 'clauses', __( 'Clauses', t ), 'area', __( 'One per line: name | text.', t ) ],
			[ 'ruleLabel', __( 'Rule caption', t ), 'line' ],
			[ 'offLabel', __( 'Label when off playbook', t ), 'line' ],
			[ 'okLabel', __( 'Label when matching', t ), 'line' ],
		] ],
	] );

	block( 'clauseway/vault', [
		[ __( 'Security section', t ), [
			[ 'heading', __( 'Heading', t ), 'line' ],
			[ 'sub', __( 'Text', t ), 'area' ],
			[ 'bullets', __( 'Points', t ), 'area', __( 'One per line: icon | text. Icons: key, eye, trash, lock.', t ) ],
			[ 'sealLabel', __( 'Seal label', t ), 'line' ],
		] ],
	] );

	block( 'clauseway/cta-form', [
		[ __( 'Form', t ), [
			[ 'label', __( 'Email label', t ), 'line' ],
			[ 'button', __( 'Button', t ), 'line' ],
			[ 'thanks', __( 'Thank-you line', t ), 'area' ],
		] ],
	] );
} )( window.wp );
