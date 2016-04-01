<?php

global $data;
if ((isset($data['twitter_url']) && $data['twitter_url']) || (isset($data['in_url']) && $data['in_url']) || (isset($data['facebook_url']) && $data['facebook_url']) || (isset($data['google_url']) && $data['google_url']) || (isset($data['pinterest_url']) && $data['pinterest_url']) || (isset($data['github_url']) && $data['github_url']) || (isset($data['flicker_url']) && $data['flicker_url']) || (isset($data['tumblr_url']) && $data['tumblr_url']) || (isset($data['dribbble_url']) && $data['dribbble_url']) || (isset($data['soundcloud_url']) && $data['soundcloud_url']) || (isset($data['behance_url']) && $data['behance_url']) || (isset($data['lastfm_url']) && $data['lastfm_url']) || (isset($data['instagram_url']) && $data['instagram_url']) || (isset($data['vimeo_url']) && $data['vimeo_url'])) {
?>
<div class="social-cont">
	<ul class="nav social-menu">
	    <?php if ($data['facebook_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0e9;" target="_blank" href="<?php echo $data['facebook_url']; ?>"></a></li><?php } ?><?php if ($data['google_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0ec;" target="_blank" href="<?php echo $data['google_url']; ?>"></a></li><?php } ?><?php if ($data['twitter_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0e7;" target="_blank" href="<?php echo $data['twitter_url']; ?>"></a></li><?php } ?><?php if ($data['in_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0f2;" target="_blank" href="<?php echo $data['in_url']; ?>"></a></li><?php } ?><?php if ($data['pinterest_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0ee;" target="_blank" href="<?php echo $data['pinterest_url']; ?>"></a></li><?php } ?><?php if ($data['github_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0e1;" target="_blank" href="<?php echo $data['github_url']; ?>"></a></li><?php } ?><?php if ($data['flicker_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0e3;" target="_blank" href="<?php echo $data['flicker_url']; ?>"></a></li><?php } ?><?php if ($data['tumblr_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0f0;" target="_blank" href="<?php echo $data['tumblr_url']; ?>"></a></li><?php } ?><?php if ($data['dribbble_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0f4;" target="_blank" href="<?php echo $data['dribbble_url']; ?>"></a></li><?php } ?><?php if ($data['soundcloud_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe108;" target="_blank" href="<?php echo $data['soundcloud_url']; ?>"></a></li><?php } ?><?php if ($data['behance_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe10a;" target="_blank" href="<?php echo $data['behance_url']; ?>"></a></li><?php } ?><?php if ($data['lastfm_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0f8;" target="_blank" href="<?php echo $data['lastfm_url']; ?>"></a></li><?php } ?><?php if ($data['instagram_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0fe;" target="_blank" href="<?php echo $data['instagram_url']; ?>"></a></li><?php } ?><?php if ($data['vimeo_url']) { ?>
	
	    <li><a class="glyph hvr" data-icon="&#xe0e5;" target="_blank" href="<?php echo $data['vimeo_url']; ?>"></a></li><?php } ?>
	
	</ul>
</div><?php } ?>