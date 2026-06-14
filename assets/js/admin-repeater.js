/* Manangatang Energy — generic repeater for the Customization admin page */
( function ( $ ) {
	'use strict';

	function esc( s ) {
		return ( s == null ? '' : String( s ) ).replace( /&/g, '&amp;' ).replace( /"/g, '&quot;' ).replace( /</g, '&lt;' );
	}

	function fieldHtml( f, val, icons ) {
		var label = '<span style="font-size:11px;font-weight:600;color:#646970">' + esc( f.label ) + '</span><br>';
		if ( 'icon' === f.type ) {
			var list = ( icons || [] ).slice();
			if ( val && list.indexOf( val ) === -1 ) {
				list.unshift( val );
			}
			var opts = '';
			list.forEach( function ( n ) {
				opts += '<option value="' + esc( n ) + '"' + ( n === val ? ' selected' : '' ) + '>' + esc( n ) + '</option>';
			} );
			return '<label style="display:block;margin-bottom:6px">' + label + '<select class="me-rep2-f" data-k="' + esc( f.key ) + '" style="width:100%;max-width:280px">' + opts + '</select></label>';
		}
		if ( 'image' === f.type ) {
			return '<div class="me-rep2-img" style="margin-bottom:6px">' + label +
				'<span class="me-rep2-img-prev" style="display:block;margin:2px 0 4px">' + ( val ? '<img src="' + esc( val ) + '" style="max-width:160px;height:auto;border:1px solid #dcdcde;border-radius:4px;display:block" />' : '' ) + '</span>' +
				'<input type="hidden" class="me-rep2-f me-rep2-imgurl" data-k="' + esc( f.key ) + '" value="' + esc( val ) + '" />' +
				'<button type="button" class="button me-rep2-imgbtn">Select image</button></div>';
		}
		if ( 'file' === f.type ) {
			return '<div class="me-rep2-file" style="margin-bottom:6px">' + label +
				'<input type="text" class="me-rep2-f" data-k="' + esc( f.key ) + '" value="' + esc( val ) + '" placeholder="File URL (PDF)" style="width:100%;max-width:420px;margin-bottom:4px" />' +
				'<button type="button" class="button me-rep2-filebtn">Select file</button></div>';
		}
		if ( 'textarea' === f.type ) {
			return '<label style="display:block;margin-bottom:6px">' + label + '<textarea class="me-rep2-f" data-k="' + esc( f.key ) + '" rows="2" class="large-text" style="width:100%">' + esc( val ) + '</textarea></label>';
		}
		return '<label style="display:block;margin-bottom:6px">' + label + '<input type="text" class="me-rep2-f" data-k="' + esc( f.key ) + '" value="' + esc( val ) + '" style="width:100%;max-width:420px" /></label>';
	}

	function rowHtml( it, fields, icons ) {
		it = it || {};
		var inner = '';
		( fields || [] ).forEach( function ( f ) {
			inner += fieldHtml( f, it[ f.key ] || '', icons );
		} );
		return '<div class="me-rep2-row" style="border:1px solid #dcdcde;border-radius:4px;padding:10px;margin-bottom:8px;background:#fff">' +
			inner +
			'<button type="button" class="button-link me-rep2-remove" style="color:#b32d2e">Remove</button>' +
			'</div>';
	}

	function render( $wrap ) {
		var fields = $wrap.data( 'fields' );
		var icons = $wrap.data( 'icons' );
		var items = [];
		try {
			items = JSON.parse( $wrap.find( '.me-rep2-data' ).val() || '[]' );
		} catch ( e ) {
			items = [];
		}
		if ( ! Array.isArray( items ) ) {
			items = [];
		}
		var $rows = $wrap.find( '.me-rep2-rows' ).empty();
		items.forEach( function ( it ) {
			$rows.append( rowHtml( it, fields, icons ) );
		} );
	}

	function sync( $wrap ) {
		var items = [];
		$wrap.find( '.me-rep2-row' ).each( function () {
			var o = {};
			$( this ).find( '.me-rep2-f' ).each( function () {
				o[ $( this ).data( 'k' ) ] = $( this ).val();
			} );
			items.push( o );
		} );
		$wrap.find( '.me-rep2-data' ).val( JSON.stringify( items ) );
	}

	$( document ).on( 'click', '.me-rep2-add', function () {
		var $wrap = $( this ).closest( '.me-rep2' );
		$wrap.find( '.me-rep2-rows' ).append( rowHtml( {}, $wrap.data( 'fields' ), $wrap.data( 'icons' ) ) );
		sync( $wrap );
	} );

	$( document ).on( 'click', '.me-rep2-remove', function () {
		var $wrap = $( this ).closest( '.me-rep2' );
		$( this ).closest( '.me-rep2-row' ).remove();
		sync( $wrap );
	} );

	$( document ).on( 'input change', '.me-rep2-f', function () {
		sync( $( this ).closest( '.me-rep2' ) );
	} );

	// Image picker inside a repeater row.
	$( document ).on( 'click', '.me-rep2-imgbtn', function ( e ) {
		e.preventDefault();
		var $cell = $( this ).closest( '.me-rep2-img' );
		var $wrap = $( this ).closest( '.me-rep2' );
		var frame = wp.media( { title: 'Select image', button: { text: 'Use image' }, multiple: false } );
		frame.on( 'select', function () {
			var a = frame.state().get( 'selection' ).first().toJSON();
			$cell.find( '.me-rep2-imgurl' ).val( a.url );
			$cell.find( '.me-rep2-img-prev' ).html( '<img src="' + a.url + '" style="max-width:160px;height:auto;border:1px solid #dcdcde;border-radius:4px;display:block" />' );
			sync( $wrap );
		} );
		frame.open();
	} );

	// File picker inside a repeater row (any media, e.g. PDF).
	$( document ).on( 'click', '.me-rep2-filebtn', function ( e ) {
		e.preventDefault();
		var $cell = $( this ).closest( '.me-rep2-file' );
		var $wrap = $( this ).closest( '.me-rep2' );
		var frame = wp.media( { title: 'Select file', button: { text: 'Use file' }, multiple: false } );
		frame.on( 'select', function () {
			var a = frame.state().get( 'selection' ).first().toJSON();
			$cell.find( '.me-rep2-f' ).val( a.url );
			sync( $wrap );
		} );
		frame.open();
	} );

	$( function () {
		$( '.me-rep2' ).each( function () {
			render( $( this ) );
		} );
	} );

	/* ---------- Image picker (media library) ---------- */
	$( document ).on( 'click', '.me-img-select', function ( e ) {
		e.preventDefault();
		var $wrap = $( this ).closest( '.me-img' );
		var frame = wp.media( { title: 'Select image', button: { text: 'Use image' }, multiple: false } );
		frame.on( 'select', function () {
			var a = frame.state().get( 'selection' ).first().toJSON();
			$wrap.find( '.me-img-url' ).val( a.url );
			$wrap.find( '.me-img-preview' ).html( '<img src="' + a.url + '" style="max-width:240px;height:auto;border:1px solid #dcdcde;border-radius:4px;display:block" />' );
		} );
		frame.open();
	} );

	$( document ).on( 'click', '.me-img-remove', function () {
		var $wrap = $( this ).closest( '.me-img' );
		$wrap.find( '.me-img-url' ).val( '' );
		$wrap.find( '.me-img-preview' ).empty();
	} );

	/* ---------- Gallery (multiple images in one grid) ---------- */
	function galleryUrls( $wrap ) {
		var urls = [];
		try {
			urls = JSON.parse( $wrap.find( '.me-gallery-data' ).val() || '[]' );
		} catch ( e ) {
			urls = [];
		}
		return Array.isArray( urls ) ? urls : [];
	}

	function galleryRender( $wrap ) {
		var $grid = $wrap.find( '.me-gallery-grid' ).empty();
		galleryUrls( $wrap ).forEach( function ( u ) {
			$grid.append( '<div class="me-gallery-item" data-url="' + esc( u ) + '"><img src="' + esc( u ) + '" /><button type="button" class="me-gallery-rm">&times;</button></div>' );
		} );
	}

	function gallerySync( $wrap ) {
		var urls = [];
		$wrap.find( '.me-gallery-item' ).each( function () {
			urls.push( $( this ).attr( 'data-url' ) );
		} );
		$wrap.find( '.me-gallery-data' ).val( JSON.stringify( urls ) );
	}

	$( document ).on( 'click', '.me-gallery-add', function ( e ) {
		e.preventDefault();
		var $wrap = $( this ).closest( '.me-gallery' );
		var frame = wp.media( { title: 'Select images', button: { text: 'Use images' }, multiple: true } );
		frame.on( 'select', function () {
			var urls = galleryUrls( $wrap );
			frame.state().get( 'selection' ).each( function ( a ) {
				urls.push( a.toJSON().url );
			} );
			$wrap.find( '.me-gallery-data' ).val( JSON.stringify( urls ) );
			galleryRender( $wrap );
		} );
		frame.open();
	} );

	$( document ).on( 'click', '.me-gallery-rm', function () {
		var $wrap = $( this ).closest( '.me-gallery' );
		$( this ).closest( '.me-gallery-item' ).remove();
		gallerySync( $wrap );
	} );

	$( function () {
		$( '.me-gallery' ).each( function () {
			galleryRender( $( this ) );
		} );
	} );
} )( jQuery );
