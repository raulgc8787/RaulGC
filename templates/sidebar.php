<?php 
    $data = get_option(OPTIONS);
    if (is_page_template('templates/contact-sidebar.php') || is_page_template('templates/contact-sidebar-fixed.php')) { 
        
        global $contact_mb;
        $contact_info = $contact_mb->get_the_value('contact_info'); 
        $prdetails = '';
        if (isset($data['contact_details']) && $data['contact_details']) { 
        $details = $data['contact_details'];
	        foreach($details as $detail) { 
	            if ($contact_mb->get_the_value('detail_'.$detail["title"])) { 
	            $prdetails .= '<span class="p-info"><b class="p-info-meta">'.ucfirst($detail['title']).':</b> '.ucfirst($contact_mb->get_the_value('detail_'.$detail["title"])).'</span>'; 
	            }
	        }
        } ?>
    <h1 class="portfolio-title"><?php the_title(); ?></h1>
    <?php
    	if ($contact_mb->get_the_value('topdetails')) echo $prdetails . '<br><hr><br>';
    ?>
    <p><?php echo $contact_info; ?></p>
    <?php
    	if (!$contact_mb->get_the_value('topdetails')) echo '<hr><br>' . $prdetails;
    ?>
    
  <?php } else if (is_page_template('templates/contact-center.php') || is_page_template('templates/page-center.php') || is_page_template('templates/page-center-full.php') || is_page_template('templates/page-center-iso.php') || is_page_template('templates/page-center-iso-fixed.php') || is_page_template('templates/page-sidebar.php') || is_page_template('templates/page-sidebar-fixed.php') || is_page_template('templates/page-sidebar-iso-fixed.php') || is_page_template('templates/page-sidebar-iso.php') || is_page_template('templates/page-text-center.php') || is_page_template('templates/page-text-full.php') || is_page_template('templates/page-text-fixed.php')) { ?>
  
    <h1 class="portfolio-title"><?php the_title(); ?></h1>

    <div class="portfolio-entry">
        <?php the_content(); ?>
    </div>
    
    <?php } else {
            if (get_post_type() == 'portfolio') { 
                global $portfolio_mb;
                $portfolio_page = $portfolio_mb->get_the_value('portfolio_page');
                $back = get_permalink($portfolio_page);
                
                $pagetemplate = get_post_meta($post->ID, '_post_template', true);
                if ($pagetemplate == 'templates/portfolio-center.php' || $pagetemplate == 'templates/portfolio-center-iso.php' || $pagetemplate == 'templates/portfolio-center-iso-fixed.php' || $pagetemplate == 'templates/portfolio-center-full.php') {
            ?>

    <h1 class="portfolio-title"><?php the_title(); ?></h1>

    <div class="span8 side-left-cont">
        <div class="portfolio-entry">
            <?php the_content(); ?>
        </div>
    </div>

    <div class="span4 side-right-cont progressive">
        <div class="portfolio-meta">
            <?php 
            	if (isset($data['pf_details']) && $data['pf_details']) { 
	                $details = $data['pf_details'];
	                foreach($details as $detail) { 
	                if ($portfolio_mb->get_the_value('detail_'.$detail["title"])) {
	                ?><span class="p-info"><b class="p-info-meta"><?php echo ucfirst($detail['title']); ?>:</b> <?php echo ucfirst($portfolio_mb->get_the_value('detail_'.$detail["title"])); ?></span> <?php }
	                }
                }
            ?>
            <?php
                add_filter( 'get_previous_post_join', 'filter_private_future_nextprevious_links' );
                add_filter( 'get_next_post_join', 'filter_private_future_nextprevious_links' );
                function filter_private_future_nextprevious_links($join)
                {
                    global $post, $wpdb, $portfolio_mb;
                    $portfolio_page = $portfolio_mb->get_the_value('portfolio_page');
                    return $join . "INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = 'portfolio_page' AND m.meta_value = '$portfolio_page'";
                }
            ?>

            <div class="portfolio-btn-cont">
                <div class="btns-nav">
                    <?php next_post_link('%link', '<i class="btn sf hvr left" data-icon=""></i>'); ?>
                    <a href="<?php echo $back; ?>"><i class="btn sf hvr remove" data-icon=""></i></a>
                    <?php previous_post_link('%link','<i class="btn sf hvr right" data-icon=""></i>'); ?>
                </div>
                <i class="btn sf hvr share" data-toggle="modal" data-target="#share" data-icon=""></i>
            </div>
        </div>
    </div>
        <?php } else { ?>

        <h1 class="portfolio-title"><?php the_title(); ?></h1>

        <div class="portfolio-entry">
            <?php the_content(); ?>
        </div>

        <div class="portfolio-meta">
            <?php 
            	if (isset($data['pf_details']) && $data['pf_details']) { 
		            $details = $data['pf_details'];
		            foreach($details as $detail) { 
		                if ($portfolio_mb->get_the_value('detail_'.$detail["title"])) {
		            ?><span class="p-info"><b class="p-info-meta"><?php echo ucfirst($detail['title']); ?>:</b> <?php echo ucfirst($portfolio_mb->get_the_value('detail_'.$detail["title"])); ?></span> <?php }
		            }
                }
            ?>
            <?php
                add_filter( 'get_previous_post_join', 'filter_private_future_nextprevious_links' );
                add_filter( 'get_next_post_join', 'filter_private_future_nextprevious_links' );
                function filter_private_future_nextprevious_links($join)
                {
                    global $post, $wpdb, $portfolio_mb;
                    $portfolio_page = $portfolio_mb->get_the_value('portfolio_page');
                    return $join . "INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = 'portfolio_page' AND m.meta_value = '$portfolio_page'";
                }
            ?>

            <div class="portfolio-btn-cont">
                <div class="btns-nav">
                    <?php next_post_link('%link', '<i class="btn sf hvr left" data-icon=""></i>'); ?>
                    <a href="<?php echo $back; ?>"><i class="btn sf hvr remove" data-icon=""></i></a>
                    <?php previous_post_link('%link','<i class="btn sf hvr right" data-icon=""></i>'); ?>
                </div>
                <i class="btn sf hvr share" data-toggle="modal" data-target="#share" data-icon=""></i>
            </div>
        </div>
            <?php }
               } else { 
                    $back = get_permalink(get_option('page_for_posts'));
                    dynamic_sidebar('sidebar-primary'); 
            ?>
            <div class="portfolio-btn-cont">
                <div class="btns-nav">
                    <?php next_post_link('%link', '<i class="btn sf hvr left" data-icon=""></i>'); ?>
                    <a href="<?php echo $back; ?>"><i class="btn sf hvr remove" data-icon=""></i></a>
                    <?php previous_post_link('%link','<i class="btn sf hvr right" data-icon=""></i>'); ?>
                </div>
                <i class="btn sf hvr share" data-toggle="modal" data-target="#share" data-icon=""></i>
            </div>
            	<?php } ?>
            <?php } ?>
