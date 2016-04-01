<?php
					
	global $gallery_mb;
	$portfolio_page = $gallery_mb->get_the_value('portfolio_page');
	if ($gallery_mb->get_the_value('lightbox_thumbnail')) $thumbs = ' data-fresco-group-options="thumbnails:true"';
	else $thumbs = ' data-fresco-group-options="thumbnails:false"';

	$disabled = '';
	if ($gallery_mb->get_the_value('disable_zoom')) $disabled .= ' zoom_disable';
	if ($gallery_mb->get_the_value('disable_overlay')) $disabled .= ' overlay_disable';
	if ($gallery_mb->get_the_value('disable_lightbox')) $disabled = ' click_disable zoom_disable overlay_disable';
	
	$counter=0;
	// loop a set of fields
	while($gallery_mb->have_fields('slides')) { 
	$getsize = $gallery_mb->get_the_value('size'); 
	if (!$getsize) $getsize = 'width2';
	
	if (isset($gallery_mb->meta['slides'][$counter]['video'])) $provider = (find_oembed($gallery_mb->meta['slides'][$counter]['video']));
	else $provider = '';
	
	if(strpos(trim($provider), ' ') !== false) $provider = 'unknown';
?>
	<div class="progressive element <?php echo $getsize; ?> item<?php echo $counter; ?>">
		<div class="inside">
			<div class="entry-thumb wplus<?php if ($gallery_mb->get_the_value('video')) echo ' video'; ?> <?php if ($provider == 'Twitter') echo 'tweet'; else echo strtolower($provider); ?>" data-overlay="<?php echo '&#xe072;'; ?>">
			<?php 
				$imgID = $gallery_mb->get_the_value('imgurl');
				
				if (is_numeric($imgID)) {
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
					
					$alt = ($gallery_mb->get_the_value('alt')) ? $gallery_mb->get_the_value('alt') : basename($imgurl);
					$image_code = wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full", 'alt' => $alt) );
					$dummy_code = '<div class="dummy" style="margin-top: '.get_dummy_height($image_attributes[1],$image_attributes[2]).'%"></div>';
				} else {
					if (isset($imgID)) {
						$imgurl = $imgID;
						$alt = ($gallery_mb->get_the_value('alt')) ? $gallery_mb->get_the_value('alt') : basename($imgurl);
						$image_code = '<img src="'.$imgID.'" alt="'.$alt.'" />';
						$image_attribute = getimagesize($imgID);
						$dummy_code = '<div class="dummy" style="margin-top: '.get_dummy_height($image_attributes[0],$image_attributes[1]).'%"></div>';
					}
				}
				
				$caption = $gallery_mb->get_the_value('caption'); 
				
				if ($gallery_mb->get_the_value('panning')) $panning = ' data-fresco-options="fit: \'width\'"';
				else $panning = '';
				
				if ($gallery_mb->get_the_value('video')) { 
				
					if ($imgID && ($provider == 'Vimeo' || $provider == 'Youtube')) { 
						
						echo $dummy_code;
					?>
					
					<a href="<?php $gallery_mb->the_value('video'); ?>" class="fresco video pushed<?php echo $disabled; ?>" data-fresco-group='<?php echo( basename(get_permalink()) ); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $thumbs; ?>><?php echo wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full fresco", 'alt' => $alt) ); ?><div class="video"></div></a>
						
					<?php } else {
						$source = $gallery_mb->get_the_value('video');
						$embed_code = wp_oembed_get($source);
						
						if ($embed_code) $embedded = $embed_code;
						else $embedded = do_shortcode($source);
						
						if ($gallery_mb->get_the_value('link')) echo '<a href="'.$gallery_mb->get_the_value('link').'" class="video'.$disabled.'">'.$embedded.'<div class="video link"></div></a>';
						else echo $embedded;
					}
				
				} else { 
				
					if ($gallery_mb->get_the_value('link')) echo '<a href="'.$gallery_mb->get_the_value('link').'" class="video'.$disabled.'">'.$image_code.'<div class="video link"></div></a>';
					else {
					
						echo $dummy_code;
				?>
				
					<a href="<?php echo $imgurl; ?>" class="fresco pushed<?php echo $disabled; ?>" data-fresco-group='<?php echo( basename(get_permalink()) ); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $panning; ?> <?php echo $thumbs; ?>><?php if (is_numeric($imgID)) echo wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full fresco", 'alt' => $alt) ); else echo '<img src="'.$imgID.'" alt="'.$alt.'" />'; ?></a>
				<?php }
				
				} 
					$counter++;
				?>
			</div>
		</div>
	</div>
<?php } ?>