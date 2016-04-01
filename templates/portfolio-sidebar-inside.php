<?php

	global $portfolio_mb, $wp_embed;

	if ($portfolio_mb->get_the_value('randomize')) shuffle($portfolio_mb->meta['slides']);
	
	$portfolio_page = $portfolio_mb->get_the_value('portfolio_page');
	$slider = $portfolio_mb->get_the_value('yesslide');
	$counter = 0;
?>

<div class="img-cont<?php if ($portfolio_mb->get_the_value('video')) echo ' video'; ?>">
<?php
	if ($slider) { ?>	
		<div class="gallery_element">
			<div class="flexslider<?php if ($portfolio_mb->get_the_value('sshow_effect')) echo ' ' . $portfolio_mb->get_the_value('sshow_effect'); ?><?php if ($portfolio_mb->get_the_value('sshow_thumbs')) echo ' wthumbs' ?><?php if ($portfolio_mb->get_the_value('sshow_loop')) echo ' loop'; ?><?php if ($portfolio_mb->get_the_value('sshow_auto')) echo ' autoplay'; ?>" data-aspect="<?php if ($portfolio_mb->get_the_value('sshow_ratio')) echo $portfolio_mb->get_the_value('sshow_ratio'); ?>" data-mheight="<?php if ($portfolio_mb->get_the_value('sshow_min')) echo $portfolio_mb->get_the_value('sshow_min'); ?>" data-offset="<?php if ($portfolio_mb->get_the_value('sshow_offset')) echo $portfolio_mb->get_the_value('sshow_offset'); ?>">
				<ul class="slides">
		<?php }
	// loop a set of fields
	while($portfolio_mb->have_fields('slides')) { ?>
		<?php 
			$imgID = $portfolio_mb->get_the_value('imgurl');
			
			if (is_numeric($imgID)) {
				$image_attributes = wp_get_attachment_image_src( $imgID, 'full' ); 
				$image_attributes = $image_attributes[0];
				$alt = ($portfolio_mb->get_the_value('alt')) ? $portfolio_mb->get_the_value('alt') : basename($image_attributes);
			} else {
				$image_attributes = $imgID;
				$alt = ($portfolio_mb->get_the_value('alt')) ? $portfolio_mb->get_the_value('alt') : basename($image_attributes);
			}

			$caption = $portfolio_mb->get_the_value('caption'); 
			if (isset($portfolio_mb->meta['slides'][$counter]['video'])) $provider = (find_oembed($portfolio_mb->meta['slides'][$counter]['video']));
			else $provider = '';
			if(strpos(trim($provider), ' ') !== false) $provider = 'unknown';
		
			if ($slider) echo '<li data-thumb="'.wp_get_attachment_thumb_url( $imgID).'">';
			if ($portfolio_mb->get_the_value('link')) { ?>
				
				<a href="<?php echo $portfolio_mb->get_the_value('link'); ?>" class="video">
				
			<?php }
			if ($portfolio_mb->get_the_value('video')) {
				if ($imgID && ($provider == 'Vimeo' || $provider == 'Youtube')) { ?>
				<a href="<?php $portfolio_mb->the_value('video'); ?>" class="fresco video <?php if (!$slider || ($slider && $counter == 0)) echo 'progressive'; ?>" data-fresco-group='<?php echo( basename(get_permalink()) ); ?>'><img alt="<?php echo $alt; ?>" src="<?php echo $image_attributes; ?>" /><div class="video"></div></a>
				
			<?php } else { ?>
			<div class="media <?php if ($provider == 'Twitter') echo 'tweet'; else echo strtolower($provider); ?>"><?php
				$source = $portfolio_mb->get_the_value('video');
				$embed_code = wp_oembed_get($source);
				
				if ($embed_code) echo $embed_code;
				else echo do_shortcode($source);
			?></div>	
			<?php } 
			} else { ?>
				<img alt="<?php echo $alt; ?>" src="<?php echo $image_attributes; ?>" class="<?php if (!$slider || ($slider && $counter == 0)) echo 'progressive'; ?>" />
  <?php 	}
  			if ($portfolio_mb->get_the_value('caption')) echo '<p class="flex-caption">'.$portfolio_mb->get_the_value('caption').'</p>';
  			if ($portfolio_mb->get_the_value('link')) { ?>
				
				<div class="video link"></div>
				</a>	
				
			<?php } 
  			if ($slider) echo '</li>';
	  		$counter++;
		?>
	<?php } 
	if ($slider) echo '</ul></div></div>'; ?>
</div>