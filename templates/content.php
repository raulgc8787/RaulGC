<?php if (!have_posts()) : ?>
  <div class="alert alert-block fade in">
    <a class="close" data-dismiss="alert">&times;</a>
    <p><?php _e('Sorry, no results were found.', 'studiofolio'); ?></p>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

	<?php
	
	if (!is_search() && !is_archive() && !is_category() && !is_tax()) {
		
		$post_object = get_post( get_queried_object_id() );
		$output = $post_object->post_content;
		if ($output != '') {
			remove_filter('the_content','wpautop', 12);
			$output = apply_filters('the_content', '<div class="top-html-blocks">'.$post_object->post_content.'</div>');
			add_filter('the_content','wpautop', 12);
			echo $output;
		}
	
		$taxonomyName = "category";
		$terms = get_terms($taxonomyName); 
		if (count($terms) > 1) {

	?>
	<div class="navbar filter">
		<div class="row-fluid">
			<div class="span12">
				<div class="navbar-inner menu-cont progressive">
			      <!-- /.inner-brand -->
			      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse-filter">
			      	<span>Filter</span> 
			      	<span class="menu-icon">
			      		<i data-icon="&#xe088;"></i>
			      	</span>
			      </a>
			      <div class="clearfix"></div>
			   </div>
			   
				<nav id="nav-filter" class="nav-collapse nav-collapse-filter progressive" role="navigation">
						<ul id="filters" class="nav main-menu filter">
							<?php 
								if (count($terms) > 1) echo '<li class="pull-right active"><a href="#" class="hvr" data-filter="*">Show All</a></li>';
								foreach($terms as $term) {
	
									if ($term->count > 0) echo '<li><a href="#" data-filter=".' .str_replace("-", "", $term->slug).'">'.$term->name.'</a></li>';
								}
								
			
							?>
						</ul>
				</nav>
			</div>
		</div>
	</div>
	<?php }
	} 
	
	global $data;
	if (isset($data['regular_index']) && $data['regular_index'] && (get_post_type(get_the_ID()) == 'post' || is_post_type_archive('post'))) $normal_index = true;
	else $normal_index = false;

	if ($normal_index) { ?>
	
	<div class="row-fluid">
		<div class="span8">
		
	<?php } 

	if (get_post_type(get_the_ID()) == 'portfolio') $cat_array = get_term_by('id', get_queried_object_id(), 'p_category');
	else if (get_post_type(get_the_ID()) == 'post') $cat_array = get_term_by('id', get_queried_object_id(), 'category');
	
	if (isset($cat_array->description) && $cat_array->description) {
		remove_filter('the_content','wpautop', 12);					
		echo '<div class="category-desc">'.apply_filters('the_content', $cat_array->description).'</div>';
		add_filter('the_content','wpautop', 12);
	}

	?>

  <div class="container-isotope">
	<div id="isotope" class="isotope">
<?php	
	global $data, $wp_embed; //fetch options stored in $data
	$paged = (get_query_var('page')) ? get_query_var('page') : 1;
	while (have_posts()) : the_post(); 
	global $more;
	global $pages_mb;
	$more = 0;
	$format = get_post_format(get_the_ID());
	$quote = ( $format == 'quote' ) ? true : false;
	$video = ( $format == 'video' ) ? true : false;
	$audio = ( $format == 'audio' ) ? true : false;
	$image = ( $format == 'image' ) ? true : false;
	
	$posttype = get_post_type();
	
	switch ($posttype) {
    case "portfolio":
    	$titleON = (isset($data['portfolio_title'])) ? $data['portfolio_title'] : 1;
      $contentON = (isset($data['portfolio_content'])) ? $data['portfolio_content'] : 1;
      $thumbON = (isset($data['portfolio_thumb'])) ? $data['portfolio_thumb'] : 1;
      $dateON = false;
      $authorON = false;
      break;
    case "page":	
    	$titleON = (isset($data['page_title'])) ? $data['page_title'] : 1;
	    $contentON = (isset($data['page_content'])) ? $data['page_content'] : 1;
	    $thumbON = (isset($data['page_thumb'])) ? $data['page_thumb'] : 1;
    	$dateON = false;
    	$authorON = false;
      break;
    case "post":
    	$titleON = $data['blog_title'];
			$contentON = $data['blog_content'];
			$thumbON = $data['blog_thumb'];
			$dateON = $data['blog_date'];
			$authorON = $data['blog_author'];
      break;
	}
	
	$sizemeta = get_post_meta(get_the_ID(), 'size', true);
	$size = (isset($sizemeta)) ? $sizemeta : 'width2';
	if ($size == '') {
		$sizemeta = get_post_meta(get_the_ID(), '_studiofolio_pages_meta', true);
		$size = (is_array($sizemeta)) ? $sizemeta['size'] : 'width2';	
	} 

	
	if ($normal_index) $size = 'width4';
	
	$terms = get_the_terms( get_the_ID() , 'category' );
	$termsarray = array(); 
	
	if (is_array($terms)) {
		// Loop over each item since it's an array
		foreach( $terms as $term ) {
			array_push($termsarray, str_replace("-", "", $term->slug));
		}
	}

