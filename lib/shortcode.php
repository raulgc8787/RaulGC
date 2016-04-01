<?php

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 12);

/* ====================
  COLUMNS
  ============================================================= */

function beginrow($atts, $content = null) {
	return '<div class="row-fluid show-grid scaff">';
}

function onecolumn($atts, $content = null) {
	return '<div class="span1 inner-scaff">' . do_shortcode($content) . '</div>';
}

function twocolumns($atts, $content = null) {
	return '<div class="span2 inner-scaff">' . do_shortcode($content) . '</div>';
}

function threecolumns($atts, $content = null) {
	return '<div class="span3 inner-scaff">' . do_shortcode($content) . '</div>';
}

function fourcolumns($atts, $content = null) {
	return '<div class="span4 inner-scaff">' . do_shortcode($content) . '</div>';
}

function fivecolumns($atts, $content = null) {
	return '<div class="span5 inner-scaff">' . do_shortcode($content) . '</div>';
}

function sixcolumns($atts, $content = null) {
	return '<div class="span6 inner-scaff">' . do_shortcode($content) . '</div>';
}

function sevencolumns($atts, $content = null) {
	return '<div class="span7 inner-scaff">' . do_shortcode($content) . '</div>';
}

function eightcolumns($atts, $content = null) {
	return '<div class="span8 inner-scaff">' . do_shortcode($content) . '</div>';
}

function ninecolumns($atts, $content = null) {
	return '<div class="span9 inner-scaff">' . do_shortcode($content) . '</div>';
}

function tencolumns($atts, $content = null) {
	return '<div class="span10 inner-scaff">' . do_shortcode($content) . '</div>';
}

function elevencolumns($atts, $content = null) {
	return '<div class="span11 inner-scaff">' . do_shortcode($content) . '</div>';
}

function twelvecolumns($atts, $content = null) {
	return '<div class="span12 inner-scaff">' . do_shortcode($content) . '</div>';
}

function endrow($atts, $content = null) {
	return '</div>';
}

function infobox($atts, $content = null) {
	return '<div class="info-box">' . do_shortcode($content) . '</div>';
}

function evidence($atts, $content = null) {
	return '<div class="evidence">' . do_shortcode($content) . '</div>';
}

function tabgroup( $atts, $content ){
	$GLOBALS['tab_count'] = 0;

	do_shortcode( $content );

	if( is_array( $GLOBALS['tabs'] ) ){
		foreach( $GLOBALS['tabs'] as $tab ){
			$statediv = !empty($tab['state']) ? $tab['state'] : 'fade'; 
			$tabs[] = '<li class="'.$tab['state'].'"><a href="#'.sanitize_title($tab['title']).'" class="tab-toggle">'.$tab['title'].'</a></li>';
			$panes[] = '<div id="'.sanitize_title($tab['title']).'" class="tab-pane '.$statediv.'">'.$tab['content'].'</div>';
		}
		$return = "\n".'<div class="tabs-group"><ul class="nav nav-tabs" data-tabs="tabs">'.implode( "\n", $tabs ).'</ul>'."\n".'<div class="tab-content">'.implode( "\n", $panes ).'</div></div>'."\n";
	}
	return $return;
}

add_shortcode( 'tab', 'tabs' );
function tabs( $atts, $content ){
	extract(shortcode_atts(array(
				'title' => 'Tab %d',
				'state' => ''
			), $atts));

	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'state' => sprintf( $state, $GLOBALS['tab_count'] ), 'content' =>  do_shortcode($content ));

	$GLOBALS['tab_count']++;
}

function accordiongroup( $atts, $content ){
	$GLOBALS['accordion_count'] = 0;
	$GLOBALS['accordions'] = array();
	extract(shortcode_atts(array(
		'id'	=> '',
		'title' => ''
	), $atts));
	do_shortcode( $content );
	if( is_array( $GLOBALS['accordions'] ) ){
		foreach( $GLOBALS['accordions'] as $tab ){
			$activepan = ($tab['state'] == 'active') ? 'in':'';
			$panes[] = '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" href="#'.$tab['id'].'" data-toggle="collapse" data-parent="#'.$id.'">'.$tab['title'].'</a></div><div id="'.$tab['id'].'" class="accordion-body collapse '.$activepan.'"><div class="accordion-inner">'.do_shortcode($tab['content']).'</div></div></div>';
		}
		$return = "\n".'<div id="'.$id.'" class="accordion">'."\n".implode( "\n", $panes ).'</div>'."\n";
	}
	return $return;
}

