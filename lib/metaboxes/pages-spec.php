<?php

$pages_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_studiofolio_pages_meta',
	'title' => 'Main thumbnail size',
	'template' => get_template_directory() . '/lib/metaboxes/pages-meta.php',
	'types' => array('post','page'),
	//'exclude_template' => 'templates/contact.php'
));

/* eof */