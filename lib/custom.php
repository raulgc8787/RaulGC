<?php

// Custom functions

/**
 *
 * Add support Page Template for portoflio custom post
 *
 */

add_action('add_meta_boxes','add_post_template_metabox');

function add_post_template_metabox() {
	add_meta_box('postparentdiv', __('Layout', 'studiofolio' ), 'post_template_meta_box', 'portfolio', 'side', 'core');
	add_meta_box('postparentdiv', __('Layout', 'studiofolio' ), 'post_template_meta_box', 'post', 'side', 'core');
}

function post_template_meta_box($post) {
	
	if ( 'portfolio' == $post->post_type && 0 != count( get_portfolio_templates() ) ) {
		wp_nonce_field( basename( __FILE__ ), 'post_template_class_nonce' );
		$template = get_post_meta($post->ID,'_post_template',true);
	?>
	<label class="screen-reader-text" for="post_template"><?php _e('Page Template', 'studiofolio' ) ?></label><select name="post_template" id="post_template">
	<option value='default'><?php _e('Default Template', 'studiofolio' ); ?></option>
	<?php portfolio_template_dropdown($template); ?>
	</select>
	<?php
	}
	if ( 'post' == $post->post_type && 0 != count( get_post_templates() ) ) {
		wp_nonce_field( basename( __FILE__ ), 'post_template_class_nonce' );
		$template = get_post_meta($post->ID,'_post_template',true);
	?>
	<label class="screen-reader-text" for="post_template"><?php _e('Page Template', 'studiofolio' ) ?></label><select name="post_template" id="post_template">
	<?php post_template_dropdown($template); ?>
	</select>
	<?php
	}
}

function get_portfolio_templates() {
	$themes = wp_get_themes();
	$theme = wp_get_theme()->template;

	$templates = $themes[$theme]['Template Files'];

	$post_templates = array();

	if ( is_array( $templates ) ) {
		$base = array( trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()) );

		foreach ( $templates as $template ) {
			$basename = str_replace($base, '', $template);
			if ($basename != 'functions.php') {
				// don't allow template files in subdirectories

				if ( false === strpos($basename, 'templates/') )
					continue;

				$template_data = implode( '', file( $template ));

				$name = '';
				if ( ! preg_match( '|Portfolio Template Layout:(.*)$|mi', file_get_contents( $template ), $name ) )
					continue;
				$name = _cleanup_header_comment($name[1]);

				if ( !empty( $name ) ) {
					$post_templates[trim( $name )] = $basename;
				}
			}
		}
	}

	return $post_templates;
}

function get_post_templates() {
	$themes = wp_get_themes();
	$theme = wp_get_theme()->template;

	$templates = $themes[$theme]['Template Files'];

	$post_templates = array();

	if ( is_array( $templates ) ) {
		$base = array( trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()) );

		foreach ( $templates as $template ) {
			$basename = str_replace($base, '', $template);
			if ($basename != 'functions.php') {
				// don't allow template files in subdirectories

				if ( false === strpos($basename, 'templates/') )
					continue;

				$template_data = implode( '', file( $template ));

				$name = '';
				if ( ! preg_match( '|Post Template Layout:(.*)$|mi', file_get_contents( $template ), $name ) )
					continue;
				$name = _cleanup_header_comment($name[1]);

				if ( !empty( $name ) ) {
					$post_templates[trim( $name )] = $basename;
				}
			}
		}
	}

	return $post_templates;
}

function portfolio_template_dropdown( $default = '' ) {
	$templates = get_portfolio_templates();
	ksort( $templates );
	foreach (array_keys( $templates ) as $template )
		: if ( $default == $templates[$template] )
			$selected = " selected='selected'";
		else
			$selected = '';
		echo "\n\t<option value='".$templates[$template]."' $selected>$template</option>";
	endforeach;
}
function post_template_dropdown( $default = '' ) {
	$templates = get_post_templates();
	$templates['Default'] = 'default';
	ksort( $templates );
	$templates = array('Default' => $templates['Default']) + $templates;
	foreach (array_keys( $templates ) as $template )
		: if ( $default == $templates[$template] )
			$selected = " selected='selected'";
		else
			$selected = '';
		echo "\n\t<option value='".$templates[$template]."' $selected>$template</option>";
	endforeach;
}

