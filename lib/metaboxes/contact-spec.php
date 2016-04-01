<?php

$contact_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_studiofolio_contact_meta',
	'title' => 'Contact info',
	'template' => get_template_directory() . '/lib/metaboxes/contact-meta.php',
	'include_template' => array('templates/contact-center.php','templates/contact-full.php','templates/contact-sidebar.php','templates/contact-sidebar-fixed.php')
));

/* eof */