/* Manangatang Energy — Customizer repeater control (with icon picker) */
( function ( $ ) {
	'use strict';

	function escAttr( s ) {
		return ( s == null ? '' : String( s ) ).replace( /&/g, '&amp;' ).replace( /"/g, '&quot;' ).replace( /</g, '&lt;' );
	}

	function iconList() {
		return ( window.ME_REPEATER && Array.isArray( window.ME_REPEATER.icons ) ) ? window.ME_REPEATER.icons : [];
	}

	function refreshIcons( $scope ) {
		if ( window.lucide && typeof window.lucide.createIcons === 'function' ) {
			window.lucide.createIcons();
		}
	}

	function iconSelect( val ) {
		var list = iconList().slice();
		// Preserve a previously-saved value that isn't in the curated list.
		if ( val && list.indexOf( val ) === -1 ) {
			list.unshift( val );
		}
		var opts = '';
		list.forEach( function ( name ) {
			opts += '<option value="' + escAttr( name ) + '"' + ( name === val ? ' selected' : '' ) + '>' + escAttr( name ) + '</option>';
		} );
		return '<div class="me-rep-iconrow" style="display:flex;align-items:center;gap:8px;margin-bottom:5px">' +
			'<span class="me-rep-preview" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #dcdcde;border-radius:4px;background:#f6f7f7;color:#1f8f47;flex:none">' +
			'<i data-lucide="' + escAttr( val || 'dot' ) + '" style="width:18px;height:18px"></i>' +
			'</span>' +
			'<select class="me-rep-icon" style="flex:1">' + opts + '</select>' +
			'</div>';
	}

	function rowHtml( it ) {
		it = it || {};
		return '<div class="me-rep-row" style="border:1px solid #dcdcde;padding:10px;margin-bottom:8px;border-radius:4px;background:#fff">' +
			iconSelect( it.icon ) +
			'<input type="text" class="me-rep-label" placeholder="Text" value="' + escAttr( it.label ) + '" style="width:100%;margin-bottom:5px" />' +
			'<input type="text" class="me-rep-url" placeholder="Link (mailto:, tel:, https://) — optional" value="' + escAttr( it.url ) + '" style="width:100%;margin-bottom:6px" />' +
			'<button type="button" class="button-link me-rep-remove" style="color:#b32d2e">Remove</button>' +
			'</div>';
	}

	function render( $wrap ) {
		var raw = $wrap.find( '.me-rep-data' ).val() || '';
		var items = [];
		try {
			items = JSON.parse( raw || '[]' );
		} catch ( e ) {
			items = [];
		}
		if ( ! Array.isArray( items ) ) {
			items = [];
		}
		// Nothing saved yet → pre-fill the current defaults (email / phone / address)
		// so they're visible and editable instead of an empty control.
		if ( items.length === 0 && '' === raw && window.ME_REPEATER && Array.isArray( window.ME_REPEATER.defaults ) ) {
			items = window.ME_REPEATER.defaults;
		}
		var $rows = $wrap.find( '.me-rep-rows' ).empty();
		items.forEach( function ( it ) {
			$rows.append( rowHtml( it ) );
		} );
		refreshIcons( $wrap );
	}

	function sync( $wrap ) {
		var items = [];
		$wrap.find( '.me-rep-row' ).each( function () {
			var $r = $( this );
			items.push( {
				icon: $r.find( '.me-rep-icon' ).val(),
				label: $r.find( '.me-rep-label' ).val(),
				url: $r.find( '.me-rep-url' ).val()
			} );
		} );
		$wrap.find( '.me-rep-data' ).val( JSON.stringify( items ) ).trigger( 'change' );
	}

	// Add a row.
	$( document ).on( 'click', '.me-rep-add', function () {
		var $wrap = $( this ).closest( '.me-repeater' );
		$wrap.find( '.me-rep-rows' ).append( rowHtml( {} ) );
		refreshIcons( $wrap );
	} );

	// Remove a row.
	$( document ).on( 'click', '.me-rep-remove', function () {
		var $wrap = $( this ).closest( '.me-repeater' );
		$( this ).closest( '.me-rep-row' ).remove();
		sync( $wrap );
	} );

	// Update the icon preview when the picker changes.
	$( document ).on( 'change', '.me-rep-icon', function () {
		var $row = $( this ).closest( '.me-rep-row' );
		var val = $( this ).val() || 'dot';
		$row.find( '.me-rep-preview' ).html( '<i data-lucide="' + escAttr( val ) + '" style="width:18px;height:18px"></i>' );
		refreshIcons( $row );
		sync( $( this ).closest( '.me-repeater' ) );
	} );

	// Text fields.
	$( document ).on( 'input change', '.me-rep-label, .me-rep-url', function () {
		sync( $( this ).closest( '.me-repeater' ) );
	} );

	// Single icon picker — update the preview when the dropdown changes.
	$( document ).on( 'change', '.me-icon-select', function () {
		var $c = $( this ).closest( '.me-icon-control' );
		var v = $( this ).val() || 'phone';
		$c.find( '.me-icon-preview' ).html( '<i data-lucide="' + escAttr( v ) + '" style="width:18px;height:18px"></i>' );
		refreshIcons( $c );
	} );

	function reflow() {
		$( '.me-repeater' ).each( function () {
			render( $( this ) );
		} );
		refreshIcons();
	}

	$( reflow );
	if ( window.wp && window.wp.customize ) {
		wp.customize.bind( 'ready', function () {
			reflow();
			// Controls mount lazily — re-render Lucide icons when a section opens.
			[ 'me_sec_footer', 'me_sec_header' ].forEach( function ( id ) {
				if ( wp.customize.section && wp.customize.section( id ) ) {
					wp.customize.section( id ).expanded.bind( function ( isOpen ) {
						if ( isOpen ) {
							setTimeout( reflow, 50 );
						}
					} );
				}
			} );
		} );
	}
} )( jQuery );
