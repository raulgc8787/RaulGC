<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>
<?php endwhile; ?>
<?php if (!is_home()) { ?>
</div>
<?php } ?>