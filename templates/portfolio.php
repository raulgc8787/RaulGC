<?php
/*
Template Name: Portfolio
*/
?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php 
	
	if (post_password_required()) { ?>
		
	<div class="entry-cont progressive">
		<div class="span12">
			<h1 class="portfolio-title"><?php the_title(); ?></h1>
			<?php echo(get_the_password_form()); ?>
		</div>
	</div>
	
	<?php } else {
	
		$output = get_the_content();
		if ($output != '') {
			remove_filter('the_content','wpautop', 12);
			$output = apply_filters('the_content', '<div class="top-html-blocks">'.$output.'</div>');
			add_filter('the_content','wpautop', 12);
			echo $output;
		}
	
		$taxonomyName = "p_category";
		$terms = get_terms($taxonomyName);
		
		global $data;
		global $portfolio_mb;
		global $portfolio_page_mb;
		
		
		if (is_front_page()) $paged = (get_query_var('page')) ? get_query_var('page') : 1;
		else $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$load_more = is_numeric($portfolio_page_mb->get_the_value('loadmore')) ? $portfolio_page_mb->get_the_value('loadmore') : -1;
	
		$titleON = (isset($data['portfolio_title'])) ? $data['portfolio_title'] : 1;
		$contentON = (isset($data['portfolio_content'])) ? $data['portfolio_content'] : 1;
		$thumbON = (isset($data['portfolio_thumb'])) ? $data['portfolio_thumb'] : 1;
		$ID = get_the_ID();

		$post_status = is_user_logged_in() ? array('publish','private') : 'publish';
		
		$args = array(
		   'orderby' => 'rand',
		   'post_type' => 'portfolio',
		   'post_status'=> $post_status,
		   'posts_per_page' => $load_more,
		   'paged' => $paged,
		   'meta_query' => array(
		                array(
			                // only works if 'mode' => WPALCHEMY_MODE_EXTRACT is used for $custom_metabox in functions.php
			                'key' => $portfolio_mb->get_the_name('portfolio_page'),
			                'value' => $ID,
		                )
		           )
		 );

		$args1 = array(
		   'post_type' => 'portfolio',
		   'post_status'=> $post_status,
		   'posts_per_page' => -1
		 );

		$query = new WP_Query( $args );
		$query1 = new WP_Query( $args1 );
		$total_posts = $query->found_posts;
		
		if (count($terms) > 1) {

	?>
	<h1>Raul Garcia Castilla, <span class="grey">illustrator, graphic designer, web <span class="red">&#38;</span> UX/UI developer</span></h1>
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
									$counter = 0;
									foreach($query1->posts as &$post1) {
										$key_1_values = get_post_meta($post1->ID, 'portfolio_page', true);
										if (pa_in_taxonomy( 'p_category', $term->slug, $post1->ID )) {
											if (get_queried_object_id() == $key_1_values) $counter++;
										}
										
									}
									
								    if ($counter > 0) echo '<li><a href="#" data-filter=".' .str_replace("-", "", $term->slug).'">'.$term->name.'</a></li>';
								}
								
			
							?>
						</ul>
				</nav>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="container-isotope">
		<div id="isotope" class="isotope"><?php
	
		while ( $query->have_posts() ) : $query->the_post();
			global $more;
	    		$more = 0;
			$portfolio_mb->the_meta($post->ID);
			$portfolio_page = $portfolio_mb->get_the_value('portfolio_page'); 
			
			$disabled = '';
			if ($portfolio_mb->get_the_value('disable_zoom_')) $disabled .= ' zoom_disable';
			if ($portfolio_mb->get_the_value('disable_overlay_')) $disabled .= ' overlay_disable';
			if ($portfolio_mb->get_the_value('disable_block')) $disabled = ' click_disable zoom_disable overlay_disable';
			
			$colwidth = $portfolio_mb->get_the_value('size'); 
			if (!$colwidth) $colwidth = 'width2';
			$terms = get_the_terms( get_the_ID() , 'p_category' );
			$termsarray = array(); 
			
			if (is_array($terms)) {
				// Loop over each item since it's an array
				foreach( $terms as $term ) {
					array_push($termsarray, str_replace("-", "", $term->slug));
				}
			}
		?>
		
			<div class="progressive element <?php echo $colwidth; ?> <?php echo(implode(' ', $termsarray)); ?>">
				<div class="inside">
					<?php if (post_password_required()) { ?>
		    		  <div class="entry-text-cont">
		    			<h2 class="entry-title"><?php the_title(); ?></h2>
						<?php echo(get_the_password_form()); ?>
		    		  </div>
					<?php } else { ?>
					<?php if ( has_post_thumbnail() && $thumbON ) { ?>
					<div class="entry-thumb<?php if ($titleON) echo ' wplus'; ?>" data-overlay="<?php if ($titleON) echo '&#xe072;'; else echo get_the_title(); ?>"> 
					<?php $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' ); 
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
						<a href="<?php the_permalink(); ?>" class="pushed<?php echo $disabled; ?>"><?php the_post_thumbnail(get_image_size($colwidth)); ?></a>
					</div>
					<?php } ?>
					<?php if ( $titleON || $contentON) { ?>
	    			<div class="entry-text-cont">
	    				<?php if ($titleON) { ?>
	    				<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	    				<?php } ?>
	    				<?php if ($contentON) { ?>
		    			<div class="entry-text">
	    				<?php 
	    					if ( ! has_excerpt() ) the_content();
	    					else the_excerpt();
	    				?>
	    				</div>
	    				<?php } ?>
	    			</div>
	    		<?php }
	    		} ?>
				</div>
			</div> 
		
			<?php 
		endwhile;
		
		// Reset Post Data
		wp_reset_postdata(); ?>
	
		</div>
	</div>
	<?php

	if ($total_posts > $load_more && $load_more > 0) { ?>
	<div class="load-more hvr progressive">
    	<div class="entry-text-cont">
    		<a href="#" data-pages="<?php echo ceil($total_posts / $load_more); ?>" data-page="<?php echo $paged; ?>" data-link="<?php echo next_posts($query->max_num_pages, false); ?>">More Items...</a>
    	</div>
    </div>
    <?php }
    } ?>
<?php endwhile; /* End loop */ ?>