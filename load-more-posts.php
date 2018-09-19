<?php
/*
	Plugin Name: Load More Posts
	Plugin URI: https://github.com/alleyinteractive/load-more-posts
	Description: Easily add ajax load more buttons
	Version: 0.1
	Author: William Gladstone
	Author URI: http://www.alleyinteractive.com/
*/
/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Helper functions
require_once  dirname( __FILE__ ) . '/functions.php';

if ( !class_exists( 'Load_More_Posts' ) ) :

/**
 * Load more button functionality  
 * Uses WP pagination
 */
class Load_More_Posts {

	/**
	 * Add hooks
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_load_more_posts', array( $this, 'load_more_posts' ) );
		add_action( 'wp_ajax_nopriv_load_more_posts', array( $this, 'load_more_posts' ) );
	}

	/**
	 * Enqueue the necessary assets
	 */
	public function enqueue_assets() {
		wp_enqueue_script( 'load-more-posts-js', plugins_url( '/', __FILE__ ) . '/js/load-more-posts.js', array( 'jquery' ), '0.1', true );
		wp_localize_script( 'load-more-posts-js', 'wp_ajax_url', admin_url( 'admin-ajax.php' ) );
	}

	/**
	 * Spit out the button html
	 * @param $text. (optional) Text to display on button
	 * @param $paged. (optional) WP query var
	 * @return void
	 */
	public function load_more_button( $context, $text, $paged ) {
		global $wp_query;

		// Lets recreate the current query within our ajax call
		wp_localize_script( 'load-more-posts-js', 'load_more_data', array( 'query' => $wp_query->query ) );

		echo '<div id="load-more-posts-area-wrapper"></div>';
		wp_nonce_field( 'load-more-posts-nonce-' . $context, 'load-more-posts-nonce' );
		echo '<div id="load-more-posts-error" class="load-more-posts-error error" style="display:none;">' . esc_html__( 'Something has gone wrong. Please try again.', 'load-more-posts' ) . '</div>';
		echo '<button id="load-more-posts" class="load-more-posts-button" data-context="'.esc_attr__( $context, 'load-more-posts' ).'" data-paged="'.esc_attr__( $paged, 'load-more-posts' ).'" data-max-pages="'. $wp_query->max_num_pages.'">'.esc_html__( $text, 'load-more-posts' ).'</button>';
	}

	/**
	 * Ajax handler for load more posts
	 */
	public function load_more_posts() {
		if ( empty( $_POST['nonce'] ) || empty( $_POST['paged'] ) || ! wp_verify_nonce( $_POST['nonce'], 'load-more-posts-nonce-'  . $_POST['context'] ) ) {
		   exit;
		} else {
			global $post; // required by setup post data
			$context = ( ! empty( $_POST['context'] ) ) ? sanitize_text_field( $_POST['context'] ) : 'default';
			$args = (array) $_POST['query'];
			$args['paged'] = sanitize_text_field( $_POST['paged'] );
			$args['post_status'] = 'publish';

			// A filter if you want to customize the query
			$args = apply_filters( 'load-more-posts-args-' . sanitize_text_field( $_POST['context'] ), $args );
			
			$query = new WP_Query( $args );
			$posts = $query->get_posts();
			foreach( $posts as $post ) {
				setup_postdata( $post );
				get_template_part( 'content', $context );
				wp_reset_postdata();
			}
		}
		exit;
	}
}
new Load_More_Posts();

endif;