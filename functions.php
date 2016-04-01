<?php
/**
 * Required by WordPress.
 *
 * Keep this file clean and only use it for requires.
 */

require_once locate_template('/lib/utils.php');           		// Utility functions
require_once locate_template('/lib/init.php');            		// Initial theme setup and constants
require_once locate_template('/lib/admin.php');        	  		// Back-end functions
require_once locate_template('/lib/envato/index.php');    		// Auto update
require_once locate_template('/lib/config.php');          		// Configuration and constants
require_once locate_template('/lib/cleanup.php');         		// Cleanup
require_once locate_template('/lib/widgets.php');         		// Sidebars and widgets
require_once locate_template('/lib/template-tags.php');  		  // Template tags
require_once locate_template('/lib/actions.php');       		  // Actions
require_once locate_template('/lib/scripts.php');    		      // Scripts and stylesheets
require_once locate_template('/lib/post-types.php');  		    // Custom post types
require_once locate_template('/lib/shortcode.php');  		 		  // shortcodes
require_once locate_template('/lib/metaboxes.php');       		// Custom metaboxes
require_once locate_template('/lib/custom.php');          		// Custom functions

// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}





add_action('manage_portfolio_item_custom_column',  'my_show_columns');
function my_show_columns($name) {
    global $portfolio_item;
    switch ($name) {
        case 'views':
            $views = get_portfolio_item_meta($portfolio->ID, 'views', true);
            echo $views;
    }
}

