<?php

$all_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_studiofolio_all_meta',
	'title' => 'Background',
	'template' => get_template_directory() . '/lib/metaboxes/all-meta.php',
	'types' => array('post','page','portfolio')
));

/* eof */