<?php

/**
 * WooThemes Media Library-driven AJAX File Uploader Module (2010-11-05)
 *
 * Slightly modified for use in the Options Framework.
 */

/**
 * Sets up a custom post type to attach image to.  This allows us to have
 * individual galleries for different uploaders.
 */
add_action( 'init', 'undsgn_mlu_init');
if ( ! function_exists( 'undsgn_mlu_init' ) ) {
	function undsgn_mlu_init () {
		register_post_type( 'options', array(
			'labels' => array(
				'name' => __( 'Options', 'undsgnoptions' ),
			),
			'public' => true,
			'show_ui' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'supports' => array( 'title', 'editor' ), 
			'query_var' => false,
			'can_export' => true,
			'show_in_nav_menus' => false
		) );
	}
}

/**
 * Forces insert into post
*/

add_filter( 'get_media_item_args', 'force_send' );
function force_send($args){
	$args['send'] = true;
	return $args;
}

/**
 * Adds the Thickbox CSS file and specific loading and button images to the header
 * on the pages where this function is called.
 */

if ( ! function_exists( 'undsgn_mlu_css' ) ) {

	function undsgn_mlu_css () {
	
		$_html = '';
		$_html .= '<link rel="stylesheet" href="' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/thickbox.css" type="text/css" media="screen" />' . "\n";
		$_html .= '<script type="text/javascript">
		var tb_pathToImage = "' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/loadingAnimation.gif";
	    var tb_closeImage = "' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/tb-close.png";
	    </script>' . "\n";
	    
	    echo $_html;
		
	}

}

/**
 * Registers and enqueues (loads) the necessary JavaScript file for working with the
 * Media Library-driven AJAX File Uploader Module.
 */

if ( ! function_exists( 'undsgn_mlu_js' ) ) {

	function undsgn_mlu_js () {
	
		// Registers custom scripts for the Media Library AJAX uploader.
		wp_register_script( 'undsgn-medialibrary-uploader', ADMIN_DIR .'js/undsgn-medialibrary-uploader.js', array( 'jquery', 'thickbox' ) );
		wp_enqueue_script( 'undsgn-medialibrary-uploader' );
		wp_enqueue_script( 'media-upload' );
	}

}

/**
 * Uses "silent" posts in the database to store relationships for images.
 * This also creates the facility to collect galleries of, for example, logo images.
 * 
 * Return: $_postid.
 *
 * If no "silent" post is present, one will be created with the type "undsgn"
 * and the post_name of "undsgn-$_token".
 *
 * Example Usage:
 * undsgn_mlu_get_silentpost ( 'undsgn_logo' );
 */

if ( ! function_exists( 'undsgn_mlu_get_silentpost' ) ) {

	function undsgn_mlu_get_silentpost ( $_token ) {
	
		global $wpdb;
		$_id = 0;
	
		// Check if the token is valid against a whitelist.
		// $_whitelist = array( 'undsgn_logo', 'undsgn_custom_favicon', 'undsgn_ad_top_image' );
		// Sanitise the token.
		
		$_token = strtolower( str_replace( ' ', '_', $_token ) );
		
		// if ( in_array( $_token, $_whitelist ) ) {
		if ( $_token ) {
			
			// Tell the function what to look for in a post.
			
			$_args = array( 'post_type' => 'options', 'post_name' => 'undsgn-' . $_token, 'post_status' => 'draft', 'comment_status' => 'closed', 'ping_status' => 'closed' );
			
			// Look in the database for a "silent" post that meets our criteria.
			$query = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_parent = 0';
			foreach ( $_args as $k => $v ) {
				$query .= ' AND ' . $k . ' = "' . $v . '"';
			} // End FOREACH Loop
			
			$query .= ' LIMIT 1';
			$_posts = $wpdb->get_row( $query );
			
			// If we've got a post, loop through and get it's ID.
			if ( count( $_posts ) ) {
				$_id = $_posts->ID;
			} else {
			
				// If no post is present, insert one.
				// Prepare some additional data to go with the post insertion.
				$_words = explode( '_', $_token );
				$_title = join( ' ', $_words );
				$_title = ucwords( $_title );
				$_post_data = array( 'post_title' => $_title );
				$_post_data = array_merge( $_post_data, $_args );
				$_id = wp_insert_post( $_post_data );
			}	
		}
		return $_id;
	}
}

/**
 * Trigger code inside the Media Library popup.
 */

if ( ! function_exists( 'undsgn_mlu_insidepopup' ) ) {

	function undsgn_mlu_insidepopup () {
	
		if ( isset( $_REQUEST['is_undsgn'] ) && $_REQUEST['is_undsgn'] == 'yes' ) {
		
			add_action( 'admin_head', 'undsgn_mlu_js_popup' );
			add_filter( 'media_upload_tabs', 'undsgn_mlu_modify_tabs' );
		}
	}
}

if ( ! function_exists( 'undsgn_mlu_js_popup' ) ) {

	function undsgn_mlu_js_popup () {

		$_undsgn_title = $_REQUEST['undsgn_title'];
		if ( ! $_undsgn_title ) { $_undsgn_title = 'file'; } // End IF Statement
?>
	<script type="text/javascript">
	<!--
	jQuery(function($) {
		
		jQuery.noConflict();
		
		// Change the title of each tab to use the custom title text instead of "Media File".
		$( 'h3.media-title' ).each ( function () {
			var current_title = $( this ).html();
			var new_title = current_title.replace( 'media file', '<?php echo $_undsgn_title; ?>' );
			$( this ).html( new_title );
		
		} );
		
		// Change the text of the "Insert into Post" buttons to read "Use this File".
		$( '.savesend input.button[value*="Insert into Post"], .media-item #go_button' ).attr( 'value', 'Use this File' );
		
		// Hide the "Insert Gallery" settings box on the "Gallery" tab.
		$( 'div#gallery-settings' ).hide();
		
		// Preserve the "is_undsgn" parameter on the "delete" confirmation button.
		$( '.savesend a.del-link' ).click ( function () {
		
			var continueButton = $( this ).next( '.del-attachment' ).children( 'a.button[id*="del"]' );
			var continueHref = continueButton.attr( 'href' );
			continueHref = continueHref + '&is_undsgn=yes';
			continueButton.attr( 'href', continueHref );
		
		} );
		
	});
	-->
	</script>
<?php
	}
}

/**
 * Triggered inside the Media Library popup to modify the title of the "Gallery" tab.
 */

if ( ! function_exists( 'undsgn_mlu_modify_tabs' ) ) {

	function undsgn_mlu_modify_tabs ( $tabs ) {
		$tabs['gallery'] = str_replace( __( 'Gallery', 'undsgnoptions' ), __( 'Previously Uploaded', 'undsgnoptions' ), $tabs['gallery'] );
		return $tabs;
	}
}

if ( is_admin() ) {

    // Load additional css and js for image uploads on the Options Framework page

    $undsgn_page= 'appearance_page_options-framework';

    add_action( "admin_print_styles-$undsgn_page", 'undsgn_mlu_css', 0 );

    add_action( "admin_print_scripts-$undsgn_page", 'undsgn_mlu_js', 0 ); 

}