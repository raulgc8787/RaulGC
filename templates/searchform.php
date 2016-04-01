<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
  <label class="hide" for="s"><?php _e('Search for:', 'studiofolio'); ?></label>
  <div class="input-append">
 	 <input type="text" value="" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'studiofolio'); ?> <?php bloginfo('name'); ?>">
 	 <span class="add-on" data-icon="&#xe072;"></span>
  </div>
</form>