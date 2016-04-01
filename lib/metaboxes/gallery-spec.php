<?php

$gallery_mb = new WPAlchemy_MetaBox(array
(
    'id' => '_studiofolio_gallery_meta',
    'title' => 'Gallery box',
    'template' => get_template_directory() . '/lib/metaboxes/gallery-meta.php',
    'include_template' => array('templates/gallery.php','templates/page-center.php','templates/page-center-full.php','templates/page-sidebar.php','templates/page-sidebar-iso.php','templates/page-sidebar-fixed.php','templates/page-sidebar-iso-fixed.php','templates/page-center-iso.php','templates/page-center-iso-fixed.php')
));

/* eof */