<div class="studiofolio_meta_control">
 	<?php global $post; ?>
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
		<?php 
			if (isset($post->page_template) && $post->page_template == 'templates/gallery.php') {
			$mb->the_field('slidethumb'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('slidethumb'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('slidethumb')) echo ' checked="checked"'; ?> /> Slide thumbs in frontpage</label>
		<?php } ?>
		<?php $mb->the_field('disable_block'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_block'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_block')) echo ' checked="checked"'; ?> /> Disable the block on click</label>
		<?php $mb->the_field('disable_zoom'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_zoom'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_zoom')) echo ' checked="checked"'; ?> /> Disable the zoom effect</label>
		<?php $mb->the_field('disable_overlay'); ?>
		<label for="<?php $mb->the_name(); ?>"><input name="<?php $mb->the_name('disable_overlay'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('disable_overlay')) echo ' checked="checked"'; ?> /> Disable the overlay effect</label>
   </div>
 
</div>