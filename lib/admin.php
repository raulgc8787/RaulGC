<?php

/*-----------------------------------------------------------------------------------*/
// Options Framework
/*-----------------------------------------------------------------------------------*/

// Paths to admin functions
define('ADMIN_PATH', '/lib/admin/');
define('ADMIN_DIR', get_template_directory_uri() . '/lib/admin/');
define('LAYOUT_PATH', get_stylesheet_directory() . '/lib/css/layout-color/');

$themedata;
if (function_exists('wp_get_theme')){
	$themedata = wp_get_theme('studiofolio');
} else {
	$themedata = (object) get_theme_data(get_template_directory() . '/style.css');
}

define('THEMENAME', strtolower($themedata->Name));
define('THEMEVERSION', $themedata->Version);
define('OPTIONS', 'undsgn_options'); // Name of the database row where your options are stored
define('BACKUPS','undsgn_backups'); // Name of the database row for options backup

// Build Options
require_once locate_template(ADMIN_PATH . 'theme-options.php'); 		// Options panel settings and custom settings
require_once locate_template(ADMIN_PATH . 'admin-functions.php'); 		// Theme actions based on options settings
require_once locate_template(ADMIN_PATH . 'admin-interface.php');		// Admin Interfaces 
require_once locate_template(ADMIN_PATH . 'medialibrary-uploader.php'); // Media Library Uploader

/*-----------------------------------------------------------------------------------*/
// Options Framework ----- END
/*-----------------------------------------------------------------------------------*/

// add metaboxes
define('METABOXES_PATH', '/lib/metaboxes/');

require_once locate_template(METABOXES_PATH . 'setup.php');
require_once locate_template(METABOXES_PATH . 'pages-spec.php');
require_once locate_template(METABOXES_PATH . 'posts-spec.php');
require_once locate_template(METABOXES_PATH . 'all-spec.php');
require_once locate_template(METABOXES_PATH . 'portfolio-spec.php');
require_once locate_template(METABOXES_PATH . 'portfolio-page-spec.php');
require_once locate_template(METABOXES_PATH . 'gallery-spec.php');
require_once locate_template(METABOXES_PATH . 'gallery-collection-spec.php');
require_once locate_template(METABOXES_PATH . 'contact-spec.php');

//----- Get thumbnail path for portfolio meta boxes

add_action("wp_ajax_studiofolio_get_thumburl", "studiofolio_get_thumburl");
add_action("wp_ajax_nopriv_studiofolio_get_thumburl", "studiofolio_must_login");

function studiofolio_get_thumburl() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "studiofolio_get_thumburl_nonce")) {
      exit("No naughty business please");
   }   
	
   $thumburl = wp_get_attachment_image_src( $_REQUEST["thumb_id"], 'thumbnail' );

   if($thumburl === false) {
      $result['type'] = "error";
   }
   else {
      $result['type'] = "success";
      $result['thumburl'] = $thumburl[0];
   }

   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $result = json_encode($result);
      echo $result;
   }
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   }

   die();

}

function studiofolio_must_login() {
   echo "You must log in";
   die();
}

//----- Add Entypo Iconic Font

add_action("wp_ajax_add_entypo", "add_entypo");

function add_entypo() {   
	
   	function ezip($zip, $hedef = '')
		{
				$file = $hedef . 'entypo.zip';
				$client = curl_init($zip);
				curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);  //fixed this line
				$fileData = curl_exec($client);
		
		    file_put_contents($file, $fileData);
		    $zip = zip_open($file);
		    while($zip_icerik = zip_read($zip)):
		        $zip_dosya = zip_entry_name($zip_icerik);
		        if(strpos($zip_dosya, '.')):
		            $hedef_yol = $hedef .$zip_dosya;
		            touch($hedef_yol);
		            $yeni_dosya = fopen($hedef_yol, 'w');
		            $buf = zip_entry_read($zip_icerik, zip_entry_filesize($zip_icerik));
					      fwrite($yeni_dosya,"$buf");
					      zip_entry_close($zip_icerik);
					      fclose($yeni_dosya); 
					      chmod($hedef_yol, 0777);
		            
		        else:
		            @mkdir($hedef . $zip_dosya);
		            chmod($hedef . $zip_dosya, 0777);
		        endif;
		    endwhile;
		    zip_close($zip);
		    unlink($file);
		}
		
		ezip('http://static.undsgn.com/download/fonts/entypo.zip', get_stylesheet_directory().'/assets/css/fonts/');

		die();

}