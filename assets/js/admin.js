/**
 * Выбор одного изображения
 */
( function( $ ) {

	'use strict';

	jQuery( document ).ready( function () {

		jQuery( '.image-select' ).each( function( index, container ) {

			let $container = jQuery( container );
			let $control = $container.find( '[name]' );
			let $image = jQuery( '.thumbnail' );
			let $delete = $container.find( '.delete' );
			let customMediaLibrary = window.wp.media( {
				frame: 'select',
				multiple: false,
				library: {
					order: 'DESC',
					orderby: 'date',
					type: 'image',
					search: null,
					uploadedTo: null
				},
				button: {
					text: 'Done'
				}
			} );

			function OpenMediaLibrary( event ) {
				event.preventDefault();
				customMediaLibrary.open();
			}

			function Delete( event ) {
				event.preventDefault();
				$control.val( '' );
				$control.trigger( 'change' );
			}

			function AddSelectedImage() {
				let selectionAPI = customMediaLibrary.state().get( 'selection' );
				let attachment;
				if ( $control.val() ) {
					attachment = wp.media.attachment( $control.val() );
					SetSrc( attachment.sizes.thumbnail.url );
					selectionAPI.add( attachment ? [ attachment ] : [] );
				}
			}

			function SetSrc( src ) {
				if ( $image == null || typeof( $image ) != 'undefined' || $image.length == 0 ) {
					$image = jQuery( '<img />', {
						class: 'thumbnail'
					} ).prependTo( $container );
				}
				$image.attr( 'src', src );
			}

			function Select() {
				var attachments = customMediaLibrary.state().get( 'selection' ).toJSON();
				if ( typeof( attachments[ 0 ].sizes.thumbnail.url ) != 'undefined' && attachments[ 0 ].sizes.thumbnail.url !== null ) {
					$control.val( attachments[ 0 ].id );
					SetSrc( attachments[ 0 ].sizes.thumbnail.url );
				} else {
					$control.val( '' );
					$control.trigger( 'change' );
				}
			}

			function Change( event ) {
				if ( ! $control.val() ) {
					$image.remove();
				}
			}

			$container.on( 'click', '.thumbnail', OpenMediaLibrary );
			$container.on( 'click', '.add', OpenMediaLibrary );
			$control.on( 'change', Change );
			$delete.on( 'click', Delete );
			customMediaLibrary.on( 'select', Select );
			customMediaLibrary.on( 'open', AddSelectedImage );

		} );
		
	} );
} )( jQuery );



/**
 * Сохранение
 */
( function( $ ) {

	'use strict';

	let $button = jQuery( '.custom-submit' );

	function Submit( event ) {
		event.preventDefault();
		jQuery( '#publish' ).trigger( 'click' );
	}

	$button.on( 'click', Submit );

} )( jQuery );


/**
 * Очистка метабокса
 */
( function( $ ) {

	'use strict';

	let $button = jQuery( '.metabox-reset' );

	function Reset( event ) {
		event.preventDefault();
		jQuery( event.target ).closest( '.postbox' ).find( '[name]' ).each( function ( index, control ) {
			jQuery( control ).val( '' );
			jQuery( control ).trigger( 'change' );
		} );
	}

	$button.on( 'click', Reset );

} )( jQuery );