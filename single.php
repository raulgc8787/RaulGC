<?php 

	$template = get_post_meta($post->ID,'_post_template',true);
	switch ($template) {
		case ('templates/blog-center.php'):
			get_template_part('templates/blog', 'center');
		break;
		case ('templates/blog-center-featured.php'):
			get_template_part('templates/blog', 'center-featured');
		break;
		case ('templates/blog-side.php'):
			get_template_part('templates/blog', 'side');
		break;
		case ('templates/blog-full.php'):
			get_template_part('templates/blog', 'full');
		break;
		default:
			the_content();
			comment_form();
		break;
	} 

?>