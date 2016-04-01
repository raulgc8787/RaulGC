<?php
/*
Template Name: Gallery
*/
?>

<?php
	global $gallery_mb, $data; 
	
	if ($gallery_mb->get_the_value('randomize')) shuffle($gallery_mb->meta['slides']);
	$mposts = $gallery_mb->get_the_value('slides');

	$disabled = '';
	if ($gallery_mb->get_the_value('disable_zoom')) $disabled .= ' zoom_disable';
	if ($gallery_mb->get_the_value('disable_overlay')) $disabled .= ' overlay_disable';
	if ($gallery_mb->get_the_value('disable_lightbox')) $disabled = ' click_disable zoom_disable overlay_disable';

	$counter=0;

	/* Start loop */ ?>
<?php while (have_posts()) : the_post(); 

	if (post_password_required()) { ?>
		
	<div class="entry-cont progressive">
		<div class="span12">
			<h1 class="portfolio-title"><?php the_title(); ?></h1>
			<?php echo(get_the_password_form()); ?>
		</div>
	</div>
	
	<?php } else {
	
	$output = get_the_content();
	if ($output != '') {
		remove_filter('the_content','wpautop', 12);
		$output = apply_filters('the_content', '<div class="top-html-blocks">'.get_the_content().'</div>');
		add_filter('the_content','wpautop', 12);
		echo $output;
	}

	if ($gallery_mb->get_the_value('gallery_slidehow')) { ?>
	
		<div class="gallery_element featured">
            <div class="slideshow progressive">
                <div class="flexslider<?php if ($gallery_mb->get_the_value('sshow_effect')) echo ' ' . $gallery_mb->get_the_value('sshow_effect'); ?><?php if ($gallery_mb->get_the_value('sshow_thumbs')) echo ' wthumbs' ?><?php if ($gallery_mb->get_the_value('sshow_loop')) echo ' loop'; ?><?php if ($gallery_mb->get_the_value('sshow_auto')) echo ' autoplay'; ?>" data-aspect="<?php if ($gallery_mb->get_the_value('sshow_ratio')) echo $gallery_mb->get_the_value('sshow_ratio'); ?>" data-mheight="<?php if ($gallery_mb->get_the_value('sshow_min')) echo $gallery_mb->get_the_value('sshow_min'); ?>" data-offset="<?php if ($gallery_mb->get_the_value('sshow_offset')) echo $gallery_mb->get_the_value('sshow_offset'); ?>">
                    <ul class="slides">
                        <?php
                          if (is_array($mposts)) {
                          	  $counter = 0;
                              foreach($mposts as &$mpost) { 
                                  if (isset($mpost['imgurl']) || isset($mpost['video'])) {
                                      $imgID = $mpost['imgurl'];
                                      if (isset($mpost['caption'])) $intext = '<div class="thumb-overlay-icon"><div class="thumb-overlay-inner"><div class="thumb-overlay-content"><h1>'.$mpost['caption'].'</h1></div></div></div>';
                                      else $intext = '<div class="thumb-overlay-icon"><div class="thumb-overlay-inner"><div class="thumb-overlay-content"></div></div></div>';
                                      if (isset($mpost['video'])) echo '<li data-thumb="'.wp_get_attachment_thumb_url( $imgID).'"><a href="'.$mpost['video'].'" class="fresco video" data-fresco-group="videogr'.$counter.'"><div class="slide">'.wp_get_attachment_image( $imgID, 'full', 0).$intext.'</div></a></li>';
                                      else if (isset($mpost['link'])) echo '<li data-thumb="'.wp_get_attachment_thumb_url( $imgID).'"><a href="'.$mpost['link'].'"><div class="slide">'.wp_get_attachment_image( $imgID, 'full', 0).$intext.'</div></a></li>';
                                      else echo '<li data-thumb="'.wp_get_attachment_thumb_url( $imgID).'"><div class="slide">'.wp_get_attachment_image( $imgID, 'full', 0).$intext.'</div></li>';
                                  }
                                  $counter++;
                              }
                          }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
	
	<?php } else { ?>
	
		<div class="container-isotope">
		<div id="isotope" class="<?php  ?>">
		<?php
		// loop a set of fields
		
		$total_posts = count($mposts);
		$load_more = is_numeric($gallery_mb->get_the_value('loadmore')) ? $gallery_mb->get_the_value('loadmore') : -1;
		
		if (is_front_page()) $paged = (get_query_var('page')) ? get_query_var('page') : 1;
		else $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		
		if ($load_more > 0) $mposts = array_slice($mposts, ($paged - 1) * $load_more, $load_more);
		
		if ($gallery_mb->get_the_value('lightbox_thumbnail')) $thumbs = ' data-fresco-group-options="thumbnails:true"';
		else $thumbs = ' data-fresco-group-options="thumbnails:false"';
		
		if (is_array($mposts)) {
		
		foreach($mposts as &$mpost) { 
			if (isset($mpost['size'])) $getsize = $mpost['size']; 
			else $getsize = 'width2'; 
			
			$provider = '';
			if (isset($mpost['video'])) $provider = (find_oembed($mpost['video']));
			if(strpos(trim($provider), ' ') !== false) $provider = 'unknown';
		?>
			<div class="progressive element <?php echo $getsize; ?> item<?php echo $counter; ?>">
				<div class="inside">
					<?php 
						if (isset($data['gallery_caption']) && $data['gallery_caption']) {
					?><div class="entry-thumb<?php if (!$mpost['caption']) echo ' wplus'; ?>" data-overlay="<?php if ($mpost['caption']) echo $mpost['caption']; else echo '&#xe072;'; ?>"><?php		
						} else {
					?><div class="entry-thumb wplus" data-overlay="<?php echo '&#xe072;'; ?>"><?php		
						}
							if (isset($mpost['imgurl'])) {
								$imgID = $mpost['imgurl'];
								$image_attributes = wp_get_attachment_image_src( $imgID, 'full' );
								$imgurl = $image_attributes[0];
								$urlquery = parse_url($imgurl);
								if (isset($urlquery['query'])) {
									parse_str($urlquery['query'], $output);
									if (isset($output['resize'])) {
										$imgsize = explode(",", $output['resize']);
										$image_attributes[1] = $imgsize[0];
										$image_attributes[2] = $imgsize[1];
									}
								} ?>	
							<div class="dummy" style="margin-top: <?php echo get_dummy_height($image_attributes[1],$image_attributes[2]); ?>%"></div>
							<?php } else $imgID = '';

							if (isset($mpost['caption'])) $caption = $mpost['caption']; 
							else $caption = '';
							
							if (isset($mpost['panning'])) $panning = ' data-fresco-options="fit: \'width\'"';
							else $panning = '';
							
							$alt = (isset($mpost['alt'])) ? $mpost['alt'] : basename($image_attributes[0]);
							
							if (isset($mpost['video'])) {
							
								if ($imgID && ($provider == 'Vimeo' || $provider == 'Youtube')) { ?>
							
								<a href="<?php echo $mpost['video']; ?>" class="fresco video pushed<?php echo $disabled; ?>" data-fresco-group='<?php echo( basename(get_permalink()) ); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $thumbs; ?>><?php echo wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full fresco") ); ?><div class="video"></div></a>
								
								<?php } else {
									$source = $mpost['video'];
									$embed_code = wp_oembed_get($source);
									
									if ($embed_code) $embedded = $embed_code;
									else $embedded = do_shortcode($source);
									
									if (isset($mpost['link'])) echo '<a href="'.$mpost['link'].'" class="video'.$disabled.'">'.$embedded.'<div class="video link"></div></a>';
									else echo $embedded;
								}
								
							} else { 
								
								if (isset($mpost['link'])) echo '<a href="'.$mpost['link'].'" class="video pushed'.$disabled.'">'.wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full") ).'<div class="video link"></div></a>';
								else {
							?>
						
							<a href="<?php echo $image_attributes[0]; ?>" class="fresco pushed<?php echo $disabled; ?>" data-fresco-group='<?php echo( basename(get_permalink()) ); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $panning; ?><?php echo $thumbs; ?>><?php echo wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full fresco") ); ?></a> 
				  		<?php  }
				  			} 

				  		$counter++;
				  		?>
				  		
					</div>
				</div>
			</div>
			
		<?php }
		} ?>	
		</div>
		<!-- isotope -->
	</div>
	<!-- container-isotope -->
	<?php
	if ($total_posts > $load_more && $load_more > 0) { ?>
	<div class="load-more hvr progressive">
    	<div class="entry-text-cont">
    		<a href="#" data-pages="<?php echo ceil($total_posts / $load_more); ?>" data-page="<?php echo $paged; ?>" data-link="<?php echo next_posts($total_posts, false); ?>">More Items...</a>
    	</div>
    </div>
    <?php } 
	   }
	} ?>
<?php endwhile; /* End loop */ ?>