<?php
	if (!is_home()) { ?>
    <?php
      if (is_archive()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        if ($term) { ?>
	    <h1 class="portfolio-title"><?php
          echo $term->name;
        } elseif (is_post_type_archive()) { ?>
	    <h1 class="portfolio-title"><?php
          echo get_queried_object()->labels->name;
        } elseif (is_day()) { ?>
	    <h1 class="portfolio-title"><?php
          printf(__('Daily Archives: %s', 'studiofolio'), get_the_date());
        } elseif (is_month()) { ?>
	    <h1 class="portfolio-title"><?php
          printf(__('Monthly Archives: %s', 'studiofolio'), get_the_date('F Y'));
        } elseif (is_year()) { ?>
	    <h1 class="portfolio-title"><?php
          printf(__('Yearly Archives: %s', 'studiofolio'), get_the_date('Y'));
        } elseif (is_author()) { ?>
	    <h1 class="portfolio-title"><?php
          global $post; 
          $author_id = $post->post_author;
          printf(__('Author Archives: %s', 'studiofolio'), get_the_author_meta('display_name', $author_id));
        } else { ?>
	    <h1 class="portfolio-title"><?php
          single_cat_title();
        }
      } elseif (is_search()) { ?>
	    <h1 class="portfolio-title"><?php
        printf(__('Search Results for %s', 'studiofolio'), get_search_query());
      } elseif (is_404()) { ?>
	    <h1 class="portfolio-title"><?php
        _e('File Not Found', 'studiofolio');
      } else { ?>
      <div class="entry-cont progressive span12">
	    <h1 class="portfolio-title"><?php
          the_title();
      }
    ?>
  </h1>
<?php } ?>
