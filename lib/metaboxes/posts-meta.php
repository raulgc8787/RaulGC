<div class="studiofolio_meta_control">
 	
	<p>Insert the code for your media</p>
 
 	<div>
    	<?php $mb->the_field('media'); ?>		        	
		<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
    </div>
 
</div>

<?php global $wpalchemy_media_access; ?>

<div id="undsgn_container" class="studiofolio_meta_control">
	
	<h2>Medias</h2>
	
	<p>Insert your work here adding singe slides. You can also reorder them by simply drag & drop.</p>

    <?php while($mb->have_fields_and_multi('slides')): ?>
    <?php $mb->the_group_open(); ?>
 		
 		<div class="slidebox">
	        <a href="#" class="dodelete button">&#x2715;</a>
	        <div>
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
 	
 	<br>
 	<br>
 	
 	<?php
 	
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
</div>