add_shortcode( 'accordion', 'accordions' );

function accordions( $atts, $content ){
	extract(shortcode_atts(array(
		'title' 	=> 'Accordion %d',
		'id' 		=> '',
		'state' 	=> ''
	), $atts));

	$x = $GLOBALS['accordion_count'];
	$GLOBALS['accordions'][$x] = array( 'title' => sprintf( $title, $GLOBALS['accordion_count'] ), 'id' => sprintf( $id, $GLOBALS['accordion_count'] ), 'state' => sprintf( $state, $GLOBALS['accordion_count'] ), 'content' =>  $content );

	$GLOBALS['accordion_count']++;
}

function egoplayer($atts, $content) {
	extract(shortcode_atts(array(
		'src' 			=> '',
		'ratio'			=> '16:9',
		'poster'		=> ''
	), $atts));
	
	$getsource = explode(',', $src);
	foreach ($getsource as &$value) {
		$value = str_replace(' ','',$value);
		
    	if (is_numeric($value) ) $video_url = wp_get_attachment_url( $value );
    	else $video_url = $value;
    	$info = pathinfo($video_url);
    	$video_ext = strtolower($info['extension']);
    	$supplied[] = $video_ext;
    	$output[] = $video_ext.': "'.$video_url.'"';
    }
	
	if (count($output) > 1) $mediasrc = join(',',$output) . ',';
	else $mediasrc = $output[0] . ',';
	if (count($supplied) > 1) $mediatype = join(',',$supplied) . ',';
	else $mediatype = $supplied[0] . ',';
	
	$getratio = explode(':', $ratio);
	$ratio = $getratio[1] / $getratio[0];

	$errmess = '<div class="jp-no-solution">
					<span>Update Required<br />To play the media you will need to either update your browser to a recent version or update your Flash plugin.</span>
				</div>';
	
	$image_attributes = wp_get_attachment_image_src( $poster, 'full' );
	$poster_url = $image_attributes[0];
	$id = rand(0, 10000);
	$output = '<div class="videocontainer"><!-- jPlayer instance --><script type="text/javascript">jQuery(document).ready(function() {
	jQuery("#jquery_jplayer_'.$id.'").jPlayer({
		ready: function() {
			jQuery(this).jPlayer("setMedia", {
				'.$mediasrc.'
				poster: "'.$poster_url.'"
			});
			resizePoster();
		},
		swfPath: "'.get_template_directory_uri().'/libs/js/",
		cssSelectorAncestor: "#jp_container_'.$id.'",
		supplied: "'.$mediatype.'all",
		solution: "html, flash",
		size: {
			width: "100%",
			height: "100%"
		}
	});
});</script><div id="jp_container_'.$id.'" class="jp-video-full"><div class="jp-type-single" data-ratio="'.$ratio.'"><div id="jquery_jplayer_'.$id.'" class="jp-jplayer"></div><div class="jp-gui"><div class="jp-video-play"><a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a></div><div class="jp-interface"><div class="jp-controls-holder"><ul class="jp-controls"><li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li><li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li></ul><div class="jp-current-time"></div><div class="jp-progress"><div class="jp-seek-bar"><div class="jp-play-bar"></div></div></div><div class="jp-duration"></div><ul class="jp-toggles"><li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li><li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li><li><div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div></li><li class="fullscreen-btn"><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a></li><li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a></li></ul></div></div></div>'.$errmess.'</div></div></div>';

	return $output;
	
}

add_shortcode('beginrw', 'beginrow');
add_shortcode('onecl', 'onecolumn');
add_shortcode('twocl', 'twocolumns');
add_shortcode('threecl', 'threecolumns');
add_shortcode('fourcl', 'fourcolumns');
add_shortcode('fivecl', 'fivecolumns');
add_shortcode('sixcl', 'sixcolumns');
add_shortcode('sevencl', 'sevencolumns');
add_shortcode('eightcl', 'eightcolumns');
add_shortcode('ninecl', 'ninecolumns');
add_shortcode('tencl', 'tencolumns');
add_shortcode('elevencl', 'elevencolumns');
add_shortcode('twelvecl', 'twelvecolumns');
add_shortcode('endrw', 'endrow');

// Twitter Bootstrap Tabs grabbed from www.friiitz.com
add_shortcode( 'tabgroup', 'tabgroup' );
add_shortcode( 'accordiongroup', 'accordiongroup' );
add_shortcode( 'infobox', 'infobox' );
add_shortcode( 'evidence', 'evidence' );
add_shortcode( 'egoplayer', 'egoplayer' );


