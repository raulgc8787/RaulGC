<?php
/*
Portfolio Template Layout: Portfolio Centered Fullwidth
*/
?>

<?php while (have_posts()) : the_post(); ?>

	<div class="portfolio-container">
		<?php get_template_part('templates/portfolio', 'center-inside'); ?>
	</div>
	
<?php endwhile; ?>