<?php global $wpalchemy_media_access; 
	
	$layout_page = get_post_meta($post->ID, '_wp_page_template', true);
?>
<div id="undsgn_container" class="studiofolio_meta_control">
<?php

	if ($layout_page == 'templates/gallery.php') { ?>	
 	<h2>Load more</h2>
 	
 	<p>Number of items to show at most</p>
 	
 	<?php $mb->the_field('loadmore'); ?>
 	<input type="text" id="loadmore" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
 	
 	<br>
 	
 	<div id="lightboxsection">
	 	<h2>Lightbox thumbnails</h2>
		<?php $mb->the_field('lightbox_thumbnail'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('lightbox_thumbnail'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('lightbox_thumbnail')) echo ' checked="checked"'; ?> /> Activate the thumbnails on the lightbox (not available with fullscreen slideshow on)</label>
 	</div>
   
    
    <h2>Activate slideshow</h2>
    <?php $mb->the_field('gallery_slidehow'); ?>
    <label for="<?php $mb->the_name(); ?>"><input id="slideshowon" name="<?php $mb->the_name('gallery_slidehow'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('gallery_slidehow')) echo ' checked="checked"'; ?> /> Activate the fullscreen slideshow (this will deactivate 'Load more')</label>
    
    <div id="slidesection">
    <br>
    <p>Aspect ratio (ex. 'Full', '16:9' or '4:3')</p>
    <?php $mb->the_field('sshow_ratio'); ?>
 	<input type="text" id="sshow_ratio" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
 	<p>Minimal height minimal height in pixel (optional)</p>
 	<?php $mb->the_field('sshow_min'); ?>
 	<input type="text" id="sshow_ratio" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
 	<p>Height offset (when fullscreen this is to make it a bit shorter to reveal the section under)</p>
 	<?php $mb->the_field('sshow_offset'); ?>
 	<input type="text" id="sshow_ratio" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
    <?php $mb->the_field('sshow_effect'); ?>
    <label for="<?php $mb->the_name(); ?>"><?php $selected = ' selected="selected"'; ?>
    <select name="<?php $mb->the_name(); ?>">
    <option value="fadeeff"<?php if ($mb->get_the_value() == 'fadeeff') echo $selected; ?>>Fade</option>
    <option value="slideeff"<?php if ($mb->get_the_value() == 'slideeff') echo $selected; ?>>Slide</option>
    </select> Animation</label>
    
    <?php $mb->the_field('sshow_thumbs'); ?>
    <label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('sshow_thumbs'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('sshow_thumbs')) echo ' checked="checked"'; ?> /> Thumbnails</label>
    
    <?php $mb->the_field('sshow_loop'); ?>
    <label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('sshow_loop'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('sshow_loop')) echo ' checked="checked"'; ?> /> Infinite loop</label>
    
    <?php $mb->the_field('sshow_auto'); ?>
    <label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('sshow_auto'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('sshow_auto')) echo ' checked="checked"'; ?> /> Autoplay</label>
    
    </div>
	<br>
	<br>
	
	<?php } ?>
	
	<h2>Medias</h2>
	
	<p>Insert your work here adding singe slides. You can also reorder them by simply drag & drop.</p>

    <?php while($mb->have_fields_and_multi('slides')): ?>
    <?php $mb->the_group_open(); ?>
 		
 		<div class="slidebox">
	        <a href="#" class="dodelete button">&#x2715;</a>
	        <div>
	        	<div class="thumbsize">
	        		<div>Thumb size</div>
	        		<?php $mb->the_field('size'); ?>		        	
					<select name="<?php $mb->the_name(); ?>">				
						<option value="">Select...</option>
						<option value="width1"<?php $mb->the_select_state('width1'); ?>>1</option>
						<option value="width2"<?php $mb->the_select_state('width2'); ?>>2</option>
						<option value="width4"<?php $mb->the_select_state('width4'); ?>>4</option>
						<option value="width6"<?php $mb->the_select_state('width6'); ?>>6</option>
					</select>
					<?php $mb->the_field('panning'); ?>
						<label for="<?php $mb->the_name(); ?>"><input name="<?php $metabox->the_name('panning'); ?>" type="checkbox" value="1" <?php if ($metabox->get_the_value('panning')) echo ' checked="checked"'; ?> /> Panning</label>
	        	</div>
	        	<?php $mb->the_field('imgurl'); ?>
	        	<?php $wpalchemy_media_access->setGroupName('img-n'. $mb->get_the_index())->setInsertButtonLabel('Insert'); ?>
	        	<div class="pull-left">
	        		<div class="labelel">Image</div>
		        	<div class="thumbcontainer">	
			        	<p class="addbutton">	
			            	<?php echo $wpalchemy_media_access->getButton(array('label' => '+')); ?>
			            	<?php  
			            		if (ctype_digit($mb->get_the_value())) {
			            			$thumburl = wp_get_attachment_image_src( $mb->get_the_value(), 'thumbnail' );
			            			echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value(), 'data' => $thumburl[0] )); 
			            		} else {
				            		echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value(), 'data' => $mb->get_the_value() ));
			            		}
			            	?>
			        	</p>
			        	<div class="removeimg"></div>
			        </div>
	        	</div>
		        <div class="pull-left">	
		        	<div class="orsep">or</div>
		        	<?php $mb->the_field('video'); ?>
		        	<div class="pull-left">
		        		<div class="labelel">Other media</div>
		        		<div class="codecontainer">
		        			<div class="button codein">
			        			<span><?php if ($mb->get_the_value()) echo str_replace('_', ' ', find_oembed($mb->get_the_value())); else echo 'Paste your URL, shortcode or custom code here'; ?></span>
		        			</div>
		        			<textarea class="vframe video" name="<?php $mb->the_name(); ?>"><?php $mb->the_value(); ?></textarea>
		        		</div>
		        	</div>
		        	<div class="pull-left">
		        		<div class="pre"><i class="info">i</i><div>You can paste here the link to the video from vimeo or youtube <br>Ex. http://vimeo.com/xxxxxxxx</div></div>
		        	</div>
		        </div>
		        <div class="caption">
		        <?php $mb->the_field('caption'); ?>
		        	<div>Caption</div>
		        	<textarea class="vframe" name="<?php $mb->the_name(); ?>"><?php $mb->the_value(); ?></textarea>
		        </div>
		        <div class="slidelink">
		        	<div>Link</div>
		        	<?php $mb->the_field('link'); ?>
		        	<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		        	<div>Alt tags</div>
		        	<?php $mb->the_field('alt'); ?>
		        	<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		        </div>
		        <div style="clear: both"></div>
	        </div>
        </div>
 
    <?php $mb->the_group_close(); ?>
    <?php endwhile; ?>
 
    <p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-slides button">Add slide</a>
    <?php 
	$version = get_bloginfo('version');
	if ($version >= 3.5) { ?>
		 <a href="#" class="upload_image_button button" style="margin-left: 10px;">Add multiple images</a></p>
	<?php } ?>    
 	
 	<p><a href="#" class="dodelete-slides button">Remove All</a></p>
 	
 	<script type="text/javascript">
	//<![CDATA[
		jQuery(function($) {
			$('#wpa_loop-slides').sortable();
			
			if ($('#slideshowon').attr('checked')) {
				$('#lightboxsection').hide();
				$('#slidesection').show();
			} else {
				$('#lightboxsection').show();
				$('#slidesection').hide();
			}
			
			$('#slideshowon').bind("change", function() {
			      $('#slidesection').slideToggle();
			      $('#lightboxsection').slideToggle();
			});
			
		});
	//]]>
	</script>
    <br>
    <?php $mb->the_field('randomize'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('randomize'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('randomize')) echo ' checked="checked"'; ?> /> Randomize the array in every visit.</label>
	<?php $mb->the_field('disable_lightbox'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_lightbox'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_lightbox')) echo ' checked="checked"'; ?> /> Disable the lightbox on click</label>
	<?php $mb->the_field('disable_zoom'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_zoom'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_zoom')) echo ' checked="checked"'; ?> /> Disable the zoom effect</label>
	<?php $mb->the_field('disable_overlay'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_overlay'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_overlay')) echo ' checked="checked"'; ?> /> Disable the overlay effect</label>
    <br>
	
	<?php 
	if ($layout_page == 'templates/page-center.php' || $layout_page == 'templates/page-center-full.php' || $layout_page == 'templates/page-sidebar.php' || $layout_page == 'templates/page-sidebar-fixed.php') {
		$mb->the_field('yesslide'); ?>
		<label for="<?php $mb->the_name(); ?>"><input id="slideshowon" name="<?php $metabox->the_name('yesslide'); ?>" type="checkbox" value="1" <?php if ($metabox->get_the_value('yesslide')) echo ' checked="checked"'; ?> /> Use the slideshow</label>
		
		<div id="slidesection">
		    <br>
		    <?php $mb->the_field('sshow_effect'); ?>
		    <label for="<?php $mb->the_name(); ?>"><?php $selected = ' selected="selected"'; ?>
		    <select name="<?php $mb->the_name(); ?>">
		    <option value="fadeeff" <?php if ($mb->get_the_value() == 'fadeeff') echo $selected; ?>>Fade</option>
		    <option value="slideeff" <?php if ($mb->get_the_value() == 'slideeff') echo $selected; ?>>Slide</option>
		    </select> Animation</label>
		    
		    <?php $mb->the_field('sshow_thumbs'); ?>
		    <label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('sshow_thumbs'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('sshow_thumbs')) echo ' checked="checked"'; ?> /> Thumbnails</label>
		    
		    <?php $mb->the_field('sshow_loop'); ?>
		    <label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('sshow_loop'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('sshow_loop')) echo ' checked="checked"'; ?> /> Infinite loop</label>
		    
		    <?php $mb->the_field('sshow_auto'); ?>
		    <label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('sshow_auto'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('sshow_auto')) echo ' checked="checked"'; ?> /> Autoplay</label>
		    
		</div>	
		
		<br>
		
	<?php } ?>
</div>