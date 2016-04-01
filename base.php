<?php
global $data;

$IERedirect = (isset($data['IERedirect']) && $data['IERedirect']) ? $data['IERedirect'] : 'http://browsehappy.com/';
?><?php get_template_part('templates/head'); ?>

<body <?php body_class(); ?>>
    <!--[if lt IE 7]><div class="alert">Your browser is <em>ancient!</em> <a href="<?php echo $IERedirect; ?>">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->
    <?php get_template_part('templates/header');?><?php
    
    if (function_exists('icl_object_id')) $getid = icl_object_id(get_queried_object_id(), 'page', false,ICL_LANGUAGE_CODE);
    else $getid = $getid = get_queried_object_id(); 
    
    $pagetemplate = get_post_meta($getid, '_post_template', true);
    
    $horizclass = '';
    if ($pagetemplate == 'templates/portfolio-center.php' || $pagetemplate == 'templates/portfolio-center-full.php' || $pagetemplate == 'templates/portfolio-center-iso-fixed.php' || $pagetemplate == 'templates/blog-center.php' || $pagetemplate == 'templates/blog-center-featured.php' || is_page_template('templates/page-center.php') || is_page_template('templates/page-text-center.php') || is_page_template('templates/contact-center.php') || is_page_template('templates/contact-full.php')) $horizclass = ' horizontal';
    
    if (get_option('page_for_posts') == $getid) $horizclass = ' horizontal';
    if (isset($data['regular_index']) && $data['regular_index'] && (is_home() || is_archive() || is_search())) $horizclass = ' horizontal';
        	
		$querystr = "
		    SELECT $wpdb->posts.* 
		    FROM $wpdb->posts, $wpdb->postmeta
		    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		    AND $wpdb->postmeta.meta_key = '_wp_page_template' 
		    AND $wpdb->postmeta.meta_value = 'templates/front-page.php' 
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'page'";
		
		$pagesfront = $wpdb->get_results($querystr, OBJECT);
		
		if (count($pagesfront) > 1 && $pagesfront[0]->ID != $getid) {
			$addId = '_'.$getid;
		} else {
			$addId = '';
		}
		
    ?>
    
    <div id="content" class="" role="document">
        
        <?php     
                        
            if (is_page_template ('templates/front-page.php')) {

                if (isset($data['html_text_feature'.$addId]) && $data['html_text_feature'.$addId]) { ?>
                
            <div class="message progressive">
                <?php echo $data['html_text_feature'.$addId]; ?>
            </div>
            
            <?php }
                
            if (((isset($data['slideshow'.$addId]) && $data['slideshow'.$addId]) || (isset($data['rs_id'.$addId]) && $data['rs_id'.$addId])) && $data['slideshow_on'.$addId]) { ?>

            <div class="gallery_element">
                <div class="slideshow progressive">
                	<?php 
                		if (isset($data['rs_on'.$addId]) && $data['rs_on'.$addId]) {
	                		echo do_shortcode('[rev_slider '.$data['rs_id'.$addId].']');	
										} else { ?>
                    <div class="flexslider<?php if (isset($data['sshow_effect'.$addId]) && $data['sshow_effect'.$addId]) echo ' ' . $data['sshow_effect'.$addId]; ?><?php if (isset($data['sshow_thumbs'.$addId]) && $data['sshow_thumbs'.$addId]) echo ' wthumbs' ?><?php if (isset($data['sshow_loop'.$addId]) && $data['sshow_loop'.$addId]) echo ' loop' ?><?php if (isset($data['sshow_auto'.$addId]) && $data['sshow_auto'.$addId]) echo ' autoplay' ?>" data-aspect="<?php if (isset($data['aspect'.$addId]) && $data['aspect'.$addId]) echo $data['aspect'.$addId]; ?>" data-mheight="<?php if (isset($data['minheight'.$addId]) && $data['minheight'.$addId]) echo $data['minheight'.$addId]; ?>" data-offset="<?php if (isset($data['sshow_offset'.$addId]) && $data['sshow_offset'.$addId]) echo $data['sshow_offset'.$addId]; ?>">
                        <ul class="slides">
                            <?php
                              $gallery_mb->the_meta($data['slideshow'.$addId]);
                              $gimages = $gallery_mb->get_the_value('slides');
                              
                              if (is_array($gimages)) {
                              	  $counter = 0;
                                  foreach($gimages as &$gimage) {
                                      if (isset($gimage['imgurl'])) {
                                          $imgID = $gimage['imgurl'];
                                          if (isset($gimage['caption'])) $intext = '<div class="thumb-overlay-icon"><div class="thumb-overlay-inner"><div class="thumb-overlay-content"><h1>'.$gimage['caption'].'</h1></div></div></div>';
                                          else $intext = '<div class="thumb-overlay-icon"><div class="thumb-overlay-inner"><div class="thumb-overlay-content"></div></div></div>';
                                          if (isset($gimage['video'])) echo '<li data-thumb="'.wp_get_attachment_thumb_url( $imgID).'"><a href="'.$gimage['video'].'" class="fresco video" data-fresco-group="videogr'.$counter.'"><div class="slide">'.wp_get_attachment_image( $imgID, 'full', 0).$intext.'</div></a></li>';
                                          else if (isset($gimage['link'])) echo '<li data-thumb="'.wp_get_attachment_thumb_url( $imgID).'"><a href="'.$gimage['link'].'"><div class="slide">'.wp_get_attachment_image( $imgID, 'full', 0).$intext.'</div></a></li>';
                                          else echo '<li data-thumb="'.wp_get_attachment_thumb_url( $imgID).'"><div class="slide">'.wp_get_attachment_image( $imgID, 'full', 0).$intext.'</div></li>';
                                      } else {
	                                      $source = $gimage['video'];
	                                      $embed_code = wp_oembed_get($source);
											
	                                      if ($embed_code) echo '<li><div class="slide">'.$embed_code.'</div></li>';
                                        else echo '<li><div class="slide">'.do_shortcode($source).'</div></li>';
                                      }
                                      $counter++;
                                  }
                              }
                            ?>
                        </ul>
                    </div>
                 <?php } ?>
                </div>
            </div><?php }
            } ?>
        <div class="container-fluid main<?php echo $horizclass; ?>" role="main">
            <div class="row-fluid">
                <div class="<?php studiofolio_main_class(); ?>">
                    <?php include studiofolio_template_path(); ?>
                </div><!-- container-fluid -->
                <?php if (studiofolio_sidebar()) : ?>

                <div class="<?php studiofolio_sidebar_class(); ?> side-right-cont sidebar-blog progressive" role="complementary">
                    <div class="sidebar-cont">
                        <div class="entry-cont">
                            <?php get_template_part('templates/sidebar'); ?>
                        </div>
                    </div>
                </div><?php endif; ?>
            </div>
        </div>
        <div id="blocklayer"></div>
    </div><!-- content -->

    <div class="clearfix"></div>
    <?php get_template_part('templates/footer'); ?>
</body>
</html>
