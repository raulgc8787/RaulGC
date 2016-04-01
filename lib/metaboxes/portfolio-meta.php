<?php 

	global $wpalchemy_media_access, $post;
	
	$layout_page = get_post_meta($post->ID, '_post_template', true);

?>
<div id="undsgn_container" class="studiofolio_meta_control">
 	
 	<h2>Portfolio page and main thumbnail size.</h2>
 	
 	<p>Assign this portfolio item to a portfolio page.</p>
 	
 	<?php $mb->the_field('portfolio_page'); ?>
 	<?php $selected = ' selected="selected"'; ?>
 	<div class="select_wrapper ">
		<select name="<?php $mb->the_name(); ?>">
			<?php echo '<option value="default">Not assigned</option>'; ?>
		<?php 
		$args = array(
		    'post_status' => 'publish',
		    'post_type' => 'page',
		    'order' => 'ASC',
		    'orderby' => 'title',
		    'posts_per_page' => -1,
		    'meta_query' => array(
		    	'relation' => 'OR',
		        array(
		            'key' => '_wp_page_template',
		            'compare' => '=',
		            'value' => 'templates/portfolio.php'
		        )    
		    )
		);
		$pages = new WP_Query($args);
		// The Loop
		while ( $pages->have_posts() ) : $pages->the_post();
			echo '<option value="'. get_the_ID() .'"'; $thevalue = get_the_ID(); if ($mb->get_the_value() == "$thevalue") echo $selected; ?> 
			<?php echo '>' . get_the_title() .'</option>';
		endwhile;
		// Reset Post Data
		wp_reset_postdata();
		?> 
		</select>
 	</div>
 	<br>
 	
 	<p>Choose the size of the page thumbnail</p>
 
 	<div>
    	<?php $mb->the_field('size'); ?>		        	
		<select name="<?php $mb->the_name(); ?>">				
			<option value="">Select...</option>
			<option value="width1"<?php $mb->the_select_state('width1'); ?>>1</option>
			<option value="width2"<?php $mb->the_select_state('width2'); ?>>2</option>
			<option value="width4"<?php $mb->the_select_state('width4'); ?>>4</option>
			<option value="width6"<?php $mb->the_select_state('width6'); ?>>6</option>
		</select>
	<?php $mb->the_field('disable_block'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_block'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_block')) echo ' checked="checked"'; ?> /> Disable the block on click</label>
	<?php $mb->the_field('disable_zoom_'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_zoom'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_zoom')) echo ' checked="checked"'; ?> /> Disable the zoom effect</label>
	<?php $mb->the_field('disable_overlay_'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_overlay'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_overlay')) echo ' checked="checked"'; ?> /> Disable the overlay effect</label>
    </div>
    
	<br>
	
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
		});
	//]]>
	</script>
	
	<br>
	<?php $mb->the_field('randomize'); ?>
	<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('randomize'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('randomize')) echo ' checked="checked"'; ?> /> Randomize the array in every visit.</label>
	
	<?php 
	if ($layout_page == 'templates/portfolio-center.php' || $layout_page == 'templates/portfolio-center-full.php' || $layout_page == 'templates/portfolio-sidebar.php' || $layout_page == 'templates/portfolio-sidebar-fixed.php') {
		$mb->the_field('yesslide'); ?>
		<label for="<?php $mb->the_name(); ?>"><input id="slideshowon" name="<?php $metabox->the_name('yesslide'); ?>" type="checkbox" value="1" <?php if ($metabox->get_the_value('yesslide')) echo ' checked="checked"'; ?> /> Use the slideshow</label>
		
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
		
	<?php } else { ?>
		<?php $mb->the_field('lightbox_thumbnail'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('lightbox_thumbnail'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('lightbox_thumbnail')) echo ' checked="checked"'; ?> /> Activate the thumbnails on the lightbox</label>
		<?php $mb->the_field('disable_lightbox'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_lightbox'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_lightbox')) echo ' checked="checked"'; ?> /> Disable the lightbox on click</label>
		<?php $mb->the_field('disable_zoom'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_zoom'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_zoom')) echo ' checked="checked"'; ?> /> Disable the zoom effect</label>
		<?php $mb->the_field('disable_overlay'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_overlay'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_overlay')) echo ' checked="checked"'; ?> /> Disable the overlay effect</label>
		<br>
	<?php }
		
	$data = get_option(OPTIONS);
	
	if (isset($data['pf_details']) && $data['pf_details']) { 
		
		$details = $data['pf_details'];
	?>
	
	<h2>Portfolio details</h2>
	
	<div>
	<?php 
		
		
		foreach($details as $detail) {
			$mb->the_field('detail_'.$detail["title"]); ?>
			<label><?php echo ucfirst($detail['title']); ?>:</label>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
	<?php } ?>
    </div>
    <?php } ?>
    <br>
    <script type="text/javascript">
	//<![CDATA[
		jQuery(function($) {
			
			if ($('#slideshowon').attr('checked')) {
				$('#slidesection').show();
			} else {
				$('#slidesection').hide();
			}
			
			$('#slideshowon').bind("change", function() {
			      $('#slidesection').slideToggle();
			});
			
			
		});
	//]]>
	</script>
</div>