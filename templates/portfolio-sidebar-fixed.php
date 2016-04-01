<?php
/*
Portfolio Template Layout: Portfolio Fixed Sidebar
*/
?>

<?php while (have_posts()) : the_post(); ?>

	<div class="portfolio-container fixed-sidebar limit">
		<?php get_template_part('templates/portfolio', 'sidebar-inside'); ?>
	</div>
	
<?php endwhile; ?>