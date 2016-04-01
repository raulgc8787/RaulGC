<?php
/*
Template Name: Page Fixed Sidebar Isotope
*/
?>
<?php while (have_posts()) : the_post(); ?>
	<div class="portfolio-container horizontal">
		<?php get_template_part('templates/page', 'sidebar-iso-inside'); ?>
	</div>
<?php endwhile; ?>