<?php
/*
Template Name: Gallery Collection
*/
?>

<?php

	global $data;
	
	$titleON = (isset($data['gallery_title'])) ? $data['gallery_title'] : 0;
	
	$meta = get_post_meta(get_the_ID(), '_studiofolio_gallerycoll_meta', TRUE);
	$mposts = $meta['gallery_ID'];	
	
	if (post_password_required()) { ?>
		
	<div class="entry-cont progressive">
		<div class="span12">
			<h1 class="portfolio-title"><?php the_title(); ?></h1>
			<?php echo(get_the_password_form()); ?>
		</div>
	</div>
	
	<?php } else { 

		$output = $post->post_content;
		if ($output != '') {
			remove_filter('the_content','wpautop', 12);
			$output = apply_filters('the_content', '<div class="top-html-blocks">'.$post->post_content.'</div>');
			add_filter('the_content','wpautop', 12);
			echo $output;
		}
	?>
	
	<div class="container-isotope">
		<div id="isotope"> 
	
	<?php if (is_array($mposts)){

			foreach ($mposts as $mpost) {
			
				$singlemeta = get_post_meta($mpost, '_studiofolio_pages_meta', true);
				if (isset($singlemeta['size'])) $getsize = $singlemeta['size']; 
				else $getsize = 'width2'; ?>
				<div class="progressive element <?php echo $getsize; ?>">
					<div class="inside">
						<div class="entry-thumb<?php if ($titleON) echo ' wplus'; ?>" data-overlay="<?php if ($titleON) echo '&#xe072;'; else echo get_the_title($mpost); ?>" data-area="">
							<?php 
							
							$getslides = get_post_meta($mpost, '_studiofolio_gallery_meta', true);
							$slides = $getslides['slides'];
						
							if (is_array($slides)) {
								$counter = 0;
								foreach($slides as &$slide) { 
												 
									if (isset($slide['imgurl'])) {
										$imgID = $slide['imgurl'];
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
										}
										if ($counter == 0) { ?>
									<div class="dummy" style="margin-top: <?php echo get_dummy_height($image_attributes[1],$image_attributes[2]); ?>%"></div>
									
								<?php	}
										
										}  
									
									if (isset($slide['caption'])) $caption = $slide['caption']; 
									else $caption = '';
									
									if (isset($slide['panning'])) $panning = ' data-fresco-options="fit: \'width\'"';
									else $panning = '';
									
									if (isset($getslides['lightbox_thumbnail'])) $thumbs = ' data-fresco-group-options="thumbnails:true"';
									else $thumbs = ' data-fresco-group-options="thumbnails:false"';
									
									if ($counter == 1 && count($slides) > 1) echo '<div class="rest_collection">';
									
									if (isset($slide['video'])) { ?>
										
										<a href="<?php echo $slide['video']; ?>" class="fresco pushed video<?php if ($counter == 0) echo ' firstel'; ?>" data-fresco-group='<?php echo get_the_title($mpost); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $thumbs; ?>><?php echo wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full fresco") ); ?><div class="video"></div></a>
										
									<?php } else { ?>
								
									<a href="<?php echo $image_attributes[0]; ?>" class="pushed fresco<?php if ($counter == 0) echo ' firstel'; ?>" data-fresco-group='<?php echo get_the_title($mpost); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $panning; ?><?php echo $thumbs; ?>><?php echo wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full fresco") ); ?></a>
									<?php }
									$counter++;
		
									if (count($slides) == $counter && count($slides) > 1) echo '</div>';
								}	
							} ?>
						</div>
						<?php if ( $titleON) { ?>
						<div class="entry-text-cont">
							
								<h2 class="entry-title"><a href="#"><?php echo get_the_title($mpost); ?></a></h2>

						</div>
	    				<?php } ?>
					</div>
				</div>
				
			<?php }
			
			
			} ?>	
		</div>
		<!-- isotope -->
	</div>
	<!-- container-isotope -->
    <?php } ?>