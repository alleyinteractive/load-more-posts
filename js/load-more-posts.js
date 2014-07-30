jQuery( document ).ready( function( $ ) {

	// JS to submit ajax for load more button
	$(document).on('click', '#load-more-posts', function(e) {
		$( '#load-more-posts-error' ).hide();
		
		var previouspaged = $( this ).attr( 'data-paged' );
		var currentpaged = parseInt( previouspaged )+ 1;

		jQuery.ajax({
			type: 'POST',
			url: wp_ajax_url,
			data: {
				action : 'load_more_posts',
				paged: currentpaged,
				context: $(this).attr( 'data-context' ),
				query: load_more_data.query,
				nonce: $( '#load-more-posts-nonce' ).val(),
			},
			dataType: 'html'
		})
		.done( function( response ) {
			if ( response ) {
				$( '#load-more-posts-area-wrapper' ).append( response );
				if ( currentpaged > parseInt( $( '#load-more-posts' ).attr( 'data-max-pages' ) ) ) {
					$( '#load-more-posts' ).hide();
				} else {
					$( '#load-more-posts' ).attr( 'data-paged', currentpaged );
				}		
			} else {
				$( '#load-more-posts' ).hide();
				$( '#load-more-posts-error' ).show();
			}
		} );
		e.preventDefault();
	});

});