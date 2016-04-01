<?php
/*-----------------------------------------------------------------------------------*/
/* Title: Aquagraphite Options Framework
/* Author: Syamil MJ
/* Author URI: http://aquagraphite.com
/* Version: 1.3
/* License: WTFPL - http://sam.zoy.org/wtfpl/
/* Credits:	Thematic Options Panel http://wptheming.com/2010/11/thematic-options-panel-v2/
			KIA Thematic Options Panel https://github.com/helgatheviking/thematic-options-KIA
			Woo Themes http://woothemes.com/
			Option Tree http://wordpress.org/extend/plugins/option-tree/
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Create the Options_Machine object - undsgn_admin_init */
/*-----------------------------------------------------------------------------------*/

function undsgn_admin_init() {
	// Rev up the Options Machine
	global $undsgn_options, $options_machine;
	$options_machine = new Options_Machine($undsgn_options);


	//if reset is pressed->replace options with defaults
	if ( isset($_REQUEST['page']) && $_REQUEST['page'] == 'undsgnoptions' ) {
		if (isset($_REQUEST['undsgn_reset']) && 'reset' == $_REQUEST['undsgn_reset']) {

			$nonce=$_POST['security'];

			if (!wp_verify_nonce($nonce, 'undsgn_ajax_nonce') ) {

				header('Location: themes.php?page=undsgnoptions&reset=error');
				die('Security Check');

			} else {

				$defaults = (array) $options_machine->Defaults;
				update_option(OPTIONS,$defaults);

				header('Location: themes.php?page=undsgnoptions&reset=true');
				die($options_machine->Defaults);
			}
		}
	}
}
add_action('admin_init','undsgn_admin_init');

/*-----------------------------------------------------------------------------------*/
/* Undsgn Framework Admin Interface - undsgn_add_admin */
/*-----------------------------------------------------------------------------------*/

function undsgn_add_admin() {

	$undsgn_page = add_menu_page(
		ucfirst(THEMENAME),           // Name of page
		ucfirst(THEMENAME),           // Label in menu
		'edit_theme_options',                    // Capability required
		'undsgnoptions',      // Menu slug, used to uniquely identify the page
		'undsgn_options_page',   // Function that renders the options page
		'images/generic.png',       // Menu icon
		61
	);

	$subpageoption = add_submenu_page('undsgnoptions', 'Theme Options', 'Theme Options', 'edit_theme_options', 'undsgnoptions', 'undsgn_options_page' );

	// Add framework functionaily to the head individually
	add_action("admin_print_scripts-$undsgn_page", 'undsgn_load_only');
	add_action("admin_print_styles-$undsgn_page",'undsgn_style_only');
	add_action( "admin_print_styles-$undsgn_page", 'undsgn_mlu_css', 0 );
	add_action( "admin_print_scripts-$undsgn_page", 'undsgn_mlu_js', 0 );

	if ( ! $undsgn_page )
		return;

}

add_action('admin_menu', 'undsgn_add_admin');


/*-----------------------------------------------------------------------------------*/
/* Build the Options Page - undsgn_options_page */
/*-----------------------------------------------------------------------------------*/

function undsgn_options_page(){
	global $options_machine;
	/*
	//for debugging
	$data = get_option(OPTIONS);
	print_r($data);
	*/
?>

<div class="wrap" id="undsgn_container">
  <div id="undsgn-popup-save" class="undsgn-save-popup">
    <div class="undsgn-save-save">Options Updated</div>
  </div>
  <div id="undsgn-popup-reset" class="undsgn-save-popup">
    <div class="undsgn-save-reset">Options Reset</div>
  </div>
   <div id="undsgn-popup-fail" class="undsgn-save-popup">
    <div class="undsgn-save-fail">Error!</div>
  </div>

  <form id="undsgn_form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >
    <div id="header">
      <div class="logo">
        <?php $the_theme = wp_get_theme(); ?>
        <h2><?php echo $the_theme->get( 'Name' ); ?> <?php echo $the_theme->get( 'Version' ); ?></h2>
      </div>
	  <div id="js-warning">Warning- This options panel will not work properly without javascript!</div>
      <div class="icon-option"> </div>
      <div class="clear"></div>
    </div>

	<div id="info_bar">
	<a><div id="expand_options" class="expand">Expand</div></a>
    <img style="display:none" src="<?php echo ADMIN_DIR; ?>images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
    <input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('undsgn_ajax_nonce'); ?>" />
	<button id ="undsgn_save" type="button" class="button-primary"><?php _e('Save All Changes', 'undsgnoptions' );?></button>
	</div><!--.info_bar-->

    <div id="main">
      <div id="undsgn-nav">
        <ul>
          <?php echo $options_machine->Menu ?>
        </ul>
      </div>
      <div id="content"> <?php echo $options_machine->Inputs /* Settings */ ?> </div>
      <div class="clear"></div>
    </div>
	<div class="save_bar">
    <img style="display:none" src="<?php echo ADMIN_DIR; ?>images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
    <input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('undsgn_ajax_nonce'); ?>" />
	<input type="hidden" name="undsgn_reset" value="reset" />

	<button id ="undsgn_save" type="submit" class="button-primary"><?php _e('Save All Changes', 'undsgnoptions' );?></button>
	<button id ="undsgn_reset" type="submit" class="button submit-button reset-button" ><?php _e('Options Reset', 'undsgnoptions');?></button>
	</div><!--.save_bar-->

  </form>


<?php  if (!empty($update_message)) echo $update_message; ?>
<div style="clear:both;"></div>

</div><!--wrap-->
<?php

}


/*-----------------------------------------------------------------------------------*/
/* Load required styles for Options Page - undsgn_style_only */
/*-----------------------------------------------------------------------------------*/

function undsgn_style_only(){
	wp_enqueue_style('admin-style', ADMIN_DIR . 'admin-style.css');
	wp_enqueue_style('color-picker', ADMIN_DIR . 'css/colorpicker.css');
}

/*-----------------------------------------------------------------------------------*/
/* Load required javascripts for Options Page - undsgn_load_only */
/*-----------------------------------------------------------------------------------*/

