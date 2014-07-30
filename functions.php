<?php
/**
 * Add a load more button to your page
 * @param string $context
 * @param string $text. (optional) Text to display on button.
 * @param int $paged. (optional) WP query var.
 * @return void
 */
function load_more_button( $context = 'default', $text = 'More Posts', $paged = 0 ) {
	if ( empty( $paged ) ) {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
	}
	$load_more = new Load_More_Posts();
	$load_more->load_more_button( $context, $text, $paged );
}