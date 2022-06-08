( function ( $ ) {

	var $form;
	var files;

	function SetFiles() {
		files = this.files;
	}

	function Send() {
		if ( typeof testwork538pl != 'undefined' ) {
			event.stopPropagation();
			event.preventDefault();
			var data = new FormData();
			data.append( 'action', 'insert_entry' );
			if ( typeof files != 'undefined' ) {
				data.append( 'custom_thumbnail', files[ 0 ] );
			}
			data.append( 'nonce', testwork538pl.nonce );
			data.append( 'data', $form.serialize() );
			jQuery.ajax( {
				url: testwork538pl.ajaxurl,
				method: 'POST',
				data: data,
				cache: false,
				dataType: 'json',
				processData : false,
				contentType : false,
				beforeSend: function ( jqXHR, settings ) {
					$form.off( 'submit', Send );
				},
				success: function ( respond ) {
					if ( typeof( respond.success ) != 'undefined' && respond.success ) {
						$form[ 0 ].reset();
						if ( typeof( respond.data ) != 'undefined' ) {
							$form.replaceWith( respond.data );
						} else {
							$form.replaceWith( testwork538pl.error );
						}
					}
				},
				error: function ( jqXHR, textStatus, errorThrown ) {
					console.log( textStatus );
					$form.replaceWith( testwork538pl.error );
				},
			} );
		}
	}

	function Init() {
		$form = jQuery( '#insert-product-form' );
		$form.on( 'submit', Send );
		$form.find( 'input[type=file]' ).on( 'change', SetFiles );
	}

	jQuery( document ).ready( Init );

} )( jQuery );