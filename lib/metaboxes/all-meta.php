<?php global $wpalchemy_media_access; ?>
<div class="studiofolio_meta_control">
 	
 	<p>Add an image as background for the section or choose a background color.</p>
 	
 	<?php $mb->the_field('bgurl'); ?>
    <?php $wpalchemy_media_access->setGroupName('bg')->setInsertButtonLabel('Insert'); ?>
 
    <div class="pull-left" style="width: 100%">
        <?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value(), 'class' => 'bgimage')); ?>
        <?php echo $wpalchemy_media_access->getButton(array('label' => 'Add background image')); ?>
        <?php $mb->the_field('alignh'); ?>		        	
		<select name="<?php $mb->the_name(); ?>" style="margin-left: 10px">				
			<option value="">Horizontal align...</option>
			<option value="left"<?php $mb->the_select_state('left'); ?>>Left</option>
			<option value="center"<?php $mb->the_select_state('center'); ?>>Center</option>
			<option value="right"<?php $mb->the_select_state('right'); ?>>Right</option>
		</select>
		<?php $mb->the_field('alignv'); ?>		        	
		<select name="<?php $mb->the_name(); ?>" style="margin-left: 10px">				
			<option value="">Vertical align...</option>
			<option value="top"<?php $mb->the_select_state('top'); ?>>Top</option>
			<option value="center"<?php $mb->the_select_state('center'); ?>>Center</option>
			<option value="bottom"<?php $mb->the_select_state('bottom'); ?>>Bottom</option>
		</select>
		<?php $mb->the_field('repeat'); ?>		        	
		<select name="<?php $mb->the_name(); ?>" style="margin-left: 10px">				
			<option value="">Repeat...</option>
			<option value="repeat"<?php $mb->the_select_state('repeat'); ?>>Repeat-xy</option>
			<option value="repeat-x"<?php $mb->the_select_state('repeat-x'); ?>>Repeat-x</option>
			<option value="repeat-y"<?php $mb->the_select_state('repeat-y'); ?>>Repeat-y</option>
			<option value="no-repeat"<?php $mb->the_select_state('no-repeat'); ?>>No-repeat</option>
		</select>
		<?php $mb->the_field('cover'); ?>
		<label style="display: inline; padding-left: 10px" for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('cover'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('cover')) echo ' checked="checked"'; ?> /> Fullscreen</label>
    </div>
    <?php $mb->the_field('bgcolor'); ?>
    <div class="pull-left" style="clear: both ">
    	<p style="padding-right: 20px;">or</p><input type="text" id="color" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" /> Pick background color
    	<div id="colorpicker"></div>
    </div>
    
    <div style="clear:both;"></div>
    <script type="text/javascript">
 
	  jQuery(document).ready(function() {
	    jQuery('#colorpicker').hide();
	    jQuery('#colorpicker').farbtastic("#color");
	    jQuery("#color").click(function(){
	    	if (jQuery("#color").val() == '') jQuery("#color").val('#000000');
	    	jQuery('#colorpicker').slideToggle()
	    });
	  });
	 
	</script>
 
</div>