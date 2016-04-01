<?php

$portfolio_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_studiofolio_portfolio_meta',
	'title' => 'Portfolio box',
	'template' => get_template_directory() . '/lib/metaboxes/portfolio-meta.php',
	'types' => array('portfolio'),
	'priority' => 'core',
	'mode' => WPALCHEMY_MODE_EXTRACT
));

/* eof */