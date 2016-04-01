<?php

$pages_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_studiofolio_post_meta',
	'title' => 'Media info',
	'template' => get_template_directory() . '/lib/metaboxes/posts-meta.php',
	'types' => array('post')
));

/* eof */