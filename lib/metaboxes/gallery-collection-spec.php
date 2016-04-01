<?php

$gallerycoll_mb = new WPAlchemy_MetaBox(array
(
    'id' => '_studiofolio_gallerycoll_meta',
    'title' => 'Galleries',
    'template' => get_template_directory() . '/lib/metaboxes/gallery-collection-meta.php',
    'include_template' => array('templates/gallery-collection.php')
));

/* eof */