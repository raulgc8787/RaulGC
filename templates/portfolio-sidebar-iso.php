<?php
/*
Portfolio Template Layout: Portfolio Fluid Sidebar Isotope
*/
?>

<?php while (have_posts()) : the_post(); ?>

	<div class="portfolio-container">
		<?php get_template_part('templates/portfolio', 'sidebar-iso-inside'); ?>
	</div>
	
<?php endwhile; ?>