<?php

$portfolio_page_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_studiofolio_portfolio_page_meta',
	'title' => 'Load more',
	'template' => get_template_directory() . '/lib/metaboxes/portfolio-page-meta.php',
	'include_template' => 'templates/portfolio.php'
));

/* eof */