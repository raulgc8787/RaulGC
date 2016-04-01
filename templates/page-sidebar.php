<?php
/*
Template Name: Page Fluid Sidebar
*/
?>
<?php while (have_posts()) : the_post(); ?>
	<div class="portfolio-container horizontal">
		<?php get_template_part('templates/page', 'sidebar-inside'); ?>
	</div>
<?php endwhile; ?>