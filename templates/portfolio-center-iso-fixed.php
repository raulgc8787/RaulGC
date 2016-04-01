<?php
/*
Portfolio Template Layout: Portfolio Centered Isotope
*/
?>

<?php while (have_posts()) : the_post(); ?>

	<div class="portfolio-container horizontal iso">
		<div class="row-fluid">
			<div class="main-cont main-side">
				<div class="container-isotope">
					<div id="isotope" class="isotope">
						<?php get_template_part('templates/portfolio-center-iso-inside'); ?>
					</div>
				</div>
				<div class="entry-cont progressive">
					<div class="span12">
						<?php get_template_part('templates/sidebar'); ?>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	
<?php endwhile; ?>