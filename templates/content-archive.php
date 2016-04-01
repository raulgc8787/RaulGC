<?php if (!have_posts()) : ?>
  <div class="alert alert-block fade in">
    <a class="close" data-dismiss="alert">&times;</a>
    <p><?php _e('Sorry, no results were found.', 'studiofolio'); ?></p>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

  <div class="container-isotope">
	<div id="isotope" class="isotope">
<?php	
	global $data; //fetch options stored in $data
	$paged = (get_query_var('page')) ? get_query_var('page') : 1;
	while (have_posts()) : the_post(); 
	global $more;
	global $pages_mb;
	$more = 0;
	$format = get_post_format(get_the_ID());
	$quote = ( $format == 'quote' ) ? true : false;
	$video = ( $format == 'video' ) ? true : false;
	$audio = ( $format == 'audio' ) ? true : false;
	
    $titleON = $data['blog_title'];
    $contentON = $data['blog_content'];
    $thumbON = $data['blog_thumb'];
    $dateON = $data['blog_date'];
	
	$sizemeta = get_post_meta(get_the_ID(), '_studiofolio_pages_meta', true);
	$size = (is_array($sizemeta)) ? $sizemeta['size'] : 'width2';
?>
	<div class="progressive element<?php echo ' ' . $size; ?><?php if ($quote) echo ' tweet'; ?>">
		<div class="inside">
		<?php if ((has_post_thumbnail() && $thumbON && !$quote) || $audio) { ?>
	    			<div class="entry-thumb<?php if ($titleON) echo ' wplus'; ?>" data-overlay="<?php if ($titleON) echo '&#xe072;'; else echo get_the_title(); ?>" data-area="<?php echo ucfirst($format); ?>">
	    				<?php if ($audio) { ?>
	    					<div class="audio">
		    				<?php 
		    					$audiocode = get_post_meta(get_the_ID(), '_studiofolio_post_meta', true);
		    					echo do_shortcode($audiocode['media']); ?>
	    					</div>
	    				<?php } else { 
		    				$image_attributes = wp_get_attachment_image_src( get_the_ID(), get_image_size($size) );
		    				$imgurl = $image_attributes[0];
								$urlquery = parse_url($imgurl);
								if (isset($urlquery['query'])) {
									parse_str($urlquery['query'], $output);
									if (isset($output['resize'])) {
										$imgsize = explode(",", $output['resize']);
										$image_attributes[1] = $imgsize[0];
										$image_attributes[2] = $imgsize[1];
									}
								}
	    				?>
	    				<div class="dummy" style="margin-top: <?php echo get_dummy_height($image_attributes[1],$image_attributes[2]); ?>%"></div>
	    				<a href="<?php the_permalink(); ?>" class="video pushed"><?php the_post_thumbnail(get_image_size($size)); ?>
	    				<?php 
		    				if ($video) echo '<div class="video"></div>';
	    				?></a>
	    				<?php } ?>
	    			</div>
	    		<?php } ?>
		<?php if ( $titleON || $contentON) { ?>
			<div class="entry-text-cont">
				<?php if ($titleON && !$quote) { ?>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php } ?>
				<?php if ($contentON) { 
    			if ($dateON && !$quote) { ?>
	    		<div class="entry-meta">
	    			<?php echo get_the_time( "F d, Y" ); ?> 
	    		</div>	
    			<?php } ?>
    			<div class="entry-text">
				<?php 
					if ( ! has_excerpt() ) the_content();
					else the_excerpt();
				?>
				</div>
				<?php } ?>
			</div>
		<?php } ?>
		</div>
	</div>
<?php endwhile; ?>
	</div>
  </div>
<?php if ($wp_query->max_num_pages > 1) : ?>
  <div class="load-more inside hvr progressive">
   	<div class="entry-text-cont">
   		<?php get_next_posts_link(); ?>
  		<a href="#" data-pages="<?php echo $wp_query->max_num_pages; ?>" data-page="<?php echo $paged; ?>" data-link="<?php echo next_posts($wp_query->max_num_pages, false); ?>">More Items...</a>
   	</div>
  </div>
<?php endif; ?>
