<?php
/*
Template Name: Contact Centered
*/
?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); 
	global $contact_mb;
	if ($contact_mb->get_the_value('asw') != '' && $contact_mb->get_the_value('ash') != '' && $contact_mb->get_the_value('lat') != '' && $contact_mb->get_the_value('lon')) { ?>
	<div id="map" class="map"></div>
	<?php } ?>
	<div class="entry-cont">
		<div class="span12">
			<h1 class="portfolio-title"><?php the_title(); ?></h1>
			<div class="span8 side-left-cont">
				<div class="portfolio-entry">
					<?php
					global $contact_mb;
					$contact_info = $contact_mb->get_the_value('contact_info'); 
			    	if ($contact_mb->get_the_value('topdetails')) echo $contact_info;
				    ?>
				    <?php the_content(); ?>
				    <?php
				    	if (!$contact_mb->get_the_value('topdetails')) echo $contact_info;
				    ?>
				</div>
			</div>
			<div class="span4 side-right-cont progressive">
				<?php
					
		        $prdetails = '';
		        if (isset($data['contact_details']) && $data['contact_details']) { 
			        $details = $data['contact_details'];
			        foreach($details as $detail) { 
			            if ($contact_mb->get_the_value('detail_'.$detail["title"])) { 
			            echo '<span class="p-info"><b class="p-info-meta">'.ucfirst($detail['title']).':</b> '.ucfirst($contact_mb->get_the_value('detail_'.$detail["title"])).'</span>'; 
			            }
			        }
		        }
				?>			</div>
		</div>
	</div>	
	
<?php endwhile; /* End loop */ ?>