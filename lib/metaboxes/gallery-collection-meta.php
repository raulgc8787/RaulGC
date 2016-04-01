<?php
	
?>
<div id="undsgn_container" class="studiofolio_meta_control">
<?php 
	$args = array(
	    'post_status' => 'publish',
	    'post_type' => 'page',
	    'order' => 'ASC',
	    'orderby' => 'menu_order',
	    'posts_per_page' => -1,
	    'meta_query' => array(
	    	'relation' => 'OR',
	        array(
	            'key' => '_wp_page_template',
	            'compare' => '=',
	            'value' => 'templates/gallery.php'
	        )    
	    )
	);
	$pages = new WP_Query($args);
	// The Loop
	while ( $pages->have_posts() ) : $pages->the_post(); ?>
		<?php $mb->the_field('gallery_ID', WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI); ?>
		<label for="<?php $mb->the_name(); ?>"><input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo get_the_ID(); ?>"<?php $mb->the_checkbox_state(get_the_ID()); ?>/> <?php echo get_the_title(); ?></label>
	<?php endwhile;
	// Reset Post Data
	wp_reset_postdata();
?> 
</div>