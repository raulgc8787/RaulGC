<?php 
	if (post_password_required()) { ?>
		
	<div class="entry-cont progressive">
		<div class="span12">
			<h1 class="portfolio-title"><?php the_title(); ?></h1>
			<?php echo(get_the_password_form()); ?>
		</div>
	</div>
	
	<?php } else {
		$template = get_post_meta($post->ID,'_post_template',true);
		switch ($template) {
			case ('templates/portfolio-center.php'):
				get_template_part('templates/portfolio', 'center');
			break;
			case ('templates/portfolio-center-full.php'):
				get_template_part('templates/portfolio', 'center-full');
			break;
			case ('templates/portfolio-center-iso.php'):
				get_template_part('templates/portfolio', 'center-iso');
			break;
			case ('templates/portfolio-center-iso-fixed.php'):
				get_template_part('templates/portfolio', 'center-iso-fixed');
			break;
			case ('templates/portfolio-sidebar.php'):
				get_template_part('templates/portfolio', 'sidebar');
			break;
			case ('templates/portfolio-sidebar-fixed.php'):
				get_template_part('templates/portfolio', 'sidebar-fixed');
			break;
			case ('templates/portfolio-sidebar-iso.php'):
				get_template_part('templates/portfolio', 'sidebar-iso');
			break;
			case ('templates/portfolio-sidebar-iso-fixed.php'):
				get_template_part('templates/portfolio', 'sidebar-iso-fixed');
			break;
			default:
				get_template_part('templates/portfolio', 'center');
			break;
		} 
	}
?>