function undsgn_load_only() {

	add_action('admin_head', 'undsgn_admin_head');

	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');
	wp_register_script('jquery-input-mask', ADMIN_DIR .'js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_register_script('tipsy', ADMIN_DIR .'js/jquery.tipsy.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	wp_enqueue_script('tipsy');
	wp_enqueue_script('color-picker', ADMIN_DIR .'js/colorpicker.js', array('jquery'));
	wp_enqueue_script('ajaxupload', ADMIN_DIR .'js/ajaxupload.js', array('jquery'));
	wp_enqueue_script('cookie', ADMIN_DIR . '/js/cookie.js', 'jquery');
	// Registers custom scripts for the Media Library AJAX uploader.
}


function undsgn_admin_head() {

	global $data; ?>

	<script type="text/javascript" language="javascript">

	jQuery.noConflict();
	jQuery(document).ready(function($){

	//(un)fold options in a checkbox-group
  	jQuery('.fld').click(function() {
    	var $fold='.f_'+this.id;
    	//var $unfold='.u_'+this.id;
    	$($fold).slideToggle('normal', "swing");
    	//$($unfold).slideToggle('normal', "swing");
  	});

	//hide hidden section on page load.
	jQuery('#section-body_bg, #section-body_bg_custom, #section-body_bg_properties').hide();

	//delays until AjaxUpload is finished loading
	//fixes bug in Safari and Mac Chrome
	if (typeof AjaxUpload != 'function') {
			return ++counter < 6 && window.setTimeout(init, counter * 500);
	}
	//hides warning if js is enabled
	$('#js-warning').hide();

	//Tabify Options
	$('.group').hide();

	// Display last current tab
	if ($.cookie("undsgn_current_opt") === null) {
		$('.group:first').fadeIn();
		$('#undsgn-nav li:first').addClass('current');
	} else {

		var hooks = <?php
	$hooks = undsgn_get_header_classes_array();
	echo json_encode($hooks);
	?>;

		$.each(hooks, function(key, value) {

			if ($.cookie("undsgn_current_opt") == '#undsgn-option-'+ value) {
				$('.group#undsgn-option-' + value).fadeIn();
				$('#undsgn-nav li.' + value).addClass('current');
			}

		});

	}

	//Current Menu Class
	$('#undsgn-nav li a').click(function(evt){
	// event.preventDefault();

		$('#undsgn-nav li').removeClass('current');
		$(this).parent().addClass('current');

		var clicked_group = $(this).attr('href');

		$.cookie('undsgn_current_opt', clicked_group, { expires: 7, path: '/' });

		$('.group').hide();

		$(clicked_group).fadeIn();
		return false;

	});

	//Expand Options
	var flip = 0;

	$('#expand_options').click(function(){
		if(flip == 0){
			flip = 1;
			$('#undsgn_container #undsgn-nav').hide();
			$('#undsgn_container #content').width(755);
			$('#undsgn_container .group').add('#undsgn_container .group h2').show();

			$(this).removeClass('expand');
			$(this).addClass('close');
			$(this).text('Close');

		} else {
			flip = 0;
			$('#undsgn_container #undsgn-nav').show();
			$('#undsgn_container #content').width(595);
			$('#undsgn_container .group').add('#undsgn_container .group h2').hide();
			$('#undsgn_container .group:first').show();
			$('#undsgn_container #undsgn-nav li').removeClass('current');
			$('#undsgn_container #undsgn-nav li:first').addClass('current');

			$(this).removeClass('close');
			$(this).addClass('expand');
			$(this).text('Expand');

		}

	});



	// Reset Message Popup
	var reset = "<?php if(isset($_REQUEST['reset'])) echo $_REQUEST['reset']; ?>";

	if ( reset.length ){
		if ( reset == 'true') {
			var message_popup = $('#undsgn-popup-reset');
		} else {
			var message_popup = $('#undsgn-popup-fail');
	}
		message_popup.fadeIn();
		window.setTimeout(function(){
	    message_popup.fadeOut();
		}, 2000);
	}

	//Update Message popup
	$.fn.center = function () {
		this.animate({"top":( $(window).height() - this.height() - 200 ) / 2+$(window).scrollTop() + "px"},100);
		this.css("left", 250 );
		return this;
	}


	$('#undsgn-popup-save').center();
	$('#undsgn-popup-reset').center();
	$('#undsgn-popup-fail').center();

	$(window).scroll(function() {
		$('#undsgn-popup-save').center();
		$('#undsgn-popup-reset').center();
		$('#undsgn-popup-fail').center();
	});


	//Masked Inputs (images as radio buttons)
	$('.undsgn-radio-img-img').click(function(){
		$(this).parent().parent().find('.undsgn-radio-img-img').removeClass('undsgn-radio-img-selected');
		$(this).addClass('undsgn-radio-img-selected');
	});
	$('.undsgn-radio-img-label').hide();
	$('.undsgn-radio-img-img').show();
	$('.undsgn-radio-img-radio').hide();

	//Masked Inputs (background images as radio buttons)
	$('.undsgn-radio-tile-img').click(function(){
		$(this).parent().parent().find('.undsgn-radio-tile-img').removeClass('undsgn-radio-tile-selected');
		$(this).addClass('undsgn-radio-tile-selected');
	});
	$('.undsgn-radio-tile-label').hide();
	$('.undsgn-radio-tile-img').show();
	$('.undsgn-radio-tile-radio').hide();

	// COLOR Picker
	$('.colorSelector').each(function(){
		var Othis = this; //cache a copy of the this variable for use inside nested function

		$(this).ColorPicker({
				color: '<?php if(isset($color)) echo $color; ?>',
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$(Othis).children('div').css('backgroundColor', '#' + hex);
					$(Othis).next('input').attr('value','#' + hex);

				}
		});

	}); //end color picker

	//AJAX Upload
	function undsgn_image_upload() {
	$('.image_upload_button').each(function(){

	var clickedObject = $(this);
	var clickedID = $(this).attr('id');

	var nonce = $('#security').val();

	new AjaxUpload(clickedID, {
		action: ajaxurl,
		name: clickedID, // File upload name
		data: { // Additional data to send
			action: 'undsgn_ajax_post_action',
			type: 'upload',
			security: nonce,
			data: clickedID },
		autoSubmit: true, // Submit file after selection
		responseType: false,
		onChange: function(file, extension){},
		onSubmit: function(file, extension){
			clickedObject.text('Uploading'); // change button text, when user selects file
			this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
			interval = window.setInterval(function(){
				var text = clickedObject.text();
				if (text.length < 13){	clickedObject.text(text + '.'); }
				else { clickedObject.text('Uploading'); }
				}, 200);
		},
		onComplete: function(file, response) {
			window.clearInterval(interval);
			clickedObject.text('Upload Image');
			this.enable(); // enable upload button


			// If nonce fails
			if(response==-1){
				var fail_popup = $('#undsgn-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
				fail_popup.fadeOut();
				}, 2000);
			}

			// If there was an error
			else if(response.search('Upload Error') > -1){
				var buildReturn = '<span class="upload-error">' + response + '</span>';
				$(".upload-error").remove();
				clickedObject.parent().after(buildReturn);

				}
			else{
				var buildReturn = '<img class="hide undsgn-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';

				$(".upload-error").remove();
				$("#image_" + clickedID).remove();
				clickedObject.parent().after(buildReturn);
				$('img#image_'+clickedID).fadeIn();
				clickedObject.next('span').fadeIn();
				clickedObject.parent().prev('input').val(response);
			}
		}
	});

	});

	}

	undsgn_image_upload();

	//AJAX Remove (clear option value)
	$('.image_reset_button').live('click', function(){

		var clickedObject = $(this);
		var clickedID = $(this).attr('id');
		var theID = $(this).attr('title');

		var nonce = $('#security').val();

		var data = {
			action: 'undsgn_ajax_post_action',
			type: 'image_reset',
			security: nonce,
			data: theID
		};

		$.post(ajaxurl, data, function(response) {

			//check nonce
			if(response==-1){ //failed

				var fail_popup = $('#undsgn-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
					fail_popup.fadeOut();
				}, 2000);
			}

			else {

				var image_to_remove = $('#image_' + theID);
				var button_to_hide = $('#reset_' + theID);
				image_to_remove.fadeOut(500,function(){ $(this).remove(); });
				button_to_hide.fadeOut();
				clickedObject.parent().prev('input').val('');
			}


		});

	});

	/* Style Select */

	(function ($) {
	styleSelect = {
		init: function () {
		$('.select_wrapper').each(function () {
			$(this).prepend('<span>' + $(this).find('.select option:selected').text() + '</span>');
		});
		$('.select').live('change', function () {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		$('.select').bind($.browser.msie ? 'click' : 'change', function(event) {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		}
	};
	$(document).ready(function () {
		styleSelect.init()
	})
	})(jQuery);


	//----------------------------------------------------------------*/
	// Aquagraphite Slider MOD
	//----------------------------------------------------------------*/

	/* Slider Interface */

		//Hide (Collapse) the toggle containers on load
		$(".slide_body").hide();

		//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
		$(".slide_edit_button").live( 'click', function(){
			$(this).parent().toggleClass("active").next().slideToggle("fast");
			return false; //Prevent the browser jump to the link anchor
		});

		// Update slide title upon typing
		function update_slider_title(e) {
			var element = e;
			if ( this.timer ) {
				clearTimeout( element.timer );
			}
			this.timer = setTimeout( function() {
				$(element).parent().prev().find('strong').text( element.value );
			}, 100);
			return true;
		}

		$('.undsgn-slider-title').live('keyup', function(){
			update_slider_title(this);
		});

		$('.type_select').live('change', function () {
			$('option', this).each(function () {
				if ($(this).attr('selected') == 'selected') $('div.' + $(this).attr('id')).slideDown('normal', "swing");
				else $('div.' + $(this).attr('id')).slideUp('normal', "swing");
			});
		});


	/* Remove individual slide */

		$('.slide_delete_button').live('click', function(){
		// event.preventDefault();
		var agree = confirm("Are you sure you wish to delete this slide?");
			if (agree) {
				var $trash = $(this).parents('li');
				//$trash.slideUp('slow', function(){ $trash.remove(); }); //chrome + confirm bug made slideUp not working...
				$trash.animate({
						opacity: 0.25,
						height: 0,
					}, 500, function() {
						$(this).remove();
				});
				return false; //Prevent the browser jump to the link anchor
			} else {
			return false;
			}
		});

	/* Add new slide */

	$(".slide_add_button").live('click', function(){
		var slidesContainer = $(this).prev();
		var sliderId = slidesContainer.attr('id');
		var sliderInt = $('#'+sliderId).attr('rel');

		var numArr = $('#'+sliderId +' li').find('.order').map(function() {
			var str = this.id;
			str = str.replace(/\D/g,'');
			str = parseFloat(str);
			return str;
		}).get();

		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0};
		var newNum = maxNum + 1;

		var newSlide = '<li><div class="slide_header"><strong>Slide ' + newNum + '</strong><input type="hidden" class="slide of-input order" name="' + sliderId + '[' + newNum + '][order]" id="' + sliderId + '_slide_order-' + newNum + '" value="' + newNum + '"><a class="slide_edit_button" href="#">Edit</a></div><div class="slide_body" style="display: none; "><select class="select of-input type_select" name="' + sliderId + '[' + newNum + '][type]" id="' + sliderId + '_' + newNum + '_type"><option id="' + sliderId + '_' + newNum + '_imagecont" value="image">Image</option><option id="' + sliderId + '_' + newNum + '_htmlcont" value="html">Html</option><option id="' + sliderId + '_' + newNum + '_videocont" value="video">Video</option></select><div class="' + sliderId + '_' + newNum + '_imagecont"><label>Title</label><input class="slide of-input of-slider-title" name="' + sliderId + '[' + newNum + '][title]" id="' + sliderId + '_' + newNum + '_slide_title" value=""><label>Image URL</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][url]" id="' + sliderId + '_' + newNum + '_slide_url" value=""><div class="upload_button_div"><span class="button media_upload_button" id="' + sliderId + '_' + newNum + '" rel="'+sliderInt+'">Upload</span><span class="button mlu_remove_button hide" id="reset_' + sliderId + '_' + newNum + '" title="' + sliderId + '_' + newNum + '">Remove</span></div><div class="screenshot"></div><label>Link URL (optional)</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][link]" id="' + sliderId + '_' + newNum + '_slide_link" value=""><label>Link text (optional)</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][linktext]" id="' + sliderId + '_' + newNum + '_slide_linktext" value=""><label>Description (optional)</label><textarea class="slide of-input" name="' + sliderId + '[' + newNum + '][description]" id="' + sliderId + '_' + newNum + '_slide_description" cols="8" rows="8"></textarea></div><div style="display: none;" class="' + sliderId + '_' + newNum + '_htmlcont"><label>HTML Text</label><textarea class="slide of-input" name="' + sliderId + '[' + newNum + '][htmltext]" id="' + sliderId + '_' + newNum + '_htmltext" cols="8" rows="8"></textarea></div><div style="display: none;" class="' + sliderId + '_' + newNum + '_videocont"><label>Video includer</label><textarea class="slide of-input" name="' + sliderId + '[' + newNum + '][videosource]" id="' + sliderId + '_' + newNum + '_videosource" cols="8" rows="8"></textarea><p class="example">Code Examples<br/><br />For external embedded videos just paste the code: <code>&lt;iframe src="http://player.vimeo.com/video/xxxxxxx" width="xxx" height="xxx" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen&gt;&lt;/iframe&gt;</code><br/><br />For internal videos use this shortcode:<br /><code>[egoplayer src="xx(ID or URL), xx(ID or URL), xx(ID or URL)" poster="xx(ID)" ratio="xx:x"]</code></p></div><a class="slide_delete_button" href="#">Delete</a><div class="clear"></div></div></li>';

		slidesContainer.append(newSlide);

		undsgn_image_upload(); // re-initialise upload image..

		return false; //prevent jumps, as always..
	});

	/* Add new front elemnt */

	$(".front_add_content").live('click', function(){
		$(this).attr("disabled", "disabled").addClass('loading');

		var slidesContainer = $(this).prev();
		var sliderId = slidesContainer.attr('id');
		var sliderInt = $('#'+sliderId).attr('rel');

		var numArr = $('#'+sliderId +' li').find('.order').map(function() {
			var str = this.id;
			str = str.replace(/\D/g,'');
			str = parseFloat(str);
			return str;
		}).get();

		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0};

		var nonce = $('#security').val();

		var data = {
			type: 'POST',
			dataType: 'json',
			action: 'load_post_items',
			security: nonce
		};

		$.post(ajaxurl, data, function(response) {

			$(".front_add_content").removeAttr("disabled").removeClass('loading');
			//check nonce
			if(response==-1){ //failed

				var fail_popup = $('#undsgn-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
					fail_popup.fadeOut();
				}, 2000);
			}

			else {

				var jsonresp = $.parseJSON( response );

				var items = [];


				$.each(jsonresp, function(key, val) {

					if ($('input.undsgn-slider-ID[value="'+val['ID']+'"]', slidesContainer).length == 0) {
						newNum = maxNum = maxNum + 1;
						var imgurl;
						if (val['imageurl'][0]) imgurl = '<img src="'+val['imageurl'][0]+'" alt="" />';
						else imgurl = '<span>NO IMAGE</span>';
				    	var newSlide = '<li><div class="imgcont">'+imgurl+'</div><div class="slide_header"><span class="title">' + val['title'] + '</span><span class="category">'+val['type']+'</span><input type="hidden" class="slide undsgn-input order" name="' + sliderId + '[' + newNum + '][order]" id="' + sliderId + '_slide_order-' + newNum + '" value="' + newNum + '"><input  type="hidden" class="slide undsgn-input undsgn-slider-ID" name="' + sliderId + '[' + newNum + '][ID]" id="' + sliderId + '_' + newNum + '_slide_ID" value="' + val['ID'] + '"><input type="checkbox" class="prettycheck" name="included" id="included-'+ newNum +'" value="1" /><a href="#" class="toggle" ref="included-'+ newNum +'"></a><a class="slide_delete_button" href="#">Delete</a><div class="clear"></div></div></li>';

				    	slidesContainer.prepend(newSlide);
					}

			    });
			}

		});



		return false; //prevent jumps, as always..
	});

	/* Add new list */

	$(".list_add_button").live('click', function(){
		var slidesContainer = $(this).prev();
		var sliderId = slidesContainer.attr('id');
		var sliderInt = $('#'+sliderId).attr('rel');

		var numArr = $('#'+sliderId +' li').find('.order').map(function() {
			var str = this.id;
			str = str.replace(/\D/g,'');
			str = parseFloat(str);
			return str;
		}).get();

		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0};
		var newNum = maxNum + 1;

		var newSlide = '<li class="dynalist"><input type="hidden" class="slide of-input order" name="' + sliderId + '[' + newNum + '][order]" id="' + sliderId + '_slide_order-' + newNum + '" value="' + newNum + '"><label>Label</label><input class="slide of-input of-slider-title" name="' + sliderId + '[' + newNum + '][title]" id="' + sliderId + '_' + newNum + '_slide_title" value=""><a class="slide_delete_button" href="#">Delete</a></li>';

		slidesContainer.append(newSlide);

		undsgn_image_upload(); // re-initialise upload image..

		return false; //prevent jumps, as always..
	});

	// Sort Slides
	jQuery('.slider').find('ul').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).sortable({
			placeholder: "placeholder",
			opacity: 0.6
		});
	});

	/*----------------------------------------------------------------*/
	/*	Aquagraphite Sorter MOD
	/*----------------------------------------------------------------*/
	jQuery('.sorter').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).find('ul').sortable({
			items: 'li',
			placeholder: "placeholder",
			connectWith: '.sortlist_' + id,
			opacity: 0.6,
			update: function() {
				$(this).find('.position').each( function() {

					var listID = $(this).parent().attr('id');
					var parentID = $(this).parent().parent().attr('id');
					parentID = parentID.replace(id + '_', '')
					var optionID = $(this).parent().parent().parent().attr('id');
					$(this).prop("name", optionID + '[' + parentID + '][' + listID + ']');

				});
			}
		});
	});
	
	//font installation
	$('#undsgn_entypo_button').live('click', function(){

		var clickedObject = $(this);
		var clickedID = $(this).attr('id');

		var nonce = $('#security').val();

		var data = {
			action: 'add_entypo',
			security: nonce
		};
		
		$(this).addClass('loading');

		$.post(ajaxurl, data, function(response) {
			
			$('#undsgn_entypo_button').removeClass('loading');
			
			//check nonce
			if(response==-1){ //failed

				var fail_popup = $('#undsgn-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
					fail_popup.fadeOut();
				}, 2000);
			}

			else {

				var success_popup = $('#undsgn-popup-save');
				success_popup.fadeIn();
				window.setTimeout(function(){
					success_popup.fadeOut();
				}, 1000);
			}

		});

	return false;

	});

	/*----------------------------------------------------------------*/
	/*	Aquagraphite Backup & Restore MOD
	/*----------------------------------------------------------------*/
	//backup button
	$('#undsgn_backup_button').live('click', function(){

		var answer = confirm("<?php _e('Click OK to backup your current saved options.', 'undsgnoptions' );?>")

		if (answer){

			var clickedObject = $(this);
			var clickedID = $(this).attr('id');

			var nonce = $('#security').val();

			var data = {
				action: 'undsgn_ajax_post_action',
				type: 'backup_options',
				security: nonce
			};

			$.post(ajaxurl, data, function(response) {

				//check nonce
				if(response==-1){ //failed

					var fail_popup = $('#undsgn-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}

				else {

					var success_popup = $('#undsgn-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}

			});

		}

	return false;

	});

	//restore button
	$('#undsgn_restore_button').live('click', function(){

		var answer = confirm("<?php _e('Warning: All of your current options will be replaced with the data from your last backup! Proceed?', 'undsgnoptions' );?>")

		if (answer){

			var clickedObject = $(this);
			var clickedID = $(this).attr('id');

			var nonce = $('#security').val();

			var data = {
				action: 'undsgn_ajax_post_action',
				type: 'restore_options',
				security: nonce
			};

			$.post(ajaxurl, data, function(response) {

				//check nonce
				if(response==-1){ //failed

					var fail_popup = $('#undsgn-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}

				else {

					var success_popup = $('#undsgn-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}

			});

		}

	return false;

	});

	/* save everything */
	$('#undsgn_save').live('click',function() {

		var nonce = $('#security').val();

		$('.ajax-loading-img').fadeIn();

		$('.frontpagelist li input.prettycheck').each(function() {
			if ($(this).is(":not(:checked)")) $(this).closest('li').remove();
		});
		var serializedReturn = $('#undsgn_form :input[name][name!="security"][name!="undsgn_reset"]').serialize();

		//alert(serializedReturn);

		var data = {
			<?php
	if(isset($_REQUEST['page']) && ($_REQUEST['page'] == 'undsgnoptions')){ ?>
			type: 'save',
			<?php } ?>

			action: 'undsgn_ajax_post_action',
			security: nonce,
			data: serializedReturn
		};

		$.post(ajaxurl, data, function(response) {
			var success = $('#undsgn-popup-save');
			var fail = $('#undsgn-popup-fail');
			var loading = $('.ajax-loading-img');
			loading.fadeOut();

			if (response==1) {
				success.fadeIn();
			} else {
				fail.fadeIn();
			}

			window.setTimeout(function(){
				success.fadeOut();
				fail.fadeOut();
			}, 2000);
		});

	return false;

	});
	
	/**	Ajax Transfer (Import/Export) Option */
	$('#undsgn_import_button').live('click', function(){

		var answer = confirm("Click OK to import options.")

		if (answer){

			var clickedObject = $(this);
			var clickedID = $(this).attr('id');

			var nonce = $('#security').val();

			var import_data = $('#export_data').val();

			var data = {
				action: 'undsgn_ajax_post_action',
				type: 'import_options',
				security: nonce,
				data: import_data
			};

			$.post(ajaxurl, data, function(response) {
				var fail_popup = $('#undsgn-popup-fail');
				var success_popup = $('#undsgn-popup-save');

				//check nonce
				if(response==-1){ //failed
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();                        
					}, 2000);
				}		
				else 
				{
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();                        
					}, 1000);
				}

			});

		}

	return false;

	});

	//confirm reset
	$('#undsgn_reset').click(function() {
		var answer = confirm("<?php _e('Click OK to reset. All settings will be lost!', 'undsgnoptions' );?>")
		if (answer){ 	return true; } else { return false; }
});

	//custom js for checkbox hidden values
	jQuery('#background_image').click(function() {
  		jQuery('#section-body_bg, #section-body_bg_custom, #section-body_bg_properties').fadeToggle(400);
	});

	if (jQuery('#background_image:checked').val() !== undefined) {
		jQuery('#section-body_bg, #section-body_bg_custom, #section-body_bg_properties').show();
	}

	/*----------------------------------------------------------------*/
	/*	Tipsy @since v1.3
	/*----------------------------------------------------------------*/
	if (jQuery().tipsy) {
		$('.typography-size, .typography-height, .typography-face, .typography-style, .undsgn-typography-color').tipsy({
			fade: true,
			gravity: 's',
			opacity: 0.7,
		});
	}

	/*----------------------------------------------------------------*/
	/*	Toggle all checkbox
	/*----------------------------------------------------------------*/

	jQuery('a[ref="undefined"]').live('click', function () {
		var $cont = jQuery(this).closest('.slider');
		if (jQuery(this).prev().is(":checked")) {
			jQuery('.prettycheck', $cont).removeAttr("checked");
			jQuery('.frontpagelist a.toggle', $cont).removeClass("checked");
		} else {
			jQuery('.prettycheck', $cont).attr("checked","true");
			jQuery('.frontpagelist a.toggle', $cont).addClass("checked");
		}

	})

	/*----------------------------------------------------------------*/
	/*	Checkbox styling
	/*----------------------------------------------------------------*/

    /*
      Add toggle switch after each checkbox.  If checked, then toggle the switch.
    */
    jQuery('input[type=checkbox]:not(.unmodify)').after(function(){
       if (jQuery(this).is(":checked")) {
         return "<a href='#' class='toggle checked' ref='"+jQuery(this).attr("id")+"'></a>";
       }else{
         return "<a href='#' class='toggle' ref='"+jQuery(this).attr("id")+"'></a>";
       }


     });

     /*
      When the toggle switch is clicked, check off / de-select the associated checkbox
     */
    jQuery('.toggle').live('click', function(e) {

       var checkbox = jQuery(this).prev();
	       var checkboxID = jQuery(this).attr("ref");
       var cleanid = (checkboxID.replace(/(\[|\])/g, '\\$1'));

       var $fold='.section.f_'+cleanid;
       var $unfold='.section.u_'+cleanid;

       $($fold).slideToggle('normal', "swing");
       $($unfold).slideToggle('normal', "swing");
       if (checkbox.is(":checked")) {
         checkbox.removeAttr("checked");
       }else{
         checkbox.attr("checked","true");
       }
       jQuery(this).toggleClass("checked");

       e.preventDefault();

    });

}); //end doc ready
</script>
<?php }

/*-----------------------------------------------------------------------------------*/
/* Load posts and portfolio - load_post_items */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_ajax_load_post_items', 'load_post_items' );
function load_post_items(){

	$nonce=$_POST['security'];

	if (! wp_verify_nonce($nonce, 'undsgn_ajax_nonce') ) die('-1');
	$itemArray = array();
	$itemArray[] = get_option('page_on_front');
	$itemArray[] = get_option('page_for_posts');

	// Query for items
	$args = array(
		'post_type' => array( 'post', 'portfolio', 'page' ),
		'posts_per_page' => -1,
		'post__not_in' => $itemArray,
		'post_status'=>'publish',
		'order' => 'ASC',
		'orderby' => 'modified'
	);
	$posts = new WP_Query( $args );

	// Initialise suggestions array
	$items=array();

	while ( $posts->have_posts() ) : $posts->the_post();
	// Initialise items array
	$item = array();
	$ID = get_the_ID();
	$item['ID'] = $ID;
	$item['title'] = ucfirst(esc_html(get_the_title()));
	$item['type'] = ucfirst(get_post_type($ID));
	$item['featured'] = esc_html(get_the_title());
	$item['imageurl'] = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ) );
	// Add suggestion to item array
	$items[]= $item;
	endwhile;

	// Reset Post Data
	wp_reset_postdata();

	// JSON encode and echo
	$response = json_encode($items);
	echo $response;

	// Don't forget to exit!
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action - undsgn_ajax_callback */
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_undsgn_ajax_post_action', 'undsgn_ajax_callback');

