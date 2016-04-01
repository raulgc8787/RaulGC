<?php 
global $data; 
$classmenu = (isset($data['inline_menu']) && $data['inline_menu'] && !$data['left_menu']) ? ' inline_menu' : ' newline_menu';
$blog_title = get_bloginfo('name');
if (LOGO_IMG) $logo = "<img src='".LOGO_IMG."' alt='Raul Garcia Castilla graphic design, illustrtor, UX/UI, Sabadell' title='RaulGC ilustrador, diseñador, UX/UI, Barcelona' />";
else $logo = get_bloginfo('name'); 

if ((isset($data['inline_menu']) && $data['inline_menu']) || (isset($data['left_menu']) && $data['left_menu'])) {
	$menudiv = '<div id="inner-logo">
                    <a id="logo" href="'.home_url().'/">'.$logo.'</a>
                </div>';
} else {
	$menudiv = '<div id="logo-container" class="container-fluid">
	                <div class="span12">
	                    <div id="inner-logo">
	                        <a id="logo" href="'.home_url().'/">'.$logo.'</a>
	                    </div>
	                </div>
	            </div>';
} ?>
<div id="background"></div>
<div id="wrapper">
    <div class="fixed-wrap">
        <header id="banner" class="navbar fixed-menu" role="banner">
        
        <?php if (!isset($data['inline_menu']) || !$data['inline_menu'] || $data['left_menu']) echo $menudiv; ?>

            <div id="main-menu" class="row-fluid<?php echo $classmenu; ?>">
                <div class="container-fluid">
                	
                <?php if (isset($data['inline_menu']) && $data['inline_menu'] && !$data['left_menu']) echo $menudiv; ?>
                	
                    <div id="inner-menu">
                        <div class="navbar-inner menu-cont hvr">
                            <!-- inner-brand -->
                             <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse-main">
                             	<span>Menu</span>
                             	<span class="menu-icon">
	                             	<i data-icon="&#xe088;"></i>
	                            </span>
                             </a>

                            <div class="clearfix"></div>
                        </div><!-- top-menu -->

                        <nav id="nav-main" class="nav-collapse nav-collapse-main" role="navigation"><?php wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav main-menu')); ?></nav>
                        
                    </div>
                    
                    <?php include(locate_template('templates/socials.php')); ?>
                    
                </div>
                
                <?php if (isset($data['vertical_message']) && $data['vertical_message'] && $data['left_menu']) { ?>
                	<div class="vertical-message"><?php echo $data['vertical_message']; ?></div>
                <?php } ?>
                        
            </div>
        </header><!-- header -->
    </div><!-- fixed-wrap -->
