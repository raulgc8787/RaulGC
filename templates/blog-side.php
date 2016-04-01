<?php
/*
Post Template Layout: Blog Fixed Sidebar
*/
?>

<?php while (have_posts()) : the_post(); 
$format = get_post_format(get_the_ID());
$video = ( $format == 'video' ) ? true : false;
$audio = ( $format == 'audio' ) ? true : false;
$image = ( $format == 'image' ) ? true : false;
$quote = ( $format == 'quote' ) ? ' tweet' : '';

global $pages_mb, $data;

$mposts = $pages_mb->get_the_value('slides');
?>
<?php if ($video || $audio || $image || $mposts) { ?>
  <div class="main-side">
	<?php 
  	 if ($mposts) get_template_part('templates/post-slideshow');
  	 else { ?>
	 	<div class="img-cont progressive">
			<?php 
			  $mediacode = get_post_meta(get_the_ID(), '_studiofolio_post_meta', true);
			  remove_filter('the_content','wpautop', 12);
				$output = apply_filters('the_content', $mediacode['media']);
				add_filter('the_content','wpautop', 12);
			  echo $output;
			?>
		</div>
	<?php }
		} else if (has_post_thumbnail()) { ?>
  <div class="main-side">
		<div class="img-cont progressive">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php } else { ?>
  <div class="main-side no-img">
	<?php } ?>
		<div class="entry-cont progressive">
			<div class="span12<?php echo $quote; ?>">
				<h1 class="portfolio-title"><?php the_title(); ?></h1>
				<?php
					if (isset($data['blog_date']) && $data['blog_date']) echo '<div class="entry-meta">'.get_the_time( "F d, Y" ).'</div>';
			        if (isset($data['blog_author']) && $data['blog_author']) echo '<div class="entry-meta">'.get_the_author().'</div>'; ?>
				<?php if (post_password_required()) : ?>
				  <section id="comments">
				    <div class="alert alert-block fade in">
				      <a class="close" data-dismiss="alert">&times;</a>
				      <p><?php _e('This post is password protected. Enter the password to view comments.', 'studiofolio'); ?></p>
				    </div>
				  </section><!-- /#comments -->
				<?php endif; ?>
				<?php the_content(); ?>
				<?php comments_template('/templates/comments.php'); ?>
			</div>
		</div>
  </div>
<?php endwhile; ?>