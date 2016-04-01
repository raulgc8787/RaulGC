<?php
/*
Portfolio Template Layout: Portfolio Fixed Sidebar Isotope
*/
?>

<?php while (have_posts()) : the_post(); ?>

	<div class="portfolio-container fixed-sidebar limit center">
		<?php get_template_part('templates/portfolio', 'sidebar-iso-inside'); ?>
	</div>
	
<?php endwhile; ?>