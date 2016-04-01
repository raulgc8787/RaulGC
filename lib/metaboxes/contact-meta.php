<div class="studiofolio_meta_control">
 	
 	<p>Write your info text for contact as HTML text.</p>
    
    <?php 
    	$mb->the_field('contact_info'); 
	    wp_editor(html_entity_decode($mb->get_the_value()), "editor_" . rand(1, 200), array("textarea_rows" => 10, "textarea_name" => $mb->get_the_name(), "editor_class" => "custom_editor"));
	    
	    $data = get_option(OPTIONS);
	    if (isset($data['contact_details']) && $data['contact_details']) { 
    ?>
    
    <h2>Contact details</h2>
    
	<?php
		
		$details = $data['contact_details'];
		foreach($details as $detail) {
			$mb->the_field('detail_'.$detail["title"]); ?>
			<div style="margin: 10px 0px">
				<div><?php echo ucfirst($detail['title']); ?>:</div>
				<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" style="clear: right;" />
			</div>
	<?php } ?>
	<?php 
    	$mb->the_field('topdetails'); ?>
		<label style="margin-top: 10px"><input name="<?php $mb->the_name('topdetails'); ?>" type="checkbox" value="1" <?php if ($mb->get_the_value('topdetails')) echo ' checked="checked"'; ?> /> Position details on top</label>
	<br>
	<?php } ?>
	<h2>Google Maps</h2>
	
	<p>Aspect ratio:</p>
	<div class="aspect">
	<?php $mb->the_field('asw'); ?>
	<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="Width" /> : <?php $mb->the_field('ash'); ?><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="Height" />
	</div>
	
	<p>Coordinates:</p>
	<div class="coordinates">
	<?php $mb->the_field('lat'); ?>
	<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="Latitude" /> : <?php $mb->the_field('lon'); ?><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="Longitude" />
	</div>
	
	<br>
</div>