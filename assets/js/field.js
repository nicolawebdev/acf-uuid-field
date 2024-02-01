/**
 * Included when uuid_field fields are rendered for editing by publishers.
 */
 ( function( $ ) {
	function initialize_field( $field ) {
		/**
		 * $field is a jQuery object wrapping field elements in the editor.
		 */
		$field.find( '.acf-uuid-field' ).on( 'click', function() {

			const uuid = $( this ).text();

			const tempInput = $('<input>');
			$('body').append(tempInput);
			tempInput.val(uuid).select();
			document.execCommand('copy');
			tempInput.remove();

			const popoverHTML = $('<span class="acf-uuid-field__copied">Copied!</span>');

			if ( $(this).next('.acf-uuid-field__copied').length === 0 ) {
				$( this ).after( popoverHTML );
				popoverHTML.fadeIn( 100, function() {
					setTimeout( function() {
						popoverHTML.fadeOut( 100, function() {
							popoverHTML.remove();
						});
					}, 1000 );
				});
			}

		});
	}

	if( typeof acf.add_action !== 'undefined' ) {
		/**
		 * Run initialize_field when existing fields of this type load,
		 * or when new fields are appended via repeaters or similar.
		 */
		acf.add_action( 'ready_field/type=uuid_field', initialize_field );
		acf.add_action( 'append_field/type=uuid_field', initialize_field );
	}
} )( jQuery );
