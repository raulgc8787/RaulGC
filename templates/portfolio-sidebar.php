<?php
/*
Portfolio Template Layout: Portfolio Fluid Sidebar
*/
?>

<?php while (have_posts()) : the_post(); ?>

	<div class="portfolio-container">
		<?php get_template_part('templates/portfolio', 'sidebar-inside'); ?>
	</div>
	
<?php endwhile; ?>