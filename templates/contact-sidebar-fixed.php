<?php
/*
Template Name: Contact Sidebar Fixed
*/
?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); 
	global $contact_mb;
	if ($contact_mb->get_the_value('asw') != '' && $contact_mb->get_the_value('ash') != '' && $contact_mb->get_the_value('lat') != '' && $contact_mb->get_the_value('lon')) { ?>
	<div id="map" class="map"></div>
	<?php } 
	if (get_the_content() != '') { ?>
	<div class="entry-cont">
		<?php the_content(); ?>	
	</div>	
	<?php } ?>
<?php endwhile; /* End loop */ ?>