?>
	<div class="progressive element<?php echo ' ' . $size; ?><?php if ($quote) echo ' tweet'; ?> <?php echo(implode(' ', $termsarray)); ?>">
		<div class="inside">
		<?php if ((has_post_thumbnail() && $thumbON && !$quote) || $audio || $image || $video) { ?>
	    			<div class="entry-thumb<?php if ($titleON) echo ' wplus'; ?>" data-overlay="<?php if ($titleON) echo '&#xe072;'; else echo get_the_title(); ?>" data-area="<?php echo ucfirst($format); ?>">
	    				<?php if (!has_post_thumbnail() && ($audio || $image || $video)) { ?>
	    					<div class="span12 <?php echo $format; ?> type">
		    				<?php 
		    					$mediacode = get_post_meta(get_the_ID(), '_studiofolio_post_meta', true);
		    					if(isset($mediacode['media'])) $mediacode = $mediacode['media'];
		    					else {
			    					if (isset($mediacode['slides'][0]['video'])) $mediacode = $mediacode['slides'][0]['video'];
			    					else $mediacode = $mediacode['slides'][0]['imgurl'];
		    					}
		    					
		    					if (is_numeric($mediacode)) {
										$image_attributes = wp_get_attachment_image_src( $mediacode, 'full' );
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
										$output = '<div class="dummy" style="margin-top: '.get_dummy_height($image_attributes[1],$image_attributes[2]).'%"></div><a href="'.get_permalink().'" class="pushed">' . wp_get_attachment_image( $mediacode, get_image_size($size), 0, array('class' => "attachment-full") ) .'</a>';
									} else {
										ob_start();
										$a = getimagesize($mediacode);
										$getsize_warning = ob_get_clean();
										if(empty($getsize_warning)) {
											$image_type = $a[2];
									    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {
									      $image_attributes = getimagesize($mediacode);
												$output = '<div class="dummy" style="margin-top: '.get_dummy_height($image_attributes[0],$image_attributes[1]).'%"></div><a href="'.get_permalink().'" class="pushed"><img src="'.$mediacode.'" class="attachment-full" alt=""></a>';
									    } else {
										    remove_filter('the_content','wpautop', 12);					
												$output = apply_filters('the_content', $mediacode);
												add_filter('the_content','wpautop', 12);	
									    }
								    } else {
									    remove_filter('the_content','wpautop', 12);					
											$output = apply_filters('the_content', $mediacode);
											add_filter('the_content','wpautop', 12);	
								    }
									}
		    					echo $output;
		    				
								?>
	    					</div>
	    				<?php } else {	
	    				
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
	    			<?php echo get_the_time(get_option('date_format')); ?> 
	    		</div>	
    			<?php }
    			if ($authorON && !$quote) { ?>
	    		<div class="entry-meta">
	    			<?php echo get_the_author(); ?> 
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

<?php 
	if ($normal_index) { ?>

		</div>
		
		<div class="span4 side-right-cont sidebar-blog progressive">
			<div class="sidebar-cont">
				<div class="entry-cont">
					<?php dynamic_sidebar('sidebar-primary'); ?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
<?php } ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
  <div class="load-more inside hvr progressive">
   	<div class="entry-text-cont">
   		<?php get_next_posts_link(); ?>
  		<a href="#" data-pages="<?php echo $wp_query->max_num_pages; ?>" data-page="<?php echo $paged; ?>" data-link="<?php echo next_posts($wp_query->max_num_pages, false); ?>">More Items...</a>
   	</div>
  </div>
<?php endif; ?>