add_action('save_post','save_post_template',10,2);
function save_post_template($post_id,$post) {
	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['post_template_class_nonce'] ) || !wp_verify_nonce( $_POST['post_template_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = $_POST['post_template'];

	/* Get the meta key. */
	$meta_key = '_post_template';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}

// ONLY MOVIE CUSTOM TYPE POSTS
add_filter('manage_portfolio_posts_columns', 'ST4_columns_head_only_movies', 10);
add_action('manage_portfolio_posts_custom_column', 'ST4_columns_content_only_movies', 10, 2);
// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
function ST4_columns_head_only_movies($defaults) {
	$columns['cb'] = __('Bulk actions', 'studiofolio' );
	$columns['title'] = __('Title', 'studiofolio' );
	$columns['size'] = 'Thumbnail size';
	$columns['portfolio_page'] = 'Portfolio page';
	$columns['page_template'] = 'Layout';
	$columns['date'] = _x('Date', 'column name', 'studiofolio');
	return $columns;
}
function ST4_columns_content_only_movies($column_name, $post_ID) {
	if ($column_name == 'size') {
		$p_meta = get_post_meta($post_ID, 'size', true);

		if( $p_meta && isset($p_meta)) echo $p_meta;
		else echo '-';
	}
	if ($column_name == 'portfolio_page') {
		$p_meta = get_post_meta($post_ID, 'portfolio_page', true);

		if( $p_meta && isset($p_meta)) echo get_the_title($p_meta);
		else echo '-';
	}
	if ($column_name == 'page_template') {
		$template = get_post_meta($post_ID, '_post_template', true);

		if( $template ) echo basename($template);
		else echo '-';
	}
}

/**
 * jQuery show/hide for meta box, post editor meta box
 *
 * Hides/Shows boxes on demand - depending on your selection inside the post formats meta box
 */
function wpse14707_scripts()
{
	wp_enqueue_script( 'jquery' );

	$script = '<script type="text/javascript">
        jQuery( document ).ready( function($)
            {
                $( "#post_format_box" ).addClass( "hidden" );

                $( "input#post-format-0" ).change( function() {
                    $( "#postdivrich" ).removeClass( "hidden" );
                    $( "#post_format_box" ).addClass( "hidden" );
                } );

                $( "input:not(#post-format-0)" ).change( function() {
                    $( "#postdivrich" ).addClass( "hidden" );
                    $( "#post_format_box" ).removeClass( "hidden" );
                } );

                $( "input[name=\"post_format\"]" ).click( function() {
                    var mydiv = $(this).attr( "id" ).replace( "post-format-", "" );
                    $( "#post_format_box div.inside div" ).addClass("hidden");
                    $( "#post_format_box div.inside div#"+mydiv).removeClass( "hidden" );
                } );
            }
        );
    </script>';

	return print $script;
}
//add_action( 'admin_footer', 'wpse14707_scripts' );

function add_featureslide() {

	global $data; //fetch options stored in $data
	if ($data['slideshow_on'])  { ?>

		<div class="casebox middlearrow">
      		<?php

		$slides = $data['slideshow']; //get the slides array
		foreach ($slides as $slide) {
			if ($slide['type'] == 'video') $withvideo = true;
		} ?>
      		<div class="slidescontainer<?php if (count($slides) == 1) echo (' single'); if ($data['slideshow_full']) echo ' fullslider'; ?>">
				<ul id="featureslider" class="<?php if ($withvideo) echo 'wvideo'; ?>"> <?php
		foreach ($slides as $slide) {
			if ($slide['type'] == 'html') {
				?><li class="slidetext"><?php echo '<table><tr><td><div>' . $slide['htmltext'] . '</div></td></tr></table>'; ?></li><?php
			}
			if ($slide['type'] == 'image') {
				$imagesize = egogetimagesize($slide['url']);
				$width = $imagesize['width'];
				$height = $imagesize['height'];

?>
							<li><img src="<?php echo $slide['url']; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="" /><?php if (!empty($slide['title']) || !empty($slide['description']) || !empty($slide['link'])) { ?><div class="slidedesc"><div class="container"><h1><?php echo $slide['title']; ?></h1><p><?php echo $slide['description']; ?></p><p><a href="<?php echo $slide['link']; ?>"><?php echo (!empty($slide['linktext'])) ? $slide['linktext'] : $slide['link']; ?></a></p></div></div><?php } ?></li><?php
			}
			if ($slide['type'] == 'video') { ?>
							<li class="videoelement"><div class="videocontainer"><?php echo do_shortcode($slide['videosource']); ?></div></li>
						<?php }
		} ?>
			    </ul>
		    </div>
	     </div>

		<?php } else { ?>

		<div class="<?php echo WRAP_CLASSES; ?>">
			<div id="bigtext"<?php if ($data['bigtext']) echo ' class="bigtext-plugin"'; ?>><?php echo $data['html_text_feature']; ?></div>
		</div>

		<?php }
}

add_action('studiofolio_featureslide', 'add_featureslide');

/*
Plugin Name: WPB Add Instagram oEmbed
Description: Adds oEmbed support for Instagram
Author: Syed Balkhi
Author URI: http://www.wpbeginner.com
*/

// Add Instagram oEmbed
function wpb_oembed_instagram(){
	wp_oembed_add_provider( 'http://instagr.am/*/*', 'http://api.instagram.com/oembed' );
	wp_oembed_add_provider( 'http://www.instagram.com/*/*', 'http://api.instagram.com/oembed' );
}
add_action('init','wpb_oembed_instagram');

/* ==============================
  TINYMCE SHORTCODE BUTTONS
  =========================================================== */

add_action('init', 'tmce_button');

function tmce_button() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'add_plugin');
        add_filter('mce_buttons_3', 'third_row');
    }
}

function third_row($buttons) {
    array_push($buttons, "", "beginrw");
    array_push($buttons, "|", "onecl");
    array_push($buttons, "twocl");
    array_push($buttons, "threecl");
    array_push($buttons, "fourcl");
    array_push($buttons, "fivecl");
    array_push($buttons, "sixcl");
    array_push($buttons, "sevencl");
    array_push($buttons, "eightcl");
    array_push($buttons, "ninecl");
    array_push($buttons, "elevencl");
    array_push($buttons, "twelvecl");
    array_push($buttons, "|", "endrw");
    array_push($buttons, "|", "tabs");
    array_push($buttons, "accordion");
    array_push($buttons, "infobox");
    array_push($buttons, "evidence");
    
    return $buttons;
}

function add_plugin($plugin_array) {
    $plugin_array['beginrw'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['onecl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['twocl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['threecl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['fourcl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['fivecl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['sixcl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['sevencl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['eightcl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['ninecl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['tencl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['elevencl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['twelvecl'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['endrw'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['tabs'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['accordion'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['infobox'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';
    $plugin_array['evidence'] = get_template_directory_uri() . '/lib/admin/custombuttons.js';

    return $plugin_array;
}