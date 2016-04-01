<?php
/*-----------------------------------------------------------------------------------*/
/* Head Hook
/*-----------------------------------------------------------------------------------*/

function undsgn_head() { do_action( 'undsgn_head' ); }

/*-----------------------------------------------------------------------------------*/
/* Add default options after activation */
/*-----------------------------------------------------------------------------------*/
if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	//Call action that sets
	add_action('admin_head','undsgn_option_setup');
}

/* set options=defaults if DB entry does not exist, else update defaults only */
function undsgn_option_setup()	{
	global $undsgn_options, $options_machine;
	$options_machine = new Options_Machine($undsgn_options);
		
	if (!get_option(OPTIONS)){
		update_option(OPTIONS,$options_machine->Defaults);
	}
}

/*-----------------------------------------------------------------------------------*/
/* Admin Backend */
/*-----------------------------------------------------------------------------------*/
function undsgn_admin_message() { 
	
	//Tweaked the message on theme activate
	?>
    <script type="text/javascript">
    jQuery(function(){
    	
        var message = '<p>This theme comes with an <a href="<?php echo admin_url('admin.php?page=undsgnoptions'); ?>">options panel</a> to configure settings. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';
    	jQuery('.themes-php #message2').html(message);
    
    });
    </script>
    <?php
	
}

add_action('admin_head', 'undsgn_admin_message'); 


/*-----------------------------------------------------------------------------------*/
/* Small function to get all header classes */
/*-----------------------------------------------------------------------------------*/

	function undsgn_get_header_classes_array() {
		global $undsgn_options;
		
		foreach ($undsgn_options as $value) {
			
			if ($value['type'] == 'heading') {
				$hooks[] = preg_replace("/[^A-Za-z0-9]/", "", strtolower($value['name']) );
			}
			
		}
		
		return $hooks;
		
	}


/* For use in themes */
$data = get_option(OPTIONS);
?>
