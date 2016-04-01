<?php 
	global $data;
?>
  <div id="footer-container" class="fixed-wrap">
		<footer id="footer" class="<?php if (!isset($data['left_menu']) || !$data['left_menu']) echo 'progressive';?>" role="contentinfo">
		   <?php 
			   if (isset($data['left_menu']) && $data['left_menu']) {
		   ?>
		   <div class="navbar">
		   		<?php include(locate_template('templates/socials.php')); ?>
		   </div>
		   <?php } ?>
		   <?php 
		   ob_start();
		   dynamic_sidebar('sidebar-footer');
		   $sidebar = ob_get_clean();
		   if ($sidebar) { ?>
		   <div class="container-fluid widgets">
		  	<div class="row-fluid">
		   	 <?php echo $sidebar; ?>
		   	</div>
		  </div>
		  <?php } ?>	
		  <div class="container-fluid">
		  	<div class="row-fluid">
	          <div id="footer-last" class="span12<?php if ($sidebar) echo ' brd'; ?>">
	            <div id="copyright" class="span6"><?php 
				if (isset($data['footer']) && $data['footer']) {
					$footer = $data['footer'];
					$footer = apply_filters('the_content', $footer);
	              	echo $footer;
	            } ?></div>
	            <div id="go-up" class="span6">
	              <a class="glyph btn hvr go-up" href="#" data-icon=""></a>
	            </div> 
	          </div><!-- span12 -->
	        </div><!-- row-fluid -->
		  </div>
		</footer><!-- footer -->
  </div><!-- fixed-wrap -->
</div><!-- wrapper -->
<!-- Modal -->
<div id="share" class="modal hide fade">
  <div class="modal-body">
    <div id="social">
		  <div id="twitter" data-url="<?php echo get_permalink( $post->ID ); ?>" data-text="<?php echo $post->post_title; ?>" data-title="Tweet"></div>
		  <div id="facebook" data-url="<?php echo get_permalink( $post->ID ); ?>" data-text="<?php echo $post->post_title; ?>" data-title="Like"></div>
		  <div id="googleplus" data-url="<?php echo get_permalink( $post->ID ); ?>" data-text="<?php echo $post->post_title; ?>" data-title="+1"></div>
		  <!--<div id="linkedin" data-url="<?php echo get_permalink( $post->ID ); ?>" data-text="<?php echo $post->post_title; ?>" data-title="linkedin"></div>-->
		  <div id="pinterest" data-url="<?php echo get_permalink( $post->ID ); ?>" data-text="<?php echo $post->post_title; ?>" data-title="pinterest"></div>
		</div>
  </div>
</div>
<?php wp_footer(); ?>