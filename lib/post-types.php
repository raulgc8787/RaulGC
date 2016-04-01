<?php

// Custom post types

function post_type_portfolio() {

	global $wpdb;

	register_post_type('portfolio', array( 'label' => 'Portfolio Items','description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'show_in_nav_menus' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => '%portfolio_page%','with_front'=>true),'query_var' => true,'supports' => array('title','editor','trackbacks','revisions','thumbnail'),'labels' => array (
				'name' => 'Portfolio Items',
				'singular_name' => 'Portfolio Item',
				'menu_name' => 'Portfolio Items',
				'add_new' => 'Add Portfolio Item',
				'add_new_item' => 'Add New Portfolio Item',
				'edit' => 'Edit',
				'edit_item' => 'Edit Portfolio Item',
				'new_item' => 'New Portfolio Item',
				'view' => 'View Portfolio Item',
				'view_item' => 'View Portfolio Item',
				'search_items' => 'Search Portfolio Items',
				'not_found' => 'No Portfolio Items Found',
				'not_found_in_trash' => 'No Portfolio Items Found in Trash',
				'parent' => 'Parent Portfolio Item',
			),) );
			
			
	add_rewrite_tag( '%portfolio%', '([^/]+)' );
	add_permastruct('portfolio', '%portfolio_page%/%portfolio%/', false );

	$querystr = "
	    SELECT $wpdb->posts.* 
	    FROM $wpdb->posts, $wpdb->postmeta
	    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
	    AND $wpdb->postmeta.meta_key = '_wp_page_template' 
	    AND $wpdb->postmeta.meta_value = 'templates/portfolio.php' 
	    AND $wpdb->posts.post_status = 'publish' 
	    AND $wpdb->posts.post_type = 'page'";
	
	$pageposts = $wpdb->get_results($querystr, OBJECT);

	foreach ($pageposts as $pagepost){
		add_rewrite_rule($pagepost->post_name.'/([^/]*)/?$','index.php?portfolio=$matches[1]','top');
		add_rewrite_rule($pagepost->post_name.'/([^/]+)/page/?([0-9]{1,})/?$','index.php?portfolio=$matches[1]&paged=$matches[2]','top');
	}
	
	flush_rewrite_rules();
	 
}
//Initialise custom post type
add_action('init', 'post_type_portfolio');

/**
 * Add custom taxonomies *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

function add_custom_taxonomies() {
	// Add new "Locations" taxonomy to Posts
	register_taxonomy('p_category', 'portfolio', array(
			// Hierarchical taxonomy (like categories)
			'hierarchical' => true, // This array of options controls the labels displayed in the WordPress Admin UI
			'labels' => array( 
				'name' => _x( 'Portfolio Category', 'taxonomy general name', 'studiofolio' ), 
				'singular_name' => _x( 'Category', 'taxonomy singular name', 'studiofolio' ), 
				'search_items' => __( 'Search Category', 'studiofolio' ), 
				'all_items' => __( 'All Categories', 'studiofolio' ),
				'parent_item' => __( 'Parent Category', 'studiofolio' ), 
				'parent_item_colon' => __( 'Parent Category:', 'studiofolio' ), 
				'edit_item' => __( 'Edit Category', 'studiofolio' ), 
				'update_item' => __( 'Update Category', 'studiofolio' ),
				'add_new_item' => __( 'Add New Category', 'studiofolio' ),
				'new_item_name' => __( 'New Category Name', 'studiofolio' ), 
				'menu_name' => __( 'Portfolio Categories', 'studiofolio' ), 
			), // Control the slugs used for this taxonomy
			'rewrite' => array( 
				'slug' => 'portfolio-category', // This controls the base slug that will display before each term
				'with_front' => false, // Don't display the category base before "/locations/"
				'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
			), 
		)
	);
	 
}; 

add_action( 'init', 'add_custom_taxonomies', 0 );

add_filter('post_link', 'portfolio_permalink', 10, 3);
add_filter('post_type_link', 'portfolio_permalink', 10, 3);

function portfolio_permalink($permalink, $post_id, $leavename) {

    if (strpos($permalink, '%portfolio_page%') === FALSE) return $permalink;
     
    	// Get post
        global $wpdb;
	
		 $sql = "
		   	  SELECT $wpdb->postmeta.meta_value
		   	  	FROM $wpdb->postmeta
		   	  LEFT JOIN $wpdb->posts ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
		   	  	WHERE $wpdb->posts.ID = '".$post_id->ID."'  
		   	  AND $wpdb->postmeta.meta_key = 'portfolio_page'
		   	  GROUP BY $wpdb->postmeta.meta_id
		   ";
	
	   	$pageid = $wpdb->get_var($sql);
	   	
	   	$page = &get_post($pageid);

 
    return str_replace('%portfolio_page%', $page->post_name, $permalink);
}   