function undsgn_ajax_callback() {
	global $options_machine, $undsgn_options;

	$nonce=$_POST['security'];

	if (! wp_verify_nonce($nonce, 'undsgn_ajax_nonce') ) die('-1');

	//get options array from db
	$all = get_option(OPTIONS);

	$save_type = $_POST['type'];

	//Uploads
	if($save_type == 'upload'){

		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
		$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';
		$uploaded_file = wp_handle_upload($filename,$override);

		$upload_tracking[] = $clickedID;

		//update $options array w/ image URL
		$upload_image = $all; //preserve current data

		$upload_image[$clickedID] = $uploaded_file['url'];

		update_option(OPTIONS, $upload_image ) ;


		if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }
		else { echo $uploaded_file['url']; } // Is the Response

	}
	elseif($save_type == 'image_reset'){

		$id = $_POST['data']; // Acts as the name

		$delete_image = $all; //preserve rest of data
		$delete_image[$id] = ''; //update array key with empty value
		update_option(OPTIONS, $delete_image ) ;

	}
	elseif($save_type == 'backup_options'){

		$backup = $all;
		$backup['backup_log'] = date('r');

		update_option(BACKUPS, $backup ) ;

		die('1');
	}
	elseif($save_type == 'restore_options'){

		$data = get_option(BACKUPS);

		update_option(OPTIONS, $data);

		die('1');
	}
	elseif($save_type == 'import_options'){

		$data = $_POST['data'];
		$data = unserialize(base64_decode($data)); //100% safe - ignore theme check nag
		update_option(OPTIONS, $data);

		die('1'); 
	}
	elseif ($save_type == 'save') {

		wp_parse_str(stripslashes($_POST['data']), $data);
		unset($data['security']);
		unset($data['undsgn_save']);

		update_option(OPTIONS, $data);

		die('1');

	} elseif ($save_type == 'reset') {
		update_option(OPTIONS,$options_machine->Defaults);

		die(1); //options reset

	}

	die();

}


