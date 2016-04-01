<?php
/*
Template Name: Page Centered Fullwidth
*/
?>
<?php while (have_posts()) : the_post(); ?>
	<div class="portfolio-container horizontal">
		<?php get_template_part('templates/page', 'center-inside'); ?>
	</div>
<?php endwhile; ?>