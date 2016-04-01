<div class="container-isotope">
	<div id="isotope" class="isotope">
	<?php
				
	global $portfolio_mb;

	if ($portfolio_mb->get_the_value('randomize')) shuffle($portfolio_mb->meta['slides']);
	
	if ($portfolio_mb->get_the_value('lightbox_thumbnail')) $thumbs = ' data-fresco-group-options="thumbnails:true"';
	else $thumbs = ' data-fresco-group-options="thumbnails:false"';

	$disabled = '';
	if ($portfolio_mb->get_the_value('disable_zoom')) $disabled .= ' zoom_disable';
	if ($portfolio_mb->get_the_value('disable_overlay')) $disabled .= ' overlay_disable';
	if ($portfolio_mb->get_the_value('disable_lightbox')) $disabled = ' click_disable zoom_disable overlay_disable';

	$counter = 0;
	// loop a set of fields
	while($portfolio_mb->have_fields('slides')) { 
		$getsize = $portfolio_mb->get_the_value('size'); 
		if (isset($portfolio_mb->meta['slides'][$counter]['video'])) $provider = (find_oembed($portfolio_mb->meta['slides'][$counter]['video']));
		else $provider = '';
		if(strpos(trim($provider), ' ') !== false) $provider = 'unknown';
		if (!$getsize) $getsize = 'width2';
	?>
		<div class="progressive element <?php echo $getsize; ?> item<?php echo $counter; ?>">
			<div class="inside">
				<div class="entry-thumb wplus<?php if ($portfolio_mb->get_the_value('video')) echo ' video'; ?> <?php if ($provider == 'Twitter') echo 'tweet'; else echo strtolower($provider); ?>" data-overlay="<?php echo '&#xe072;'; ?>">
				<?php 
					$imgID = $portfolio_mb->get_the_value('imgurl');
					
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
						$alt = ($portfolio_mb->get_the_value('alt')) ? $portfolio_mb->get_the_value('alt') : basename($imgurl);
						$image_code = wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full", 'alt' => $alt) );
						$dummy_code = '<div class="dummy" style="margin-top: '.get_dummy_height($image_attributes[1],$image_attributes[2]).'%"></div>';
					} else {
						if (isset($imgID)) {
							$imgurl = $imgID;
							$alt = ($portfolio_mb->get_the_value('alt')) ? $portfolio_mb->get_the_value('alt') : basename($imgurl);
							$image_code = '<img src="'.$imgID.'" alt="'.$alt.'" />';
							$image_attributes = getimagesize($imgID);
							$dummy_code = '<div class="dummy" style="margin-top: '.get_dummy_height($image_attributes[0],$image_attributes[1]).'%"></div>';
						}
					}

					$caption = $portfolio_mb->get_the_value('caption'); 
					
					if ($portfolio_mb->get_the_value('panning')) $panning = ' data-fresco-options="fit: \'width\'"';
					else $panning = '';
						
					if ($portfolio_mb->get_the_value('video')) { 
					
						if ($imgID && ($provider == 'Vimeo' || $provider == 'Youtube')) { 
							
							echo $dummy_code;
						?>
						
						<a href="<?php $portfolio_mb->the_value('video'); ?>" class="fresco video pushed<?php echo $disabled; ?>" data-fresco-group='<?php echo( basename(get_permalink()) ); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $thumbs; ?>><?php echo wp_get_attachment_image( $imgID, get_image_size($getsize), 0, array('class' => "attachment-full fresco", 'alt' => $alt) ); ?><div class="video"></div></a>
							
						<?php } else {
							$source = $portfolio_mb->get_the_value('video');
							$embed_code = wp_oembed_get($source);
							
							if ($embed_code) $embedded = $embed_code;
							else $embedded = do_shortcode($source);
							
							if ($portfolio_mb->get_the_value('link')) echo '<a href="'.$portfolio_mb->get_the_value('link').'" class="video'.$disabled.'">'.$embedded.'<div class="video link"></div></a>';
							else echo $embedded;
						}
					
					} else { 
					
						if ($portfolio_mb->get_the_value('link')) echo $dummy_code . '<a href="'.$portfolio_mb->get_the_value('link').'" class="video pushed'.$disabled.'">'.$image_code.'<div class="video link"></div></a>';
						else {
						
							echo $dummy_code;
				?>
				
							<a href="<?php echo $imgurl; ?>" class="fresco pushed<?php echo $disabled; ?>" data-fresco-group='<?php echo( basename(get_permalink()) ); ?>' data-fresco-caption="<?php echo $caption; ?>"<?php echo $panning; ?> <?php echo $thumbs; ?>><?php echo $image_code; ?></a>
				
					<?php }
						
					}
					
					$counter++;
					?>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
</div>