/*-----------------------------------------------------------------------------------*/
/* Class that Generates The Options Within the Panel - undsgn_machine */
/*-----------------------------------------------------------------------------------*/

class Options_Machine {

	function __construct($options) {

		$return = $this->undsgn_machine($options);

		$this->Inputs = $return[0];
		$this->Menu = $return[1];
		$this->Defaults = $return[2];

	}


	/*-----------------------------------------------------------------------------------*/
	/* Generates The Options Within the Panel - undsgn_machine */
	/*-----------------------------------------------------------------------------------*/

	public static function undsgn_machine($options) {

		$data = get_option(OPTIONS);

		$defaults = array();
		$counter = 0;
		$menu = '';
		$output = '';
		foreach ($options as $value) {

			$counter++;
			$val = '';

			//create array of defaults
			if ($value['type'] == 'multicheck'){
				if (is_array($value['std'])){
					foreach($value['std'] as $i=>$key){
						$defaults[$value['id']][$key] = true;
					}
				} else {
					$defaults[$value['id']][$value['std']] = true;
				}
			} else {
				if (isset($value['id'])) {
					if (isset($value['std'])) $defaults[$value['id']] = $value['std'];
				}
			}


			//Start Heading
			if ( $value['type'] != "heading" )
			{
				$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }

				$fold='';
				$unfold='';
				//hide items in checkbox-group
				if (array_key_exists("fold",$value)) {
					if ($data[$value['fold']]) {
						$fold="f_".$value['fold']." ";
					} else {
						$fold="f_".$value['fold']." temphide ";
					}
				}

				if (array_key_exists("unfold",$value)) {
					if ($data[$value['unfold']]) {
						$unfold="u_".$value['unfold']." temphide ";
					} else {

						$unfold="u_".$value['unfold']." ";
					}
				}

				$output .= '<div id="section-'.$value['id'].'" class="'.$fold.$unfold.'section section-'.$value['type'].' '. $class .'">'."\n";
				if ($value['name']) $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
				$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";

			}
			//End Heading

			switch ( $value['type'] ) {

			case 'text':
				$t_value = '';

				if (isset($data[$value['id']]) && $data[$value['id']]) $t_value = stripslashes($data[$value['id']]);

				$mini ='';
				if(!isset($value['mod'])) $value['mod'] = '';
				if($value['mod'] == 'mini') { $mini = 'mini';}

				$output .= '<input class="undsgn-input '.$mini.'" name="'.$value['id'].'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $t_value .'" />';
				break;
			case 'select':
				$mini ='';
				if(!isset($value['mod'])) $value['mod'] = '';
				if($value['mod'] == 'mini') { $mini = 'mini';}
				$output .= '<div class="select_wrapper ' . $mini . '">';
				$output .= '<select class="select undsgn-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
				foreach ($value['options'] as $select_ID => $option) {
					$output .= '<option id="' . $select_ID . '" value="'.$option.'" ' . selected($data[$value['id']], $option, false) . ' />'.$option.'</option>';
				}
				$output .= '</select></div>';
				break;
			case 'select2':
				$mini ='';
				if(!isset($value['mod'])) $value['mod'] = '';
				if($value['mod'] == 'mini') { $mini = 'mini';}
				$output .= '<div class="select_wrapper ' . $mini . '">';
				$output .= '<select class="select undsgn-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
				foreach ($value['options'] as $select_ID => $option) {
					$std = (isset($data[$value['id']])) ? $data[$value['id']] : $value['std'];
					$output .= '<option id="' . $select_ID . '" value="'.$select_ID.'" ' . selected($std, $select_ID, false) . ' />'.$option.'</option>';
				}
				$output .= '</select></div>';
				break;
			case 'textarea':
				$cols = '8';
				$ta_value = '';

				if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])){
						$cols = $ta_options['cols'];
					}
				}

				if (isset($data[$value['id']]) && $data[$value['id']]) $ta_value = stripslashes($data[$value['id']]);
				$output .= '<textarea class="undsgn-input" name="'.$value['id'].'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';
				break;
			case "radio":
				foreach($value['options'] as $option=>$name) {
					$std = (isset($data[$value['id']])) ? $data[$value['id']] : $value['std'];
					$output .= '<input class="undsgn-input undsgn-radio" name="'.$value['id'].'" type="radio" value="'.$option.'" ' . checked($std, $option, false) . ' /><label class="radio">'.$name.'</label><br/>';
				}
				break;
			case 'checkbox':
				if (!isset($data[$value['id']])) {
					$data[$value['id']] = 0;
				}

				if (array_key_exists("folds",$value)) {
					$fold="fld ";
				}

				$output .= '<input type="hidden" class="'.$fold.'checkbox aq-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="0"/>';
				$output .= '<input type="checkbox" class="'.$fold.'checkbox undsgn-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="1" '. checked($data[$value['id']], 1, false) .' />';
				break;
			case 'multicheck':
				$multi_stored = $data[$value['id']];

				foreach ($value['options'] as $key => $option) {
					if (!isset($multi_stored[$key])) {$multi_stored[$key] = '';}
					$undsgn_key_string = $value['id'] . '_' . $key;
					$output .= '<input type="checkbox" class="checkbox undsgn-input" name="'.$value['id'].'['.$key.']'.'" id="'. $undsgn_key_string .'" value="1" '. checked($multi_stored[$key], 1, false) .' /><label class="multicheck" for="'. $undsgn_key_string .'">'. $option .'</label><br />';
				}
				break;
			case 'upload':
				if(!isset($value['mod'])) $value['mod'] = '';
				$output .= Options_Machine::undsgn_uploader_function($value['id'],$value['std'],$value['mod']);
				break;
			case 'media':
				$_id = strip_tags( strtolower($value['id']) );
				$int = '';
				$int = undsgn_mlu_get_silentpost( $_id );
				if(!isset($value['mod'])) $value['mod'] = '';
				$output .= Options_Machine::undsgn_media_uploader_function( $value['id'], $value['std'], $int, $value['mod'] ); // New AJAX Uploader using Media Library
				break;
			case 'color':
				$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div style="background-color: '.$data[$value['id']].'"></div></div>';
				$output .= '<input class="undsgn-color" name="'.$value['id'].'" id="'. $value['id'] .'" type="text" value="'. $data[$value['id']] .'" />';
				break;
			case 'typography':

				$typography_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];

				/* Font Size */

				if(isset($typography_stored['size'])) {
					$output .= '<div class="select_wrapper typography-size" original-title="Font size">';
					$output .= '<select class="undsgn-typography undsgn-typography-size select" name="'.$value['id'].'[size]" id="'. $value['id'].'_size">';
					for ($i = 9; $i < 20; $i++){
						$test = $i.'px';
						$output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $test, false) . '>'. $i .'px</option>';
					}

					$output .= '</select></div>';

				}

				/* Line Height */

				if(isset($typography_stored['height'])) {

					$output .= '<div class="select_wrapper typography-height" original-title="Line height">';
					$output .= '<select class="undsgn-typography undsgn-typography-height select" name="'.$value['id'].'[height]" id="'. $value['id'].'_height">';
					for ($i = 20; $i < 38; $i++){
						$test = $i.'px';
						$output .= '<option value="'. $i .'px" ' . selected($typography_stored['height'], $test, false) . '>'. $i .'px</option>';
					}

					$output .= '</select></div>';

				}

				/* Font Face */

				if(isset($typography_stored['face'])) {

					$output .= '<div class="select_wrapper typography-face" original-title="Font family">';
					$output .= '<select class="undsgn-typography undsgn-typography-face select" name="'.$value['id'].'[face]" id="'. $value['id'].'_face">';

					$faces = array('arial'=>'Arial',
						'verdana'=>'Verdana, Geneva',
						'trebuchet'=>'Trebuchet',
						'georgia' =>'Georgia',
						'times'=>'Times New Roman',
						'tahoma'=>'Tahoma, Geneva',
						'palatino'=>'Palatino',
						'helvetica'=>'Helvetica' );
					foreach ($faces as $i=>$face) {
						$output .= '<option value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
					}

					$output .= '</select></div>';

				}

				/* Font Weight */

				if(isset($typography_stored['style'])) {

					$output .= '<div class="select_wrapper typography-style" original-title="Font style">';
					$output .= '<select class="undsgn-typography undsgn-typography-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
					$styles = array('normal'=>'Normal',
						'italic'=>'Italic',
						'bold'=>'Bold',
						'bold italic'=>'Bold Italic');

					foreach ($styles as $i=>$style){

						$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';
					}
					$output .= '</select></div>';

				}

				/* Font Color */

				if(isset($typography_stored['color'])) {

					$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector typography-color"><div style="background-color: '.$typography_stored['color'].'"></div></div>';
					$output .= '<input class="undsgn-color undsgn-typography undsgn-typography-color" original-title="Font color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'" />';

				}

				break;
			case 'border':

				/* Border Width */
				$border_stored = $data[$value['id']];

				$output .= '<div class="select_wrapper border-width">';
				$output .= '<select class="undsgn-border undsgn-border-width select" name="'.$value['id'].'[width]" id="'. $value['id'].'_width">';
				for ($i = 0; $i < 21; $i++){
					$output .= '<option value="'. $i .'" ' . selected($border_stored['width'], $i, false) . '>'. $i .'</option>';     }
				$output .= '</select></div>';

				/* Border Style */
				$output .= '<div class="select_wrapper border-style">';
				$output .= '<select class="undsgn-border undsgn-border-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';

				$styles = array('none'=>'None',
					'solid'=>'Solid',
					'dashed'=>'Dashed',
					'dotted'=>'Dotted');

				foreach ($styles as $i=>$style){
					$output .= '<option value="'. $i .'" ' . selected($border_stored['style'], $i, false) . '>'. $style .'</option>';
				}

				$output .= '</select></div>';

				/* Border Color */
				$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color: '.$border_stored['color'].'"></div></div>';
				$output .= '<input class="undsgn-color undsgn-border undsgn-border-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $border_stored['color'] .'" />';

				break;
			case 'images':

				$i = 0;

				$select_value = $data[$value['id']];

				foreach ($value['options'] as $key => $option)
				{
					$i++;

					$checked = '';
					$selected = '';
					if(NULL!=checked($select_value, $key, false)) {
						$checked = checked($select_value, $key, false);
						$selected = 'undsgn-radio-img-selected';
					}
					$output .= '<span>';
					$output .= '<input type="radio" id="undsgn-radio-img-' . $value['id'] . $i . '" class="checkbox undsgn-radio-img-radio" value="'.$key.'" name="'.$value['id'].'" '.$checked.' />';
					$output .= '<div class="undsgn-radio-img-label">'. $key .'</div>';
					$output .= '<img src="'.$option.'" alt="" class="undsgn-radio-img-img '. $selected .'" onClick="document.getElementById(\'undsgn-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
					$output .= '</span>';
				}

				break;
			case "info":
				$info_text = $value['std'];
				$output .= '<div class="undsgn-info">'.$info_text.'</div>';
				break;
			case "image":
				$src = $value['std'];
				$output .= '<img src="'.$src.'">';
				break;
			case 'heading':
				if($counter >= 2){
					$output .= '</div>'."\n";
				}
				$header_class = preg_replace("/[^A-Za-z0-9]/", "", strtolower($value['name']) );
				$jquery_click_hook = preg_replace("/[^A-Za-z0-9]/", "", strtolower($value['name']) );
				$jquery_click_hook = "undsgn-option-" . $jquery_click_hook;
				$menu .= '<li class="'. $header_class .'"><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
				$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
				break;
			case 'slider':
				$_id = strip_tags( strtolower($value['id']) );
				$int = '';
				$int = undsgn_mlu_get_silentpost( $_id );
				$output .= '<div class="slider"><ul id="'.$value['id'].'" rel="'.$int.'">';
				if (isset($data[$value['id']]) && $data[$value['id']]) {
					$slides = $data[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::undsgn_slider_function($value['id'],$value['std'],$oldorder,$order,$int);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::undsgn_slider_function($value['id'],$value['std'],$oldorder,$order,$int);
						}
					}
				}
				$output .= '</ul>';
				$output .= '<a href="#" class="button slide_add_button">Add New Slide</a></div>';

				break;
			case 'frontpage':
				$_id = strip_tags( strtolower($value['id']) );
				$int = '';
				$int = undsgn_mlu_get_silentpost( $_id );
				$output .= '<div class="slider"><input type="checkbox" class="toggleIncluded" /><ul id="'.$value['id'].'" class="frontpagelist" rel="'.$int.'">';
				if (isset($data[$value['id']]) && $data[$value['id']]) {
					$slides = $data[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::undsgn_frontpage_function($value['id'],$value['std'],$oldorder,$order,$int);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::undsgn_frontpage_function($value['id'],$value['std'],$oldorder,$order,$int);
						}
					}
				}
				$output .= '</ul>';
				$output .= '<a href="#" class="button front_add_content">Load content</a></div>';

				break;
			case 'dynalist':
				$_id = strip_tags( strtolower($value['id']) );
				$int = '';
				$int = undsgn_mlu_get_silentpost( $_id );
				$output .= '<div class="slider"><ul id="'.$value['id'].'" rel="'.$int.'">';
				if (isset($data[$value['id']]) && $data[$value['id']]) {
					$slides = $data[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::undsgn_dynalist_function($value['id'],$value['std'],$oldorder,$order,$int);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::undsgn_dynalist_function($value['id'],$value['std'],$oldorder,$order,$int);
						}
					}
				}
				$output .= '</ul>';
				$output .= '<a href="#" class="button list_add_button">Add New Detail</a></div>';

				break;
			case 'sorter':

				$sortlists = isset($data[$value['id']]) && !empty($data[$value['id']]) ? $data[$value['id']] : $value['std'];

				$output .= '<div id="'.$value['id'].'" class="sorter">';


				if ($sortlists) {

					foreach ($sortlists as $group=>$sortlist) {

						$output .= '<ul id="'.$value['id'].'_'.$group.'" class="sortlist_'.$value['id'].'">';
						$output .= '<h3>'.$group.'</h3>';

						foreach ($sortlist as $key => $list) {

							$output .= '<input class="sorter-placebo" type="hidden" name="'.$value['id'].'['.$group.'][placebo]" value="placebo">';

							if ($key != "placebo") {

								$output .= '<li id="'.$key.'" class="sortee">';
								$output .= '<input class="position" type="hidden" name="'.$value['id'].'['.$group.']['.$key.']" value="'.$list.'">';
								$output .= $list;
								$output .= '</li>';

							}

						}

						$output .= '</ul>';
					}
				}

				$output .= '</div>';
				break;
			case 'tiles':

				$i = 0;
				$select_value = '';
				$select_value = $data[$value['id']];

				foreach ($value['options'] as $key => $option)
				{
					$i++;

					$checked = '';
					$selected = '';
					if(NULL!=checked($select_value, $option, false)) {
						$checked = checked($select_value, $option, false);
						$selected = 'undsgn-radio-tile-selected';
					}
					$output .= '<span>';
					$output .= '<input type="radio" id="undsgn-radio-tile-' . $value['id'] . $i . '" class="checkbox undsgn-radio-tile-radio" value="'.$option.'" name="'.$value['id'].'" '.$checked.' />';
					$output .= '<div class="undsgn-radio-tile-img '. $selected .'" style="background: url('.$option.')" onClick="document.getElementById(\'undsgn-radio-tile-'. $value['id'] . $i.'\').checked = true;"></div>';
					$output .= '</span>';
				}

				break;
				// Background
			case 'background':

				$background = $data[$value['id']];

				// Background Color
				$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $background['color'] ) . '"></div></div>';
				$output .= '<input class="undsgn-color undsgn-background undsgn-background-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $background['color'] ) . '" />';

				// Background Image - New AJAX Uploader using Media Library
				if (!isset($background['image'])) {
					$background['image'] = '';
				}

				$output .= undsgn_medialibrary_uploader( $value['id'], $background['image'], null, '',0,'image');
				$class = 'undsgn-background-properties';
				if ( '' == $background['image'] ) {
					$class .= ' hide';
				}
				$output .= '<div class="' . esc_attr( $class ) . '">';

				// Background Repeat
				$output .= '<select class="undsgn-background undsgn-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '][repeat]'  ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
				$repeats = undsgn_recognized_background_repeat();

				foreach ($repeats as $key => $repeat) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
				}
				$output .= '</select>';

				// Background Position
				$output .= '<select class="undsgn-background undsgn-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '][position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
				$positions = undsgn_recognized_background_position();

				foreach ($positions as $key=>$position) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
				}
				$output .= '</select>';

				// Background Attachment
				$output .= '<select class="undsgn-background undsgn-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '][attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
				$attachments = undsgn_recognized_background_attachment();

				foreach ($attachments as $key => $attachment) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
				}
				$output .= '</select>';
				$output .= '</div>';

				break;
			case 'iconic':
				$output .= '<div class="upload_button_div">';
				$output .= '<a href="#" id="undsgn_entypo_button" class="button" title="Install Entypo">Download and Install Entypo Iconic Font</a>';
				$output .= '</div>';
				break;
			case 'backup':

				/* $instructions = $value['options']; */
				$backup = get_option(BACKUPS);

				if(!isset($backup['backup_log'])) {
					$log = 'No backups yet';
				} else {
					$log = $backup['backup_log'];
				}

				$output .= '<div class="backup-box upload_button_div"><div>';
				/* $output .= '<div class="instructions">'.$instructions."\n"; */
				$output .= '<p><strong>'. __('Last Backup : ', 'undsgnoptions' ).'<span class="backup-log">'.$log.'</span></strong></p></div>'."\n";
				$output .= '<a href="#" id="undsgn_backup_button" class="button" title="Backup Options">Backup Options</a>';
				$output .= '<a href="#" id="undsgn_restore_button" class="button" title="Restore Options">Restore Options</a>';
				$output .= '</div>';

				break;
			
			//export or import data between different installs
				case 'transfer':

					$instructions = $value['desc'];
					$output .= '<textarea id="export_data" rows="8">'.base64_encode(serialize($data)) /* 100% safe - ignore theme check nag */ .'</textarea>'."\n";
					$output .= '<div class="upload_button_div"><a href="#" id="undsgn_import_button" class="button" title="Restore Options">Import Options</a></div>';

				break;
			}

			// if TYPE is an array, formatted into smaller inputs... ie smaller values
			if ( is_array($value['type'])) {
				foreach($value['type'] as $array){

					$id = $array['id'];
					$std = $array['std'];
					$saved_std = get_option($id);
					if($saved_std != $std){$std = $saved_std;}
					$meta = $array['meta'];

					if($array['type'] == 'text') { // Only text at this point

						$output .= '<input class="input-text-small undsgn-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';
						$output .= '<span class="meta-two">'.$meta.'</span>';
					}
				}
			}
			if ( $value['type'] != 'heading' ) {
				if(!isset($value['desc'])){ $explain_value = ''; } else{
					$explain_value = '<div class="explain">'. $value['desc'] .'</div>'."\n";
				}
				$output .= '</div>'.$explain_value."\n";
				$output .= '<div class="clear"> </div></div></div>'."\n";
			}

		}
		$output .= '</div>';
		return array($output,$menu,$defaults);
	}


	/*-----------------------------------------------------------------------------------*/
	/* Aquagraphite Uploader - undsgn_uploader_function */
	/*-----------------------------------------------------------------------------------*/

	public static function undsgn_uploader_function($id,$std,$mod){

		$data =get_option(OPTIONS);

		$uploader = '';
		$upload = $data[$id];
		$hide = '';

		if ($mod == "min") {$hide ='hide';}

		if ( $upload != "") { $val = $upload; } else {$val = $std;}

		$uploader .= '<input class="'.$hide.' upload undsgn-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" />';

		$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">'._('Upload').'</span>';

		if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
		$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
		$uploader .='</div>' . "\n";
		$uploader .= '<div class="clear"></div>' . "\n";
		if(!empty($upload)){
			$uploader .= '<div class="screenshot">';
			$uploader .= '<a class="undsgn-uploaded-image" href="'. $upload . '">';
			$uploader .= '<img class="undsgn-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
			$uploader .= '</a>';
			$uploader .= '</div>';
		}
		$uploader .= '<div class="clear"></div>' . "\n";

		return $uploader;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Aquagraphite Media Uploader - undsgn_media_uploader_function */
	/*-----------------------------------------------------------------------------------*/
	public static function undsgn_media_uploader_function($id,$std,$int,$mod){

		$data = get_option(OPTIONS);

		$uploader = '';
		$upload = $data[$id];
		$hide = '';

		if ($mod == "min") {$hide ='hide';}

		if ( $upload != "") { $val = $upload; } else {$val = $std;}

		$uploader .= '<input class="'.$hide.' upload undsgn-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" />';

		$uploader .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'" rel="">Upload</span>';

		if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
		$uploader .= '<span class="button mlu_remove_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
		$uploader .='</div>' . "\n";
		$uploader .= '<div class="screenshot">';
		if(!empty($upload)){
			$uploader .= '<a class="undsgn-uploaded-image" href="'. $upload . '">';
			$uploader .= '<img class="undsgn-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
			$uploader .= '</a>';
		}
		$uploader .= '</div>';
		$uploader .= '<div class="clear"></div>' . "\n";

		return $uploader;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Aquagraphite Slider - undsgn_slider_function */
	/*-----------------------------------------------------------------------------------*/

	public static function undsgn_slider_function($id,$std,$oldorder,$order,$int){

		$data = get_option(OPTIONS);

		$slider = '';
		$slide = array();
		$slide = $data[$id];

		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}

		//initialize all vars
		$slidevars = array('title','url','link','linktext','description');

		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}

		//begin slider interface
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>Slide '.$order.'</strong>';
		}

		$slider .= '<input type="hidden" class="slide undsgn-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';

		$slider .= '<a class="slide_edit_button" href="#">Edit</a></div>';

		$slider .= '<div class="slide_body">';
		$selectedimage = ($val['type'] == 'image') ? 'selected="selected"' : '';
		$selectedhtml = ($val['type'] == 'html') ? 'selected="selected"' : '';
		$slider .= '<select class="select undsgn-input type_select" name="'. $id .'['.$order.'][type]" id="'. $id .'_'.$order .'_type"><option id="'. $id .'_'.$order .'_imagecont" value="image" '.$selectedimage.'>Image</option><option id="'. $id .'_'.$order .'_htmlcont" value="html" '.$selectedhtml.'>HTML</option></select>';

		if ($val['type'] == 'html') $slider .= '<div style="display: none" class="'. $id .'_'.$order .'_imagecont">';
		else $slider .= '<div class="'. $id .'_'.$order .'_imagecont">';

		$slider .= '<label>Title</label>';
		$slider .= '<input class="slide undsgn-input undsgn-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';

		$slider .= '<label>Image URL</label>';
		$slider .= '<input class="slide undsgn-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';

		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'" rel="' . $int . '">Upload</span>';

		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button mlu_remove_button '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">Remove</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){

			$slider .= '<a class="undsgn-uploaded-image" href="'. $val['url'] . '">';
			$slider .= '<img class="undsgn-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
			$slider .= '</a>';

		}
		$slider .= '</div>';
		$slider .= '<label>Link URL (optional)</label>';
		$slider .= '<input class="slide undsgn-input" name="'. $id .'['.$order.'][link]" id="'. $id .'_'.$order .'_slide_link" value="'. $val['link'] .'" />';

		$slider .= '<label>Link text (optional)</label>';
		$slider .= '<input class="slide undsgn-input" name="'. $id .'['.$order.'][linktext]" id="'. $id .'_'.$order .'_slide_linktext" value="'. $val['linktext'] .'" />';

		$slider .= '<label>Description (optional)</label>';
		$slider .= '<textarea class="slide undsgn-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea></div>';

		if ($val['type'] == 'image') $slider .= '<div style="display: none" class="'. $id .'_'.$order .'_htmlcont">';
		else $slider .= '<div class="'. $id .'_'.$order .'_htmlcont">';

		$slider .= '<label>HTML Text</label>';
		$slider .= '<textarea class="slide undsgn-input" name="'. $id .'['.$order.'][htmltext]" id="'. $id .'_'.$order .'_slide_htmltext" cols="8" rows="8">'.stripslashes($val['htmltext']).'</textarea></div>';

		$slider .= '<a class="slide_delete_button" href="#">Delete</a>';
		$slider .= '<div class="clear"></div>' . "\n";

		$slider .= '</div>';
		$slider .= '</li>';

		return $slider;
	}

	/*-----------------------------------------------------------------------------------*/
	/* dynalist - undsgn_dynalist_function */
	/*-----------------------------------------------------------------------------------*/

	public static function undsgn_dynalist_function($id,$std,$oldorder,$order,$int){

		$data = get_option(OPTIONS);

		$slider = '';
		$slide = array();
		$slide = $data[$id];

		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}

		//initialize all vars
		$slidevars = array('title');

		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}

		//begin slider interface

		$slider .= '<li class="dynalist"><input type="hidden" class="slide undsgn-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';

		$slider .= '<label>Label</label>';
		$slider .= '<input class="slide undsgn-input undsgn-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';

		$slider .= '<a class="slide_delete_button" href="#">Delete</a></li>';

		return $slider;
	}


	/*-----------------------------------------------------------------------------------*/
	/* frontpage - undsgn_frontpage_function */
	/*-----------------------------------------------------------------------------------*/

	public static function undsgn_frontpage_function($id,$std,$oldorder,$order,$int){

		$data = get_option(OPTIONS);

		$slider = '';
		$slide = array();
		$slide = $data[$id];

		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		if ($val == "") $val = array_pop($slide);
		//initialize all vars
		$slidevars = array('ID');

		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}

		$imgurl = wp_get_attachment_image_src( get_post_thumbnail_id( $val['ID'] ) );

		//begin slider interface
		$slider .= '<li>';
		if ($imgurl) $slider .= '<div class="imgcont"><img src="'.$imgurl[0].'" alt="" /></div><div class="slide_header"><span class="title">'.ucfirst(stripslashes(get_the_title($val['ID']))).'</span>';
		else $slider .= '<div class="imgcont"><span>NO IMAGE</span></div><div class="slide_header"><span class="title">'.ucfirst(stripslashes(get_the_title($val['ID']))).'</span>';

		$slider .= '<span class="category">'.ucfirst(get_post_type($val['ID'])).'</span>';
		$slider .= '<input type="hidden" class="slide undsgn-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
		$slider .= '<input type="hidden" class="slide undsgn-input undsgn-slider-ID" name="'. $id .'['.$order.'][ID]" id="'. $id .'_'.$order .'_slide_ID" value="'. $val['ID'] .'" />';
		$slider .= '<input type="checkbox" class="prettycheck" name="included" id="included-'.$order .'" value="1" checked="checked" />';
		$slider .= '<a class="slide_delete_button" href="#">Delete</a>';
		$slider .= '<div class="clear"></div>' . "\n";


		$slider .= '</div>';
		$slider .= '</li>';

		return $slider;
	}

	/*-----------------------------------------------------------------------------------*/
	/* End Class
/*-----------------------------------------------------------------------------------*/
} //end class