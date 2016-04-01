<?php
/*
Template Name: Front Page
*/
?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post();

	global $data, $itemArray; //fetch options stored in $data
	
	$load_more = (isset($data['front_load_more'.$addId]) && $data['front_load_more'.$addId] != '') ? $data['front_load_more'.$addId] : -1;
	if (is_front_page()) $paged = (get_query_var('page')) ? get_query_var('page') : 1;
	else $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$items = array();
	
	// Query for items
	if ($data['automatic'.$addId]) {
		$cinArray = explode(",", trim($data['category_inclusion'.$addId]));
		$tinArray = explode(",", trim($data['tag_inclusion'.$addId]));
		$itemArray = explode(",", trim($data['post_exclusion'.$addId]));
		$catArray = explode(",", trim($data['category_exclusion'.$addId]));
		$itemArray[] = get_option('page_on_front');
		$itemArray[] = get_option('page_for_posts');
		if (isset($data['slideshow_on'.$addId]) && $data['slideshow_on'.$addId] && isset($data['slideshow'.$addId]) && $data['slideshow'.$addId]) $itemArray[] = $data['slideshow'.$addId];
		$args = array(
			'post_type' => array( 'post', 'portfolio', 'page' ),
			'category__in' => $cinArray,
			'tag__in' => $tinArray,
			'post__not_in' => $itemArray,
			'category__not_in' => $catArray,
			'posts_per_page' => $load_more,
			'post_status'=>'publish',
			'order' => 'DESC',
			'orderby' => 'publish',
			'paged' => $paged
		);

		if ($tinArray[0] == '') unset($args['tag__in']);
		if ($cinArray[0] == '') unset($args['category__in']);		
		
	} else {
	
		
		if (isset($data['frontpage'.$addId])) $items = $data['frontpage'.$addId]; //get the slides array 
	
		$itemArray = array();
		if (is_array($items))
		{
			foreach($items as $item) {
				$itemArray[] = $item['ID'];
			}
		}
		$args = array(
			'post_type' => array( 'post', 'portfolio', 'page' ),
			'post__in' => $itemArray,
			'post_status'=>'publish',
			'orderby' => 'DESC',
			'posts_per_page' => -1
		);
	}
	
	if (count($items) || $data['automatic'.$addId]) {
			
		$mposts = new WP_Query( $args );
	
	
    ?>
    <div class="container-isotope">
	    <div id="isotope">
	    <?php 
	    
	   	/**
		 * Order an array of objects by object property
		 */
		function orderby( $a, $b ) {
		    global $itemArray;
		    $apos   = array_search( $a->ID, $itemArray );
		    $bpos   = array_search( $b->ID, $itemArray );
		    return ( $apos < $bpos ) ? -1 : 1;
		}
		//$itemArray = array_slice($itemArray, ($paged - 1) * get_option('posts_per_page'), $paged * get_option('posts_per_page'));
		
	    if (!$data['automatic'.$addId]) {
	    	$total_posts = count($mposts->posts);
	    	usort( $mposts->posts, "orderby" );
	    	if ($load_more > 0) $mposts->posts = array_slice($mposts->posts, ($paged - 1) * $load_more, $load_more); 	
	    } else $total_posts = $mposts->found_posts;
	   
	    
	    foreach( $mposts->posts as $post ) : setup_postdata($post);  
	    	global $more, $pages_mb, $gallery_mb, $contact_mb;
	    	$more = 0;
	    	$format = get_post_format(get_the_ID());
			$quote = ( $format == 'quote' ) ? true : false;
			$video = ( $format == 'video' ) ? true : false;
			$audio = ( $format == 'audio' ) ? true : false;
			$image = ( $format == 'image' ) ? true : false;
			
	    	$posttype = get_post_type();
	    	$template_name = get_post_meta( get_the_ID(), '_wp_page_template', true );
	    	
	    	$slidethumb = 0;

	    	$disabled = '';
	    	
	    	switch ($posttype) {
			    case "portfolio":
			        $sizemeta = get_post_meta(get_the_ID(), 'size', true);
			        $thumbopt = get_post_meta(get_the_ID(), '_studiofolio_portfolio_meta', true);
			        $diszoom = get_post_meta(get_the_ID(), 'disable_zoom_', true);
			        $disover= get_post_meta(get_the_ID(), 'disable_overlay_', true);
			        $disblock = get_post_meta(get_the_ID(), 'disable_block', true);
			        if ($diszoom) $disabled .= ' zoom_disable';
			        if ($disover) $disabled .= ' overlay_disable';
			        if ($disblock) $disabled = ' click_disable zoom_disable overlay_disable';
			        $titleON = (isset($data['portfolio_title'])) ? $data['portfolio_title'] : 1;
			        $contentON = (isset($data['portfolio_content'])) ? $data['portfolio_content'] : 1;
			        $thumbON = (isset($data['portfolio_thumb'])) ? $data['portfolio_thumb'] : 1;
			        $dateON = false;
			        $authorON = false;
			        $size = ($sizemeta) ? $sizemeta : 'width2';
			        $label = get_post_meta(get_the_ID(), 'portfolio_page', true);
			        $terms = get_the_terms( get_the_ID() , 'p_category' );
			        $termsarray = array(); 
			        if (is_array($terms)) {
					// Loop over each item since it's an array
					foreach( $terms as $term ) {
						array_push($termsarray, str_replace("-", "", $term->slug));
					}
					$cssclass = implode(' ', $termsarray);
					$cssclass = $cssclass . ' ' . basename(get_permalink($label));
				} else $cssclass = basename(get_permalink($label));
			        $label = get_the_title($label);
			        break;
			    case "page":	
			    	$sizemeta = get_post_meta(get_the_ID(), '_studiofolio_pages_meta', true);
			    	if (isset($sizemeta['disable_zoom'])) $disabled .= ' zoom_disable';
				if (isset($sizemeta['disable_overlay'])) $disabled .= ' overlay_disable';
				if (isset($sizemeta['disable_block'])) $disabled = ' click_disable zoom_disable overlay_disable';
			    	if ($template_name == 'templates/gallery.php' || $template_name == 'templates/gallery-collection.php') {
				        $titleON = (isset($data['gallery_title'])) ? $data['gallery_title'] : 1;
				        $contentON = (isset($data['gallery_content'])) ? $data['gallery_content'] : 1;
				        $thumbON = (isset($data['gallery_thumb'])) ? $data['gallery_thumb'] : 1;
				        $slidethumb = (isset($sizemeta['slidethumb'])) ? $sizemeta['slidethumb'] : 0;
				        $label = 'Gallery';
				        $cssclass = $label;
			    	} else {
				    	$titleON = (isset($data['page_title'])) ? $data['page_title'] : 1;
				        $contentON = (isset($data['page_content'])) ? $data['page_content'] : 1;
				        $thumbON = (isset($data['page_thumb'])) ? $data['page_thumb'] : 1;
				        $label = 'Page';
				        $cssclass = $label;
			    	}
			    	$dateON = false;
			    	$authorON = false;
			    	$size = (isset($sizemeta['size'])) ? $sizemeta['size'] : 'width2';
			        break;
			    case "post":
			    	$sizemeta = get_post_meta(get_the_ID(), '_studiofolio_pages_meta', true);
			    	if (isset($sizemeta['disable_zoom'])) $disabled .= ' zoom_disable';
				if (isset($sizemeta['disable_overlay'])) $disabled .= ' overlay_disable';
				if (isset($sizemeta['disable_block'])) $disabled = ' click_disable zoom_disable overlay_disable';
			        $titleON = (isset($data['blog_title'])) ? $data['blog_title'] : 1;
			        $contentON = (isset($data['blog_content'])) ? $data['blog_content'] : 1;
			        $thumbON = (isset($data['blog_thumb'])) ? $data['blog_thumb'] : 1;
			        $dateON = (isset($data['blog_date'])) ? $data['blog_date'] : 1;
			        $authorON = (isset($data['blog_author'])) ? $data['blog_author'] : 1;
			        $size = (isset($sizemeta['size'])) ? $sizemeta['size'] : 'width2';
			        $label = 'Blog';
			        $cssclass = $label;
			        break;
			}

	    ?>
	    	<div class="progressive element<?php echo ' ' . $size; ?><?php if ($quote) echo ' tweet'; ?><?php if ($template_name == 'templates/gallery.php') echo ' gallery'; echo ' '.$cssclass.'-item'; ?>">
	    		<div class="inside">
	    		<?php if (post_password_required()) { ?>
	    		  <div class="entry-text-cont">
	    			<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php echo(get_the_password_form()); ?>
	    		  </div>
				<?php } else { ?>
	    		<?php if (($template_name == 'templates/contact-sidebar.php' || $template_name == 'templates/contact-center.php' || $template_name == 'templates/contact-sidebar-fixed.php' || $template_name == 'templates/contact-full.php') && $contact_mb->get_the_value('asw') && $contact_mb->get_the_value('ash') && $contact_mb->get_the_value('lat') && $contact_mb->get_the_value('lon')) { ?>
		    		<div id="map">
		    			<?php
		    				$w = $contact_mb->get_the_value('asw');
						  	$h = $contact_mb->get_the_value('ash');
						  	$la = $contact_mb->get_the_value('lat');
						  	$lo = $contact_mb->get_the_value('lon');
		    				echo "\n\t<script>\n";
		    				echo "\t\tjQuery(document).ready(function() {\n";
						  	echo "\n\tif (document.getElementById('map')) {\n";
						  	echo "\t\t/*  gMap\n";
						    echo "\t\t========================================================================== */\n";
							echo "\t\t// http://universimmedia.pagesperso-orange.fr/geo/loc.htm\n";
							echo "\t\tvar gmap = jQuery('#map');\n";
							echo "\t\tfunction setGMapHeight() {\n";
							echo "\t\tgmap.height((gmap.width() * $h) / $w);\n";
							echo "\t\tgmap.gMap('centerAt', { latitude: $la, longitude: $lo, zoom: 14 });\n";
							echo "\t\t}\n";
							echo "\t\tsetGMapHeight();\n";
							echo "\t\tif (gmap.length) {\n";
							echo "\t\tgmap.gMap({\n";
							echo "\t\tcontrols: {\n";
							echo "\t\tpanControl: false,\n";
							echo "\t\tzoomControl: true,\n";
							echo "\t\tzoomControlOptions: {\n";
							echo "\t\tstyle: google.maps.ZoomControlStyle.SMALL\n";
							echo "\t\t},\n";
							echo "\t\tmapTypeControl: false,\n";
							echo "\t\tstreetViewControl: false,\n";
							echo "\t\toverviewMapControl: false\n";
							echo "\t\t},\n";
							echo "\t\tmaptype: 'ROADMAP',\n";
							echo "\t\tzoom: 14,\n";
							echo "\t\tmarkers: [{\n";
							echo "\t\tlatitude: $la,\n";
							echo "\t\tlongitude: $lo,\n";
							echo "\t\ticon: {\n";
							echo "\t\timage: '".get_template_directory_uri()."/assets/img/gmap_pin.png',\n";
							echo "\t\ticonsize: [31, 43],\n";
							echo "\t\ticonanchor: [15, 40]\n";
							echo "\t\t}\n";
							echo "\t\t}]\n";
							echo "\t\t});\n";
							echo "\t\t}\n";
							echo "\t\tjQuery(window).smartresize(function() {\n";
							echo "\t\tsetGMapHeight();\n";
							echo "\t\t}).resize();\n";
							echo "\t}\n";
							echo "\t})\n";
							echo "\t</script>\n";
		    			?>
		    		</div>
		    	<?php } else if ($thumbON) { ?>
	    			<div class="entry-thumb<?php if ($titleON) echo ' wplus'; if ($slidethumb) echo ' slide'; ?>" data-overlay="<?php if ($titleON) echo '&#xe072;'; else echo get_the_title(); ?>" data-area="<?php echo $label; ?>">
    				<?php 
    					if (has_post_thumbnail()) {
	    					$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
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
	    				<div class="span12 <?php if ($audio || $image) echo $format; ?>">
		    				<div class="dummy" style="margin-top: <?php echo get_dummy_height($image_attributes[1],$image_attributes[2]); ?>%"></div>
		    				<a href="<?php the_permalink(); ?>" class="video pushed<?php echo $disabled; ?>"><?php the_post_thumbnail(get_image_size($size)); ?>
		    					<?php if ($video) { ?>
		    						<div class="video"></div>
		    					<?php } ?>
		    				</a>
		    			</div>
    					<?php } else if ($template_name == 'templates/gallery.php' && $slidethumb) { ?>
    					<div class="span12">
		    				<a href="<?php the_permalink(); ?>" class="<?php echo $disabled; ?>">
		    				  <div class="flexslider">
			    				<ul class="slides">
	    					<?php 
	    						$gallery_mb->the_meta($post->ID);
	    						$gimages = $gallery_mb->get_the_value('slides');
	    		
	    						if (is_array($gimages)) {
										foreach($gimages as &$gimage) { 
											if (isset($gimage['imgurl'])) {
												$imgID = $gimage['imgurl'];
												echo '<li class="slide">'.wp_get_attachment_image( $imgID, get_image_size($size), 0).'</li>';	
											} else if (isset($gimage['video'])) {
												remove_filter('the_content','wpautop', 12);
					    					$output = apply_filters('the_content', $gimage['video']);
					    					add_filter('the_content','wpautop', 12);
												echo '<li class="slide">'.$output.'</li>';	
											}
										}
									}
	    					?>
			    				</ul>
		    				  </div>
		    				</a>
		    			</div>
	    				<?php } else { 
	    				$mediacode = get_post_meta(get_the_ID(), '_studiofolio_post_meta', true);
	    				if ($mediacode && array_key_exists('media', $mediacode)) {
	    					remove_filter('the_content','wpautop', 12);
	    					$output = apply_filters('the_content', $mediacode['media']);
	    					add_filter('the_content','wpautop', 12); ?>
	    				<div class="span12 <?php if ($audio || $image) echo $format; ?>">
    						<?php echo $output; ?>
	    				</div>
    					<?php }
						} ?>
	    			</div>
	    		<?php } 
	    		 if ( ($titleON || $contentON) && !$quote) { ?>
	    			<div class="entry-text-cont">
	    				<?php if ($titleON && !$quote) { ?>
	    				<h2 class="entry-title"><a class="<?php echo $disabled; ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	    				<?php } ?>
	    				<?php if ($contentON) { 
		    			if ($dateON) { ?>
			    		<div class="entry-meta">
			    			<?php echo get_the_time( "F d, Y" ); ?> 
			    		</div>	
		    			<?php }
		    			if (isset($authorON) && $authorON) { ?>
			    		<div class="entry-meta">
			    			<?php echo get_the_author(); ?> 
			    		</div>	
		    			<?php }
		    			if ( has_excerpt() || get_the_content() != '' ) { ?>
		    			<div class="entry-text">
	    				<?php 
	    					if ( ! has_excerpt() ) the_content();
	    					else the_excerpt();
	    				?>
	    				</div>
	    				<?php } ?>
	    				<?php } ?>
	    			</div>
	    		<?php }
	    			} ?>
	    		</div>
	    	</div>
		<?php endforeach; ?>		
	    </div>
    </div>
    <?php 

	if ($total_posts > $load_more && $load_more > 0) { ?>
	<div class="load-more inside hvr progressive">
    	<div class="entry-text-cont">
    		<a href="#" data-pages="<?php echo ceil($total_posts / $load_more); ?>" data-page="<?php echo $paged; ?>" data-link="<?php echo next_posts($mposts->max_num_pages, false); ?>">More Items...</a>
    	</div>
    </div>
    <?php } ?>
	<?php	// Reset Post Data
		wp_reset_postdata();
	}
	?>
<?php endwhile; /* End loop */ ?>